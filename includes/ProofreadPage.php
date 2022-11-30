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

use CommentStoreComment;
use Config;
use DatabaseUpdater;
use ExtensionRegistry;
use FixProofreadIndexPagesContentModel;
use FixProofreadPagePagesContentModel;
use IContextSource;
use ImagePage;
use MediaWiki\Hook\GetLinkColoursHook;
use MediaWiki\Hook\OutputPageParserOutputHook;
use MediaWiki\Hook\ParserFirstCallInitHook;
use MediaWiki\Hook\RecentChange_saveHook;
use MediaWiki\Hook\SkinTemplateNavigation__UniversalHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use OutOfBoundsException;
use OutputPage;
use Parser;
use ParserOutput;
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
use RequestContext;
use SkinTemplate;
use Status;
use Title;
use User;

/*
 @todo :
 - check uniqueness of the index page : when index is saved too
*/

class ProofreadPage implements
	RecentChange_saveHook,
	SkinTemplateNavigation__UniversalHook,
	OutputPageParserOutputHook,
	ParserFirstCallInitHook,
	GetLinkColoursHook
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
	public static function onwgQueryPages( array &$queryPages ) {
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
	public static function onContentHandlerDefaultModelFor( Title $title, &$model ) {
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
	 */
	public static function onBeforePageDisplay( OutputPage $out ) {
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
	public static function onImageOpenShowImageInlineBefore(
		ImagePage $imgpage, OutputPage $out
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
	public static function onEditFormPreloadText( &$text, Title $title ) {
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
	public static function onGetPreferences( $user, array &$preferences ) {
		$type = 'toggle';
		// Hide the option from the preferences tab if WikiEditor is loaded
		if ( ExtensionRegistry::getInstance()->isLoaded( 'WikiEditor' ) &&
			MediaWikiServices::getInstance()->getUserOptionsLookup()
				->getBoolOption( $user, 'usebetatoolbar' )
		) {
			$type = 'hidden';
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
	public static function onCanonicalNamespaces( array &$namespaces ) {
		$pageNamespaceId = self::getPageNamespaceId();
		$indexNamespaceId = self::getIndexNamespaceId();

		$namespaces[$pageNamespaceId] = 'Page';
		$namespaces[$pageNamespaceId + 1] = 'Page_talk';
		$namespaces[$indexNamespaceId] = 'Index';
		$namespaces[$indexNamespaceId + 1] = 'Index_talk';
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LoadExtensionSchemaUpdates
	 *
	 * @param DatabaseUpdater $updater
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$dbType = $updater->getDB()->getType();

		if ( $dbType === 'mysql' ) {
			$updater->addExtensionTable( 'pr_index',
				dirname( __DIR__ ) . '/sql/tables-generated.sql'
			);
		} elseif ( $dbType === 'sqlite' ) {
			$updater->addExtensionTable( 'pr_index',
				dirname( __DIR__ ) . '/sql/sqlite/tables-generated.sql'
			);
		} elseif ( $dbType === 'postgres' ) {
			$updater->addExtensionTable( 'pr_index',
				dirname( __DIR__ ) . '/sql/postgres/tables-generated.sql'
			);
		}

		// fix issue with content type hardcoded in database
		$updater->addPostDatabaseUpdateMaintenance( FixProofreadPagePagesContentModel::class );
		$updater->addPostDatabaseUpdateMaintenance( FixProofreadIndexPagesContentModel::class );
	}

	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserTestTables
	 *
	 * @param string[] &$tables
	 */
	public static function onParserTestTables( array &$tables ) {
		$tables[] = 'pr_index';
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

		if ( EditInSequence::isEnabled( $skin ) ) {
			$links['views']['proofreadPageEditInSequenceLink'] = [
				'class' => '',
				'href' => $title->getLocalURL( [
						'action' => 'edit',
						EditInSequence::URLPARAMNAME => 'true'
					] ),
				'text' => wfMessage( 'proofreadpage_edit_in_sequence' )->plain()
			];
		}

		// Prev, Next and Index links
		$indexTitle = $context
			->getIndexForPageLookup()->getIndexForPageTitle( $title );
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
						'class' => ( in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ] ) ) ? 'icon' : '',
						'href' => $prevUrl,
						'text' => $prevText,
						'title' => $prevText
					];
					$prevThumbnail = $pageDisplayHandler->getImageThumbnail( $prevTitle );
					if ( $prevThumbnail ) {
						$skin->getOutput()->addLink( [
							'rel' => 'prefetch',
							'as' => 'image',
							'href' => $prevThumbnail->getUrl(),
							'title' => 'prp-prev-image',
						] );
					}
				} catch ( OutOfBoundsException $e ) {
					// if the previous page does not exist
				}

				try {
					$nextTitle  = $pagination->getPageTitle( $pageNumber + 1 );
					$nextText = wfMessage( 'proofreadpage_nextpage' )->plain();
					$nextUrl = self::getLinkUrlForTitle( $nextTitle );
					$firstLinks['proofreadPageNextLink'] = [
						'class' => ( in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ] ) ) ? 'icon' : '',
						'href' => $nextUrl,
						'text' => $nextText,
						'title' => $nextText
					];
					$nextThumbnail = $pageDisplayHandler->getImageThumbnail( $nextTitle );
					if ( $nextThumbnail ) {
						$skin->getOutput()->addLink( [
							'rel' => 'prefetch',
							'as' => 'image',
							'href' => $nextThumbnail->getUrl(),
							'title' => 'prp-next-image',
						] );
					}
				} catch ( OutOfBoundsException $e ) {
					// if the next page does not exist
				}
			} catch ( PageNotInPaginationException $e ) {
			}

			// Prepend Prev, Next to namespaces tabs
			$links['namespaces'] = array_merge( $firstLinks, $links['namespaces'] );

			$links['namespaces']['proofreadPageIndexLink'] = [
				'class' => ( in_array( $skin->getSkinName(), [ 'vector', 'vector-2022' ] ) ) ? 'icon' : '',
				'href' => $indexTitle->getLinkURL(),
				'text' => wfMessage( 'proofreadpage_index' )->plain(),
				'title' => wfMessage( 'proofreadpage_index' )->plain()
			];
		}
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
				] ] +
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
	public static function onInfoAction( IContextSource $context, array &$pageInfo ) {
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
	 * ListDefinedTags and ChangeTagsListActive hook handler
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ListDefinedTags
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ChangeTagsListActive
	 *
	 * @param array &$tags The list of tags. Add your extension's tags to this array.
	 * @return bool
	 */
	public static function onListDefinedTags( &$tags ) {
		$tags[] = Tags::WITHOUT_TEXT_TAG;
		$tags[] = Tags::NOT_PROOFREAD_TAG;
		$tags[] = Tags::PROBLEMATIC_TAG;
		$tags[] = Tags::PROOFREAD_TAG;
		$tags[] = Tags::VALIDATED_TAG;
		return true;
	}

	// phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName

	/**
	 * @inheritDoc
	 */
	public function onRecentChange_save( $rc ) {
		// phpcs:enable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
		$useTags = $this->config->get( 'ProofreadPageUseStatusChangeTags' );

		// not configured to add tags to revisions
		if ( !$useTags ) {
			return;
		}

		$ns = $rc->getAttribute( 'rc_namespace' );

		if ( $ns === self::getPageNamespaceId() ) {
			$tagger = new PageRevisionTagger();
			$tags = $tagger->getTagsForChange( $rc );

			if ( $tags ) {
				$rc->addTags( $tags );
			}
		}
	}

	/**
	 * Register our extra Lua libraries
	 * @param string $engine
	 * @param array &$extraLibraries the array to add to
	 */
	public static function onScribuntoExternalLibraries( string $engine, array &$extraLibraries ) {
		$extraLibraries['mw.ext.proofreadPage'] = ProofreadPageLuaLibrary::class;
	}

	/**
	 * Register the Scribunto external libraries for ProofreadPage
	 * @param string $engine the Lua engine
	 * @param array &$extraLibraryPaths An array of strings representing the
	 *                                   filesystem paths to library files.
	 *                                   Will be merged with core library paths.
	 * @return bool
	 */
	public static function onScribuntoExternalLibraryPaths( $engine, array &$extraLibraryPaths ) {
		if ( $engine === 'lua' ) {
			// Path containing pure Lua libraries that don't need to interact with PHP
			$extraLibraryPaths[] = __DIR__ . '/lualib';
		}

		return true;
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
	public static function onMultiContentSave(
		RenderedRevision $renderedRevision,
		UserIdentity $user,
		CommentStoreComment $summary,
		$flags,
		Status $hookStatus
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

}
