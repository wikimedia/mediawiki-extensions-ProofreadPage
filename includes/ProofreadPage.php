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

use DatabaseUpdater;
use ExtensionRegistry;
use FixProofreadIndexPagesContentModel;
use FixProofreadPagePagesContentModel;
use IContextSource;
use ImagePage;
use MediaWiki\MediaWikiServices;
use OutOfBoundsException;
use OutputPage;
use Parser;
use ParserOutput;
use ProofreadPage\Index\IndexTemplateStyles;
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
use Title;
use User;

/*
 @todo :
 - check uniqueness of the index page : when index is saved too
*/

class ProofreadPage {

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
	public static function onParserFirstCallInit( Parser $parser ) {
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
	 * @param string[] $pageIds Prefixed DB keys of the pages linked to, indexed by page_id
	 * @param string[] &$colours CSS classes, indexed by prefixed DB keys
	 * @param Title $title Title of the page being parsed, on which the links will be shown
	 */
	public static function onGetLinkColours( array $pageIds, array &$colours, Title $title ) {
		$context = Context::getDefaultContext();
		$inIndexNamespace = $title->inNamespace( $context->getIndexNamespaceId() );
		$pageQualityLevelLookup = $context->getPageQualityLevelLookup();

		$pageTitles = array_map( static function ( $prefixedDbKey ) {
			return Title::newFromText( $prefixedDbKey );
		}, $pageIds );
		$pageQualityLevelLookup->prefetchQualityLevelForTitles( $pageTitles );

		/** @var Title|null $pageTitle */
		foreach ( $pageTitles as $pageTitle ) {
			if ( $pageTitle !== null && $pageTitle->inNamespace( $context->getPageNamespaceId() ) ) {
				$pageLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $pageTitle );
				if ( $pageLevel !== null ) {
					$classes = "prp-pagequality-{$pageLevel}";
					if ( $inIndexNamespace ) {
						$classes .= " quality{$pageLevel}";
					}
					$colours[$pageTitle->getPrefixedDBkey()] = $classes;
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
	public static function onOutputPageParserOutput(
		OutputPage $outputPage, ParserOutput $parserOutput
	) {
		if ( $outputPage->getTitle()->inNamespace( NS_MAIN ) ) {
			$context = Context::getDefaultContext();
			$modifier = new TranslusionPagesModifier(
				$context->getPageQualityLevelLookup(),
				$context->getIndexQualityStatsLookup(),
				$context->getIndexForPageLookup(),
				MediaWikiServices::getInstance()->getLinkRenderer(),
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
			'section'        => 'editing/advancedediting',
		];

		// Use horizontal layout when editing in the Page namespace
		$preferences['proofreadpage-horizontal-layout'] = [
			'type'           => $type,
			'label-message'  => 'proofreadpage-preferences-horizontal-layout-label',
			'section'        => 'editing/advancedediting',
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
		$pageDisplayHandler = new PageDisplayHandler( Context::getDefaultContext() );

		// Image link
		$image = $pageDisplayHandler->getImageFullSize( $title );
		if ( $image ) {
			$links['namespaces']['proofreadPageScanLink'] = [
				'class' => '',
				'href' => $image->getUrl(),
				'text' => wfMessage( 'proofreadpage_image' )->plain()
			];
		}

		// Prev, Next and Index links
		$indexTitle = Context::getDefaultContext()
			->getIndexForPageLookup()->getIndexForPageTitle( $title );
		if ( $indexTitle !== null ) {
			$pagination = Context::getDefaultContext()
				->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );

			$firstLinks = [];
			try {
				$pageNumber = $pagination->getPageNumber( $title );

				try {
					$prevTitle  = $pagination->getPageTitle( $pageNumber - 1 );
					$prevText = wfMessage( 'proofreadpage_prevpage' )->plain();
					$prevUrl = self::getLinkUrlForTitle( $prevTitle );
					$firstLinks['proofreadPagePrevLink'] = [
						'class' => ( $skin->getSkinName() === 'vector' ) ? 'icon' : '',
						'href' => $prevUrl,
						'text' => $prevText,
						'title' => $prevText
					];
					$skin->getOutput()->addLink( [
						'rel' => 'prev',
						'href' => $prevUrl
					] );
					$skin->getOutput()->addLink( [
						'rel' => 'prefetch',
						'href' => $prevUrl
					] );
					$prevThumbnail = $pageDisplayHandler->getImageThumbnail( $prevTitle );
					if ( $prevThumbnail ) {
						$skin->getOutput()->addLink( [
							'rel' => 'prefetch',
							'as' => 'image',
							'href' => $prevThumbnail->getUrl(),
							'title' => 'prp-prev-image',
						] );
					}
				}
				// if the previous page does not exist
				catch ( OutOfBoundsException $e ) {
				}

				try {
					$nextTitle  = $pagination->getPageTitle( $pageNumber + 1 );
					$nextText = wfMessage( 'proofreadpage_nextpage' )->plain();
					$nextUrl = self::getLinkUrlForTitle( $nextTitle );
					$firstLinks['proofreadPageNextLink'] = [
						'class' => ( $skin->getSkinName() === 'vector' ) ? 'icon' : '',
						'href' => $nextUrl,
						'text' => $nextText,
						'title' => $nextText
					];
					$skin->getOutput()->addLink( [
						'rel' => 'next',
						'href' => $nextUrl
					] );
					$skin->getOutput()->addLink( [
						'rel' => 'prefetch',
						'href' => $nextUrl
					] );
					$nextThumbnail = $pageDisplayHandler->getImageThumbnail( $nextTitle );
					if ( $nextThumbnail ) {
						$skin->getOutput()->addLink( [
							'rel' => 'prefetch',
							'as' => 'image',
							'href' => $nextThumbnail->getUrl(),
							'title' => 'prp-next-image',
						] );
					}
				}
				// if the next page does not exist
				catch ( OutOfBoundsException $e ) {
				}
			} catch ( PageNotInPaginationException $e ) {
			}

			// Prepend Prev, Next to namespaces tabs
			$links['namespaces'] = array_merge( $firstLinks, $links['namespaces'] );

			$links['namespaces']['proofreadPageIndexLink'] = [
				'class' => ( $skin->getSkinName() === 'vector' ) ? 'icon' : '',
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
	 * Add links in the navigation menus related the current page
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/SkinTemplateNavigation::Universal
	 *
	 * @param SkinTemplate $skin
	 * @param array[] &$links Structured navigation links
	 */
	public static function onSkinTemplateNavigationUniversal( SkinTemplate $skin, array &$links ) {
		$title = $skin->getTitle();
		if ( $title === null ) {
			return;
		}

		if ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::addPageNsNavigation( $title, $skin, $links );
		} elseif ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			self::addIndexNsNavigation( $title, $skin, $links );
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

	/**
	 * RecentChange_save hook handler that tags changes according to their content
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/RecentChange_save
	 *
	 * @param \RecentChange $rc
	 */
	public static function onRecentChangeSave( \RecentChange $rc ) {
		$useTags = Context::getDefaultContext()->getConfig()->get(
			'ProofreadPageUseStatusChangeTags' );

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
}
