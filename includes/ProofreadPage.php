<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup ProofreadPage
 */

namespace ProofreadPage;

use ImagePage;
use MediaWiki\ChangeTags\Hook\ChangeTagsListActiveHook;
use MediaWiki\ChangeTags\Hook\ListDefinedTagsHook;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Hook\CanonicalNamespacesHook;
use MediaWiki\Hook\EditFormPreloadTextHook;
use MediaWiki\Hook\GetDoubleUnderscoreIDsHook;
use MediaWiki\Hook\GetLinkColoursHook;
use MediaWiki\Hook\InfoActionHook;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Hook\RecentChange_saveHook;
use MediaWiki\Hook\SkinTemplateNavigation__UniversalHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\Hook\BeforePageDisplayHook;
use MediaWiki\Output\Hook\OutputPageParserOutputHook;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Hook\ImageOpenShowImageInlineBeforeHook;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Preferences\Hook\GetPreferencesHook;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Revision\Hook\ContentHandlerDefaultModelForHook;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\Hook\WgQueryPagesHook;
use MediaWiki\Status\Status;
use MediaWiki\Storage\Hook\MultiContentSaveHook;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use OutOfBoundsException;
use ProofreadPage\Index\IndexTemplateStyles;
use ProofreadPage\Page\DatabasePageQualityLevelLookup;
use ProofreadPage\Page\PageContent;
use ProofreadPage\Page\PageContentBuilder;
use ProofreadPage\Page\PageDisplayHandler;
use ProofreadPage\Page\PageRevisionTagger;
use ProofreadPage\Pagination\PageNotInPaginationException;
use ProofreadPage\Parser\PagelistTagParser;
use ProofreadPage\Parser\PagequalityTagParser;
use ProofreadPage\Parser\PagesTagParser;
use ProofreadPage\Parser\TranslusionPagesModifier;
use Skin;
use SkinTemplate;

/*
 @todo :
 - check uniqueness of the index page : when index is saved too
*/

class ProofreadPage implements
	RecentChange_saveHook,
	SkinTemplateNavigation__UniversalHook,
	OutputPageParserOutputHook,
	ParserFirstCallInitHook,
	GetLinkColoursHook,
	GetPreferencesHook,
	BeforePageDisplayHook,
	ImageOpenShowImageInlineBeforeHook,
	WgQueryPagesHook,
	CanonicalNamespacesHook,
	ContentHandlerDefaultModelForHook,
	EditFormPreloadTextHook,
	MultiContentSaveHook,
	InfoActionHook,
	ListDefinedTagsHook,
	ChangeTagsListActiveHook,
	GetDoubleUnderscoreIDsHook
{

	/** @var Config */
	private $config;

	/**
	 * @param Config $config
	 */
	public function __construct( Config $config ) {
		$this->config = $config;
	}

	/**
	 * @deprecated use Context::getPageNamespaceId
	 *
	 * Returns id of Page namespace.
	 *
	 * @return int
	 */
	public static function getPageNamespaceId() {
		return ProofreadPageInit::getNamespaceId( 'page' );
	}

	/**
	 * @deprecated use Context::getIndexNamespaceId
	 *
	 * Returns id of Index namespace.
	 *
	 * @return int
	 */
	public static function getIndexNamespaceId() {
		return ProofreadPageInit::getNamespaceId( 'index' );
	}

	/**
	 * @deprecated
	 * @return string[]
	 */
	public static function getPageAndIndexNamespace() {
		static $res;
		if ( $res === null ) {
			global $wgExtraNamespaces;
			$res = [
				preg_quote( $wgExtraNamespaces[self::getPageNamespaceId()], '/' ),
				preg_quote( $wgExtraNamespaces[self::getIndexNamespaceId()], '/' ),
			];
		}
		return $res;
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/wgQueryPages
	 *
	 * @param array[] &$queryPages
	 */
	public function onWgQueryPages( &$queryPages ) {
		$queryPages[] = [ 'SpecialProofreadPages', 'IndexPages' ];
		$queryPages[] = [ 'SpecialPagesWithoutScans', 'PagesWithoutScans' ];
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ContentHandlerDefaultModelFor
	 *
	 * @param Title $title the title page
	 * @param string &$model the content model for the page
	 * @return bool if we have to continue the research for a content handler
	 */
	public function onContentHandlerDefaultModelFor( $title, &$model ) {
		// Warning: do not use Context here because it assumes ContentHandler is already initialized
		if ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			$model = CONTENT_MODEL_PROOFREAD_PAGE;
			return false;
		} elseif ( $title->inNamespace( self::getIndexNamespaceId() ) && !$title->isSubpage() ) {
			$model = CONTENT_MODEL_PROOFREAD_INDEX;
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Set up our custom parser hooks when initializing parser.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 *
	 * @param Parser $parser
	 */
	public function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'pagelist', static function ( $input, array $args, Parser $parser ) {
			$context = Context::getDefaultContext( true );
			$tagParser = new PagelistTagParser( $parser, $context );
			return $tagParser->render( $args );
		} );
		$parser->setHook( 'pages', static function ( $input, array $args, Parser $parser ) {
			$context = Context::getDefaultContext( true );
			$tagParser = new PagesTagParser( $parser, $context );
			return $tagParser->render( $args );
		} );
		$parser->setHook( 'pagequality', static function ( $input, array $args, Parser $parser ) {
			$tagParser = new PagequalityTagParser();
			return $tagParser->render( $args );
		} );
	}

	/**
	 * Loads JS modules
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		$title = $out->getTitle();

		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			$out->addModuleStyles( 'ext.proofreadpage.base' );
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			$out->addModuleStyles( 'ext.proofreadpage.page.navigation' );
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetLinkColours
	 *
	 * @param string[] $linkcolour_ids Prefixed DB keys of the pages linked to, indexed by page_id
	 * @param string[] &$colours CSS classes, indexed by prefixed DB keys
	 * @param Title $title Title of the page being parsed, on which the links will be shown
	 */
	public function onGetLinkColours( $linkcolour_ids, &$colours, $title ) {
		$context = Context::getDefaultContext();
		$inIndexNamespace = $title->inNamespace( $context->getIndexNamespaceId() );
		$pageQualityLevelLookup = $context->getPageQualityLevelLookup();

		$pageTitles = array_map( [ Title::class, 'newFromText' ], $linkcolour_ids );
		$pageQualityLevelLookup->prefetchQualityLevelForTitles( $pageTitles );

		/** @var Title|null $pageTitle */
		foreach ( $pageTitles as $pageTitle ) {
			if ( $pageTitle !== null && $pageTitle->inNamespace( $context->getPageNamespaceId() ) ) {
				$pageLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $pageTitle );
				if ( $pageLevel !== null ) {
					$colours[$pageTitle->getPrefixedDBkey()] = self::getQualityClassesForQualityLevel(
							$pageLevel,
							$inIndexNamespace
						);
				}
			}
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ImageOpenShowImageInlineBefore
	 *
	 * @param ImagePage $imgpage
	 * @param OutputPage $out
	 */
	public function onImageOpenShowImageInlineBefore(
		$imgpage, $out
	) {
		$image = $imgpage->getPage()->getFile();
		if ( !$image->isMultipage() ) {
			return;
		}

		$name = $image->getTitle()->getText();
		$title = Title::makeTitle( self::getIndexNamespaceId(), $name );
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$link = $linkRenderer->makeKnownLink(
			$title, $out->msg( 'proofreadpage_image_message' )->text()
		);
		$out->addHTML( $link );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
	 *
	 * @param OutputPage $outputPage
	 * @param ParserOutput $parserOutput
	 */
	public function onOutputPageParserOutput(
		$outputPage, $parserOutput
	): void {
		$title = $outputPage->getTitle();
		$bookNamespaces = $outputPage->getConfig()->get( 'ProofreadPageBookNamespaces' );

		$outputPage->addJsConfigVars( 'prpProofreadPageBookNamespaces', $bookNamespaces );

		if ( $title->inNamespaces( $bookNamespaces ) && !$title->isMainPage() ) {
			$context = Context::getDefaultContext();
			$modifier = new TranslusionPagesModifier(
				$context->getPageQualityLevelLookup(),
				$context->getIndexQualityStatsLookup(),
				$context->getIndexForPageLookup(),
				$context->getPageNamespaceId()
			);
			$modifier->modifyPage( $parserOutput, $outputPage );
		}
	}

	/**
	 * Provides text for preload API
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/EditFormPreloadText
	 *
	 * @param string &$text
	 * @param Title $title
	 */
	public function onEditFormPreloadText( &$text, $title ) {
		if ( !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return;
		}

		$pageContentBuilder = new PageContentBuilder(
			RequestContext::getMain(), Context::getDefaultContext()
		);
		$content = $pageContentBuilder->buildDefaultContentForPageTitle( $title );
		$text = $content->serialize();
	}

	/**
	 * Add ProofreadPage preferences to the preferences menu
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetPreferences
	 *
	 * @param User $user
	 * @param array[] &$preferences
	 */
	public function onGetPreferences( $user, &$preferences ) {
		$type = 'toggle';
		// Hide the option from the preferences tab if WikiEditor is loaded
		if ( ExtensionRegistry::getInstance()->isLoaded( 'WikiEditor' ) &&
			MediaWikiServices::getInstance()->getUserOptionsLookup()
				->getBoolOption( $user, 'usebetatoolbar' )
		) {
			$type = 'api';
		}
		// Show header and footer fields when editing in the Page namespace
		$preferences['proofreadpage-showheaders'] = [
			'type'           => $type,
			'label-message'  => 'proofreadpage-preferences-showheaders-label',
			'section'        => 'editing/proofread-pagenamespace',
		];

		// Use horizontal layout when editing in the Page namespace
		$preferences['proofreadpage-horizontal-layout'] = [
			'type'           => $type,
			'label-message'  => 'proofreadpage-preferences-horizontal-layout-label',
			'section'        => 'editing/proofread-pagenamespace',
		];

		if ( $this->config->get( 'ProofreadPageEnableEditInSequence' ) ) {
			// Show dialog before saving the a Page using EditInSequence
			$preferences['proofreadpage-show-dialog-before-every-save'] = [
				'type'           => 'api',
				'label-message'  => 'proofreadpage-preferences-show-dialog-before-every-save-label',
				'section'        => 'editing/proofread-pagenamespace',
			];

			// Preference denoting action to be performed
			// after saving a page in EditInSequence,
			$preferences['proofreadpage-after-save-action'] = [
				'type'           => 'api',
				'options-messages'        => [
					'prp-editinsequence-save-next-action-do-nothing' => 'do-nothing',
					'prp-editinsequence-save-next-action-go-to-next' => 'go-to-next',
					'prp-editinsequence-save-next-action-go-to-prev' => 'go-to-prev',
				],
				'label-message'  => 'proofreadpage-preferences-after-save-action-label',
				'section'        => 'editing/proofread-pagenamespace',
			];
		}

		// Page image viewer zoom factor
		$preferences['proofreadpage-zoom-factor'] = [
			'type'           => 'float',
			'min'            => 1.0,
			'max'            => 2.0,
			'label-message'  => 'proofreadpage-preferences-zoom-factor-label',
			'section'        => 'editing/proofread-pagenamespace',
		];

		// Page image viewer animation time (higher is smoother)
		$preferences['proofreadpage-animation-time'] = [
			'type'           => 'float',
			'min'            => 0,
			'max'            => 2.0,
			'label-message'  => 'proofreadpage-preferences-animation-time-label',
			'section'        => 'editing/proofread-pagenamespace',
		];

		// Mode selection for the new PagelistInputWidget
		$preferences['proofreadpage-pagelist-use-visual-mode'] = [
			'type'           => 'toggle',
			'label-message'  => 'proofreadpage-preferences-pagelist-use-visual-mode',
			'section'        => 'editing/advancedediting',
		];
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/CanonicalNamespaces
	 *
	 * @param string[] &$namespaces
	 */
	public function onCanonicalNamespaces( &$namespaces ) {
		$pageNamespaceId = self::getPageNamespaceId();
		$indexNamespaceId = self::getIndexNamespaceId();

		$namespaces[$pageNamespaceId] = 'Page';
		$namespaces[$pageNamespaceId + 1] = 'Page_talk';
		$namespaces[$indexNamespaceId] = 'Index';
		$namespaces[$indexNamespaceId + 1] = 'Index_talk';
	}

	/**
	 * Add the links to previous, next, index page and scan image to Page: pages.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 *
	 * @param Title $title the page title
	 * @param SkinTemplate $skin
	 * @param array[] &$links Structured navigation links
	 */
	private static function addPageNsNavigation( Title $title, SkinTemplate $skin, array &$links ) {
		$context = Context::getDefaultContext();
		$pageDisplayHandler = new PageDisplayHandler( $context );

		// Image link
		$image = $pageDisplayHandler->getImageFullSize( $title );
		if ( $image ) {
			$links['namespaces']['proofreadPageScanLink'] = [
				'class' => '',
				'href' => $image->getUrl(),
				'text' => wfMessage( 'proofreadpage_image' )->plain()
			];
		}

		$indexTitle = $context
			->getIndexForPageLookup()->getIndexForPageTitle( $title );

		if ( EditInSequence::isEnabled( $skin ) ) {
			$isLoaded = EditInSequence::shouldLoadEditInSequence( $skin );
			$links['views']['proofreadPageEditInSequenceLink'] = [
				'class' => $isLoaded ? 'selected' : '',
				'href' => $title->getLocalURL( [
						'action' => 'edit',
						EditInSequence::URLPARAMNAME => 'true'
					] ),
				'text' => wfMessage( 'proofreadpage_edit_in_sequence' )->plain()
			];

			if ( $isLoaded ) {
				// Deselect the Edit link when EditInSequence is loaded
				$links['views']['edit']['class'] = '';
				self::addIndexLink( $skin, $indexTitle, $links );
				return;
			}
		}

		// Prev, Next and Index links
		if ( $indexTitle !== null ) {
			$pagination = $context
				->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );

			$firstLinks = [];
			try {
				$pageNumber = $pagination->getPageNumber( $title );

				try {
					$prevTitle  = $pagination->getPageTitle( $pageNumber - 1 );
					$prevText = wfMessage( 'proofreadpage_prevpage' )->plain();
					$prevUrl = self::getLinkUrlForTitle( $prevTitle );
					$firstLinks['proofreadPagePrevLink'] = [
						'class' => in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ], true ) ? 'icon' : '',
						'href' => $prevUrl,
						'text' => $prevText,
						'title' => $prevText,
						'aria-label' => wfMessage( 'proofreadpage_prevpage_label' )->plain(),
					];
					$prevThumbnailLinkAttributes = $pageDisplayHandler->getImageHtmlLinkAttributes(
						$prevTitle, 'prefetch', 'prp-prev-image'
					);
					if ( $prevThumbnailLinkAttributes ) {
						$skin->getOutput()->addLink( $prevThumbnailLinkAttributes );
					}
				} catch ( OutOfBoundsException $e ) {
					// if the previous page does not exist
				}

				try {
					$nextTitle  = $pagination->getPageTitle( $pageNumber + 1 );
					$nextText = wfMessage( 'proofreadpage_nextpage' )->plain();
					$nextUrl = self::getLinkUrlForTitle( $nextTitle );
					$firstLinks['proofreadPageNextLink'] = [
						'class' => in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ], true ) ? 'icon' : '',
						'href' => $nextUrl,
						'text' => $nextText,
						'title' => $nextText,
						'aria-label' => wfMessage( 'proofreadpage_nextpage_label' )->plain(),
					];
					$nextThumbnailLinkAttributes = $pageDisplayHandler->getImageHtmlLinkAttributes(
						$nextTitle, 'prefetch', 'prp-next-image'
					);
					if ( $nextThumbnailLinkAttributes ) {
						$skin->getOutput()->addLink( $nextThumbnailLinkAttributes );
					}
				} catch ( OutOfBoundsException $e ) {
					// if the next page does not exist
				}
			} catch ( PageNotInPaginationException $e ) {
			}

			// Prepend Prev, Next to namespaces tabs
			$links['namespaces'] = array_merge( $firstLinks, $links['namespaces'] );

			self::addIndexLink( $skin, $indexTitle, $links );
		}
	}

	/**
	 * Add the link to the index page from Page: pages.
	 * @param SkinTemplate $skin
	 * @param ?Title $indexTitle
	 * @param array[] &$links Structured navigation links
	 */
	private static function addIndexLink( SkinTemplate $skin, ?Title $indexTitle, array &$links ) {
		if ( $indexTitle === null ) {
			return;
		}

		$indexLabel = wfMessage( 'proofreadpage_index' )->plain();
		$links['namespaces']['proofreadPageIndexLink'] = [
			'class' => ( in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ], true ) ) ? 'icon' : '',
			'href' => $indexTitle->getLinkURL(),
			'text' => $indexLabel,
			'title' => $indexLabel,
			'aria-label' => wfMessage( 'proofreadpage_index_label' )->plain(),
		];
	}

	/**
	 * Add the link the style page (if any) on the Index: pages
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 *
	 * @param Title $title the page title
	 * @param SkinTemplate $skin
	 * @param array[] &$links Structured navigation links
	 */
	private static function addIndexNsNavigation( Title $title, SkinTemplate $skin, array &$links ) {
		$indexTs = new IndexTemplateStyles( $title );

		$stylesTitle = $indexTs->getTemplateStylesPage();

		if ( $stylesTitle !== null ) {
			// set up styles navigation

			$stylesSelected = $title->equals( $stylesTitle );

			// link to the styles page
			$links['namespaces']['proofreadPageStylesLink'] = $skin->tabAction(
				$stylesTitle, 'proofreadpage_styles', $stylesSelected, '', true );

			if ( $stylesSelected ) {
				// redirect the Index and Talk links to the root page
				$rootIndex = $indexTs->getAssociatedIndexPage();
				$links['namespaces']['index'] = $skin->tabAction(
					$rootIndex, 'index', false, '', true
				);
				$links['namespaces']['index_talk'] = $skin->tabAction(
					$rootIndex->getTalkPage(),
					'talk', false, '', true
				);
			}
		}
	}

	/**
	 * Add the link the style page (if any) on the main namespace pages
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation
	 *
	 * @param Title $title the page title
	 * @param SkinTemplate $skin
	 * @param array[] &$links Structured navigation links
	 */
	private static function addBookSourceNavigation( Title $title, SkinTemplate $skin, array &$links ) {
		$outputPage = $skin->getOutput();
		$indexTitleText = $outputPage->getProperty( 'prpSourceIndexPage' );
		if ( $indexTitleText !== null ) {
			$links['namespaces'] = array_slice( $links['namespaces'], 0, 1, true ) +
				[
					'proofread-source' => [
						'title' => $outputPage->msg( 'proofreadpage_source_message' )->text(),
						'text' => $outputPage->msg( 'proofreadpage_source' )->text(),
						'href' => Title::newFromText( $indexTitleText )->getLocalUrl(),
					]
				] +
				array_slice( $links['namespaces'], 1, count( $links['namespaces'] ), true );
		}
	}

	// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

	/**
	 * @inheritDoc
	 */
	public function onSkinTemplateNavigation__Universal( $skin, &$links ): void {
		// phpcs:enable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
		$title = $skin->getTitle();
		if ( $title === null ) {
			return;
		}

		if ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::addPageNsNavigation( $title, $skin, $links );
		} elseif ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			self::addIndexNsNavigation( $title, $skin, $links );
		} elseif ( $title->inNamespaces( $skin->getConfig()->get( 'ProofreadPageBookNamespaces' ) ) ) {
			self::addBookSourceNavigation( $title, $skin, $links );
		}
	}

	/**
	 * Add proofreading status to action=info
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/InfoAction
	 *
	 * @param IContextSource $context
	 * @param array[] &$pageInfo The page information
	 */
	public function onInfoAction( $context, &$pageInfo ) {
		$title = $context->getTitle();
		if ( !$title || !$title->canExist() ) {
			return;
		}
		if ( !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return;
		}

		$pageQualityLevelLookup = Context::getDefaultContext()->getPageQualityLevelLookup();
		$pageQualityLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $title );
		if ( $pageQualityLevel !== null ) {
			$pageInfo['header-basic'][] = [
				wfMessage( 'proofreadpage-pageinfo-status' ),
				wfMessage( "proofreadpage_quality{$pageQualityLevel}_category" ),
			];
		}
	}

	/**
	 * Get URL for particular title
	 * @param Title $title
	 * @return string
	 */
	protected static function getLinkUrlForTitle( Title $title ) {
		if ( $title->exists() ) {
			return $title->getLinkURL();
		} else {
			return $title->getLinkURL( 'action=edit&redlink=1' );
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Extension_registration#Customizing_registration
	 */
	public static function onRegistration() {
		// L10n
		include_once __DIR__ . '/../ProofreadPage.namespaces.php';

		// Content handler
		define( 'CONTENT_MODEL_PROOFREAD_PAGE', 'proofread-page' );
		define( 'CONTENT_MODEL_PROOFREAD_INDEX', 'proofread-index' );
	}

	/**
	 * ListDefinedTags hook handler
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ListDefinedTags
	 *
	 * @param array &$tags The list of tags. Add your extension's tags to this array.
	 */
	public function onListDefinedTags( &$tags ) {
		$this->addDefinedTags( $tags );
	}

	/**
	 * ChangeTagsListActive hook handler
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ChangeTagsListActive
	 *
	 * @param array &$tags The list of tags. Add your extension's tags to this array.
	 */
	public function onChangeTagsListActive( &$tags ) {
		$this->addDefinedTags( $tags );
	}

	/**
	 * @param array &$tags
	 */
	private function addDefinedTags( &$tags ) {
		$tags[] = Tags::WITHOUT_TEXT_TAG;
		$tags[] = Tags::NOT_PROOFREAD_TAG;
		$tags[] = Tags::PROBLEMATIC_TAG;
		$tags[] = Tags::PROOFREAD_TAG;
		$tags[] = Tags::VALIDATED_TAG;
		// Add a tag for edits made using EditInSequence
		$tags[] = EditInSequence::TAGNAME;
	}

	// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

	/**
	 * @inheritDoc
	 */
	public function onRecentChange_save( $rc ) {
		// phpcs:enable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

		$ns = $rc->getAttribute( 'rc_namespace' );

		if ( $ns === self::getPageNamespaceId() ) {

			$requestContext = RequestContext::getMain();

			if ( EditInSequence::isEditInSequenceEdit( $requestContext ) ) {
				$rc->addTags( [ EditInSequence::TAGNAME ] );
			}

			$useProofreadTags = $this->config->get( 'ProofreadPageUseStatusChangeTags' );

			// not configured to add tags to revisions
			if ( !$useProofreadTags ) {
				return;
			}

			$tagger = new PageRevisionTagger();
			$tags = $tagger->getTagsForChange( $rc );

			if ( $tags ) {
				$rc->addTags( $tags );
			}
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/MultiContentSave
	 *
	 * @param RenderedRevision $renderedRevision
	 * @param UserIdentity $user
	 * @param CommentStoreComment $summary
	 * @param int $flags
	 * @param Status $hookStatus
	 * @return bool|void
	 */
	public function onMultiContentSave(
		$renderedRevision,
		$user,
		$summary,
		$flags,
		$hookStatus
	) {
		$revisionRecord = $renderedRevision->getRevision();
		$content = $revisionRecord->getContent( SlotRecord::MAIN );
		if ( !( $content instanceof PageContent ) ) {
			// We just need to prepare this check for CONTENT_MODEL_PROOFREAD_PAGE (PageContent)
			return;
		}

		if ( !( $content->isValid() ) ) {
			$hookStatus->fatal( 'invalid-content-data' );
			return;
		}

		$oldContent = PageContent::getContentForRevId( $revisionRecord->getParentId() );
		if ( $oldContent->getModel() !== CONTENT_MODEL_PROOFREAD_PAGE ) {
			// Let's convert it to Page: page content
			$oldContent = $oldContent->convert( CONTENT_MODEL_PROOFREAD_PAGE );
		}

		if ( !( $oldContent instanceof PageContent ) ) {
			$hookStatus->fatal( 'invalid-content-data' );
			return;
		}

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( !$oldContent->getLevel()->isChangeAllowed( $content->getLevel(), $permissionManager ) ) {
			$hookStatus->fatal( 'proofreadpage_notallowedtext' );
			return;
		}
	}

	/**
	 * @param Title $pageTitle
	 * @param bool $inIndexNamespace
	 * @return string
	 */
	public static function getQualityLevelClassesForTitle( Title $pageTitle, bool $inIndexNamespace ): string {
		$dbLookup = new DatabasePageQualityLevelLookup( $pageTitle->getNamespace() );
		$pageLevel = $dbLookup->getQualityLevelForPageTitle( $pageTitle );

		return self::getQualityClassesForQualityLevel( $pageLevel, $inIndexNamespace );
	}

	/**
	 * @param int|null $pageLevel
	 * @param bool $inIndexNamespace
	 * @return string
	 */
	public static function getQualityClassesForQualityLevel( ?int $pageLevel, bool $inIndexNamespace ): string {
		$classes = "";
		if ( $pageLevel !== null ) {
			$classes = "prp-pagequality-{$pageLevel}";
			if ( $inIndexNamespace ) {
				$classes .= " quality{$pageLevel}";
			}
		}
		return $classes;
	}

	/**
	 * @param User $user
	 * @param array &$betaPrefs
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetBetaFeaturePreferences
	 */
	public static function onGetBetaFeaturePreferences( User $user, array &$betaPrefs ) {
		$extensionAssetsPath = MediaWikiServices::getInstance()
			->getMainConfig()
			->get( 'ExtensionAssetsPath' );
		$betaPrefs[ EditInSequence::BETA_FEATURE_NAME ] = [
			// The first two are message keys
			'label-message' => 'prp-editinsequence-beta-label',
			'desc-message' => 'prp-editinsequence-beta-description',
			'screenshot' => [
				'ltr' => "$extensionAssetsPath/ProofreadPage/modules/page/images/eis-ltr.svg",
				'rtl' => "$extensionAssetsPath/ProofreadPage/modules/page/images/eis-rtl.svg",
			],
			'info-link' => 'https://meta.wikimedia.org/wiki/Special:MyLanguage/Wikisource_EditInSequence',
			'discussion-link' =>
			'https://meta.wikimedia.org/wiki/Special:MyLanguage/Talk:Wikisource_EditInSequence',
		];
	}

	/**
	 * @param array &$ids
	 */
	public function onGetDoubleUnderscoreIDs( &$ids ) {
		$ids[] = 'expectwithoutscans';
	}
}
