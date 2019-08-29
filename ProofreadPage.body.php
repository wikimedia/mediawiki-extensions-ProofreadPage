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

use MediaWiki\MediaWikiServices;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Index\ProofreadIndexDbConnector;
use ProofreadPage\Page\PageContentBuilder;
use ProofreadPage\Page\ProofreadPageDbConnector;
use ProofreadPage\PageNumberNotFoundException;
use ProofreadPage\Pagination\PageNotInPaginationException;
use ProofreadPage\Parser\PagelistTagParser;
use ProofreadPage\Parser\PagequalityTagParser;
use ProofreadPage\Parser\PagesTagParser;
use ProofreadPage\ProofreadPageInit;

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
	 * @return array
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
	 * @param array &$queryPages
	 * @return bool
	 */
	public static function onwgQueryPages( &$queryPages ) {
		$queryPages[] = [ 'SpecialProofreadPages', 'IndexPages' ];
		$queryPages[] = [ 'SpecialPagesWithoutScans', 'PagesWithoutScans' ];
		return true;
	}

	/**
	 * Set up content handlers
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
		} elseif ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			$model = CONTENT_MODEL_PROOFREAD_INDEX;
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Set up our custom parser hooks when initializing parser.
	 *
	 * @param Parser $parser
	 * @return bool hook return value
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setHook( 'pagelist', function ( $input, array $args, Parser $parser ) {
			$context = Context::getDefaultContext( true );
			$tagParser = new PagelistTagParser( $parser, $context );
			return $tagParser->render( $input, $args );
		} );
		$parser->setHook( 'pages', function ( $input, array $args, Parser $parser ) {
			$context = Context::getDefaultContext( true );
			$tagParser = new PagesTagParser( $parser, $context );
			return $tagParser->render( $input, $args );
		} );
		$parser->setHook( 'pagequality', function ( $input, array $args, Parser $parser ) {
				$tagParser = new PagequalityTagParser();
				return $tagParser->render( $input, $args );
		} );
		return true;
	}

	/**
	 * Append javascript variables and code to the page.
	 * @param OutputPage $out
	 * @return bool
	 * @suppress PhanUndeclaredProperty $out->proofreadPageDone
	 */
	public static function onBeforePageDisplay( OutputPage $out ) {
		$action = $out->getRequest()->getVal( 'action' );
		$isEdit = ( $action === 'submit' || $action === 'edit' );
		$title = $out->getTitle();

		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			$out->addModuleStyles( 'ext.proofreadpage.base' );
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			$out->addModuleStyles( 'ext.proofreadpage.page.navigation' );
		} elseif (
			$title->inNamespace( NS_MAIN ) &&
			( $out->isArticle() || $isEdit ) &&
			!isset( $out->proofreadPageDone )
		) {
			$out->proofreadPageDone = true;
			self::prepareArticle( $out );
		}

		return true;
	}

	/**
	 * Hook function
	 * @param array $pageIds Prefixed DB keys of the pages linked to, indexed by page_id
	 * @param array &$colours CSS classes, indexed by prefixed DB keys
	 * @param Title $title Title of the page being parsed, on which the links will be shown
	 * @return bool
	 */
	public static function onGetLinkColours( $pageIds, &$colours, Title $title ) {
		$inIndexNamespace = $title->inNamespace( self::getIndexNamespaceId() );
		$pageQualityLevelLookup = Context::getDefaultContext()->getPageQualityLevelLookup();

		$pageTitles = array_map( function ( $prefixedDbKey ) {
			return Title::newFromText( $prefixedDbKey );
		}, $pageIds );
		$pageQualityLevelLookup->prefetchQualityLevelForTitles( $pageTitles );

		/** @var Title|null $pageTitle */
		foreach ( $pageTitles as $pageTitle ) {
			if ( $pageTitle !== null && $pageTitle->inNamespace( self::getPageNamespaceId() ) ) {
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

		return true;
	}

	/**
	 * @param ImagePage &$imgpage
	 * @param OutputPage &$out
	 * @return bool
	 */
	public static function onImageOpenShowImageInlineBefore(
		ImagePage &$imgpage, OutputPage &$out
	) {
		$image = $imgpage->getFile();
		if ( !$image->isMultipage() ) {
			return true;
		}
		$name = $image->getTitle()->getText();
		$title = Title::makeTitle( self::getIndexNamespaceId(), $name );
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$link = $linkRenderer->makeKnownLink(
			$title, $out->msg( 'proofreadpage_image_message' )->text()
		);
		$out->addHTML( $link );
		return true;
	}

	/**
	 * Set is_toc flag (true if page is a table of contents)
	 * @param OutputPage $outputPage
	 * @param ParserOutput $parserOutput
	 * @return bool
	 * @suppress PhanUndeclaredProperty $out->is_toc
	 */
	public static function onOutputPageParserOutput(
		OutputPage $outputPage, ParserOutput $parserOutput
	) {
		if ( isset( $parserOutput->is_toc ) ) {
			$outputPage->is_toc = $parserOutput->is_toc;
		} else {
			$outputPage->is_toc = false;
		}
		return true;
	}

	/**
	 * Updates index data for an index referencing the specified page.
	 * @param Title $title page title object
	 * @param boolean $deleted indicates whether the page was deleted
	 */
	private static function updateIndexOfPage( Title $title, $deleted = false ) {
		$indexTitle = Context::getDefaultContext()
			->getIndexForPageLookup()->getIndexForPageTitle( $title );
		if ( $indexTitle !== null ) {
			$indexTitle->invalidateCache();
			$index = WikiPage::factory( $indexTitle );
			if ( $index ) {
				self::updatePrIndex( $index, $deleted ? $title : null );
			}
		}
	}

	/**
	 * @param WikiPage &$article
	 * @return bool
	 */
	public static function onPageContentSaveComplete( WikiPage &$article ) {
		$title = $article->getTitle();

		// if it's an index, update pr_index table
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			// Move this part to EditProofreadIndexPage
			self::updatePrIndex( $article );
			return true;
		}

		// return if it is not a page
		if ( !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return true;
		}

		/* check if there is an index */
		$indexTitle = Context::getDefaultContext()
			->getIndexForPageLookup()->getIndexForPageTitle( $title );
		if ( $indexTitle === null ) {
			return true;
		}

		/**
		 * invalidate the cache of the index page
		 */
		$indexTitle->invalidateCache();

		/**
		 * update pr_index iteratively
		 */
		$indexId = $indexTitle->getArticleID();
		$indexData = ProofreadIndexDbConnector::getIndexDataFromIndexPageId( $indexId );
		if ( $indexData ) {
			ProofreadIndexDbConnector::replaceIndexById( $indexData, $indexId, $article );
		}

		return true;
	}

	/**
	 * if I delete a page, I need to update the index table
	 * if I delete an index page too...
	 *
	 * @param WikiPage $article
	 * @return bool true
	 */
	public static function onArticleDelete( WikiPage $article ) {
		$title = $article->getTitle();

		// Process Index removal.
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			ProofreadIndexDbConnector::removeIndexData( $article->getId() );

		// Process Page removal.
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $title, true );
		}

		return true;
	}

	/**
	 * @param Title $title Title corresponding to the article restored
	 * @param bool $create If true, the restored page didn't exist before
	 * @param string $comment Comment explaining the undeletion
	 * @param int $oldPageId ID of page previously deleted from archive table
	 * @return bool
	 */
	public static function onArticleUndelete( Title $title, $create, $comment, $oldPageId ) {
		// Process Index restoration.
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			$index = new Article( $title );
			if ( $index ) {
				self::updatePrIndex( $index );
			}

		// Process Page restoration.
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $title );
		}

		return true;
	}

	/**
	 * @param MovePageForm &$form
	 * @param Title &$ot
	 * @param Title &$nt
	 * @return bool
	 */
	public static function onSpecialMovepageAfterMove(
		MovePageForm &$form, Title &$ot, Title &$nt
	) {
		if ( $ot->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $ot );
		} elseif ( $ot->inNamespace( self::getIndexNamespaceId() )
			&& !$nt->inNamespace( self::getIndexNamespaceId() )
		) {
			// The page is moved out of the Index namespace.
			// Remove all index data associated with that page.

			// $nt is used here on purpose, as we need to get the page id.
			// There is no page under the old title or it is a redirect.
			$wikipage = WikiPage::factory( $nt );
			if ( $wikipage ) {
				ProofreadIndexDbConnector::removeIndexData( $wikipage->getId() );
			}
		}

		if ( $nt->inNamespace( self::getPageNamespaceId() ) ) {
			$oldIndexTitle = Context::getDefaultContext()
				->getIndexForPageLookup()->getIndexForPageTitle( $ot );
			$newIndexTitle = Context::getDefaultContext()
				->getIndexForPageLookup()->getIndexForPageTitle( $nt );
			if ( $newIndexTitle !== null
				&& ( $oldIndexTitle === null ||
				( $newIndexTitle->equals( $oldIndexTitle ) ) )
			) {
				self::updateIndexOfPage( $nt );
			}
		} elseif ( $nt->inNamespace( self::getIndexNamespaceId() ) ) {
			// Update index data.
			$wikipage = WikiPage::factory( $nt );
			if ( $wikipage ) {
				self::updatePrIndex( $wikipage );
			}
		}
		return true;
	}

	/**
	 * When an index page is created or purged, recompute pr_index values
	 * @param WikiPage $article
	 * @return bool
	 */
	public static function onArticlePurge( WikiPage $article ) {
		$title = $article->getTitle();
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			self::updatePrIndex( $article );
			return true;
		}
		return true;
	}

	/**
	 * Update the pr_index entry of an article
	 * @param Page $index
	 * @param Title|null $deletedPage
	 * @suppress PhanUndeclaredMethod Page interface doesn't declare any method
	 */
	public static function updatePrIndex( Page $index, $deletedPage = null ) {
		$indexTitle = $index->getTitle();
		$indexId = $index->getId();

		// read the list of pages
		$pages = [];
		$pagination =
		Context::getDefaultContext()->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );
		foreach ( $pagination as $page ) {
			if ( $deletedPage === null || !$page->equals( $deletedPage ) ) {
				$pages[] = $page->getDBkey();
			}
		}

		if ( !count( $pages ) ) {
			return;
		}

		$total = ProofreadPageDbConnector::getNumberOfExistingPagesFromPageTitle( $pages );

		if ( $total === null ) {
			return;
		}

		// proofreading status of pages
		$queryArr = [
			'tables' => [ 'page', 'categorylinks' ],
			'fields' => [ 'COUNT(page_id) AS count' ],
			'conds' => [ 'cl_to' => '', 'page_namespace' => self::getPageNamespaceId(),
				'page_title' => $pages ],
			'joins' => [ 'categorylinks' => [ 'LEFT JOIN', 'cl_from=page_id' ] ]
		];

		$n0 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality0_category' );
		$n2 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality2_category' );
		$n3 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality3_category' );
		$n4 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality4_category' );
		$n1 = $total - $n0 - $n2 - $n3 - $n4;

		ProofreadIndexDbConnector::setIndexData( $pagination->getNumberOfPages(),
			$n0, $n1, $n2, $n3, $n4, $indexId );
	}

	/**
	 * In main namespace, display the proofreading status of transcluded pages.
	 *
	 * @param OutputPage $out
	 * @return bool
	 * @suppress SecurityCheck-SQLInjection See T201806 for more information
	 */
	private static function prepareArticle( OutputPage $out ) {
		$id = $out->getTitle()->getArticleID();
		if ( $id == -1 ) {
			return true;
		}
		$pageNamespaceId = self::getPageNamespaceId();
		$indexNamespaceId = self::getIndexNamespaceId();
		if ( $pageNamespaceId == null || $indexNamespaceId == null ) {
			return true;
		}

		// find the index page
		$indextitle = ProofreadPageDbConnector::getIndexTitleForPageId( $id );

		// @phan-suppress-next-line PhanUndeclaredProperty
		if ( isset( $out->is_toc ) && $out->is_toc ) {
			$n = 0;

			if ( $indextitle ) {
				$row = ProofreadIndexDbConnector::getIndexDataFromIndexTitle( $indextitle );
				if ( $row ) {
					$n0 = $row->pr_q0;
					$n1 = $row->pr_q1;
					$n2 = $row->pr_q2;
					$n3 = $row->pr_q3;
					$n4 = $row->pr_q4;
					$n = $row->pr_count;
					$ne = $n - ( $n0 + $n1 + $n2 + $n3 + $n4 );
				}
			}
		} else {
			// count transclusions from page namespace
			$n = ProofreadPageDbConnector::countTransclusionFromPageId( $id );
			if ( $n === null ) {
				return true;
			}

			// find the proofreading status of transclusions
			$queryArr = [
				'tables' => [ 'templatelinks', 'page', 'categorylinks' ],
				'fields' => [ 'COUNT(page_id) AS count' ],
				'conds' => [ 'tl_from' => $id, 'tl_namespace' => $pageNamespaceId, 'cl_to' => '' ],
				'joins' => [
					'page' => [ 'LEFT JOIN',
						'page_title=tl_title AND page_namespace=tl_namespace'
					],
					'categorylinks' => [ 'LEFT JOIN', 'cl_from=page_id' ],
				]
			];

			$n0 = ProofreadPageDbConnector::queryCount(
				$queryArr, 'proofreadpage_quality0_category'
			);
			$n2 = ProofreadPageDbConnector::queryCount(
				$queryArr, 'proofreadpage_quality2_category'
			);
			$n3 = ProofreadPageDbConnector::queryCount(
				$queryArr, 'proofreadpage_quality3_category'
			);
			$n4 = ProofreadPageDbConnector::queryCount(
				$queryArr, 'proofreadpage_quality4_category'
			);
			// quality1 is the default value
			$n1 = $n - $n0 - $n2 - $n3 - $n4;
			$ne = 0;
		}

		if ( $n == 0 ) {
			return true;
		}

		if ( $indextitle ) {
			$nt = Title::makeTitleSafe( $indexNamespaceId, $indextitle );
			if ( $nt !== null ) {
				$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
				$indexlink = $linkRenderer->makeLink( $nt, $out->msg( 'proofreadpage_source' )->text(),
					[ 'title' => $out->msg( 'proofreadpage_source_message' )->text() ] );
				$out->addJsConfigVars( 'proofreadpage_source_href', $indexlink );
				$out->addModules( 'ext.proofreadpage.article' );
			}
		}

		$q0 = $n0 * 100 / $n;
		$q1 = $n1 * 100 / $n;
		$q2 = $n2 * 100 / $n;
		$q3 = $n3 * 100 / $n;
		$q4 = $n4 * 100 / $n;
		$qe = $ne * 100 / $n;
		$void_cell = $ne ? '<td class="qualitye" style="width:' . $qe . '%;"></td>' : '';
		$textualAlternative = wfMessage( 'proofreadpage-indexquality-alt', $n4, $n3, $n1 );
		$output = '<table class="pr_quality noprint" title="' . $textualAlternative . '">
<tr>
<td class="quality4" style="width:' . $q4 . '%;"></td>
<td class="quality3" style="width:' . $q3 . '%;"></td>
<td class="quality2" style="width:' . $q2 . '%;"></td>
<td class="quality1" style="width:' . $q1 . '%;"></td>
<td class="quality0" style="width:' . $q0 . '%;"></td>
' . $void_cell . '
</tr>
</table>';
		$out->setSubtitle( $out->getSubtitle() . $output );
		return true;
	}

	/**
	 * Provides text for preload API
	 *
	 * @param string &$text
	 * @param Title $title
	 * @return bool
	 */
	public static function onEditFormPreloadText( &$text, Title $title ) {
		if ( !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return true;
		}

		$pageContentBuilder = new PageContentBuilder(
			RequestContext::getMain(), Context::getDefaultContext()
		);
		$content = $pageContentBuilder->buildDefaultContentForPageTitle( $title );
		$text = $content->serialize();

		return true;
	}

	/**
	 * Add ProofreadPage preferences to the preferences menu
	 * @param User $user
	 * @param array &$preferences
	 * @return bool
	 */
	public static function onGetPreferences( $user, &$preferences ) {
		// Show header and footer fields when editing in the Page namespace
		$preferences['proofreadpage-showheaders'] = [
			'type'           => 'toggle',
			'label-message'  => 'proofreadpage-preferences-showheaders-label',
			'section'        => 'editing/advancedediting',
		];

		// Use horizontal layout when editing in the Page namespace
		$preferences['proofreadpage-horizontal-layout'] = [
			'type'           => 'toggle',
			'label-message'  => 'proofreadpage-preferences-horizontal-layout-label',
			'section'        => 'editing/advancedediting',
		];

		return true;
	}

	/**
	 * Adds canonical namespaces.
	 * @param array &$list
	 * @return true
	 */
	public static function addCanonicalNamespaces( &$list ) {
		$list[self::getPageNamespaceId()] = 'Page';
		$list[self::getPageNamespaceId() + 1] = 'Page_talk';
		$list[self::getIndexNamespaceId()] = 'Index';
		$list[self::getIndexNamespaceId() + 1] = 'Index_talk';
		return true;
	}

	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		global $wgContentHandlerUseDB;

		$dir = __DIR__ . '/sql/';

		$updater->addExtensionTable( 'pr_index', $dir . 'ProofreadIndex.sql' );

		// fix issue with content type hardcoded in database
		if ( isset( $wgContentHandlerUseDB ) && $wgContentHandlerUseDB ) {
			$updater->addPostDatabaseUpdateMaintenance( FixProofreadPagePagesContentModel::class );
			$updater->addPostDatabaseUpdateMaintenance( FixProofreadIndexPagesContentModel::class );
		}

		return true;
	}

	/**
	 * @param array &$tables
	 * @return bool
	 */
	public static function onParserTestTables( array &$tables ) {
		$tables[] = 'pr_index';

		return true;
	}

	/**
	 * Add the links to previous, next, index page and scan image to Page: pages.
	 * @param SkinTemplate &$skin
	 * @param array &$links Structured navigation links
	 * @return true
	 */
	public static function onSkinTemplateNavigation( SkinTemplate &$skin, array &$links ) {
		$title = $skin->getTitle();
		if ( $title === null || !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return true;
		}

		// Image link
		try {
			$fileProvider = Context::getDefaultContext()->getFileProvider();
			$image = $fileProvider->getFileForPageTitle( $title );
			$imageUrl = null;
			if ( $image->isMultipage() ) {
				$transformAttributes = [
					'width' => $image->getWidth()
				];
				try {
					$transformAttributes['page'] = $fileProvider->getPageNumberForPageTitle( $title );
				} catch ( PageNumberNotFoundException $e ) {
					// We do not care
				}

				$handler = $image->getHandler();
				if ( $handler && $handler->normaliseParams( $image, $transformAttributes ) ) {
					$thumbName = $image->thumbName( $transformAttributes );
					$imageUrl = $image->getThumbUrl( $thumbName );
				}
			} else {
				// The thumb returned is invalid for not multipage pages when the width
				// requested is the image width
				$imageUrl = $image->getViewURL();
			}

			if ( $imageUrl !== null ) {
				$links['namespaces']['proofreadPageScanLink'] = [
					'class' => '',
					'href' => $imageUrl,
					'text' => wfMessage( 'proofreadpage_image' )->plain()
				];
			}
		} catch ( FileNotFoundException $e ) {
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
					$firstLinks['proofreadPagePrevLink'] = [
						'class' => ( $skin->getSkinName() === 'vector' ) ? 'icon' : '',
						'href' => self::getLinkUrlForTitle( $prevTitle ),
						'rel' => 'prev',
						'text' => $prevText,
						'title' => $prevText
					];
				}
				catch ( OutOfBoundsException $e ) {
				} // if the previous page does not exits

				try {
					$nextTitle  = $pagination->getPageTitle( $pageNumber + 1 );
					$nextText = wfMessage( 'proofreadpage_nextpage' )->plain();
					$firstLinks['proofreadPageNextLink'] = [
						'class' => ( $skin->getSkinName() === 'vector' ) ? 'icon' : '',
						'href' => self::getLinkUrlForTitle( $nextTitle ),
						'rel' => 'next',
						'text' => $nextText,
						'title' => $nextText
					];
				}
				catch ( OutOfBoundsException $e ) {
				} // if the next page does not exits
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

		return true;
	}

	/**
	 * Add proofreading status to action=info
	 * @param IContextSource $context
	 * @param array &$pageInfo The page information
	 * @return true
	 */
	public static function onInfoAction( IContextSource $context, array &$pageInfo ) {
		if ( !$context->canUseWikiPage() ) {
			return true;
		}
		$page = $context->getWikiPage();
		$title = $page->getTitle();
		if ( !$title->inNamespace( self::getPageNamespaceId() ) ) {
			return true;
		}

		$pageQualityLevelLookup = Context::getDefaultContext()->getPageQualityLevelLookup();
		$pageQualityLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $title );
		if ( $pageQualityLevel !== null ) {
			$pageInfo['header-basic'][] = [
				wfMessage( 'proofreadpage-pageinfo-status' ),
				wfMessage( "proofreadpage_quality{$pageQualityLevel}_category" ),
			];
		}

		return true;
	}

	protected static function getLinkUrlForTitle( Title $title ) {
		if ( $title->exists() ) {
			return $title->getLinkURL();
		} else {
			return $title->getLinkURL( 'action=edit&redlink=1' );
		}
	}

	public static function onSkinMinervaDefaultModules( Skin $skin, array &$modules ) {
		if (
			$skin->getTitle()->inNamespace( self::getIndexNamespaceId() ) ||
			$skin->getTitle()->inNamespace( self::getPageNamespaceId() )
		) {
			unset( $modules['editor'] );
		}

		return true;
	}

	/**
	 * Extension registration callback
	 */
	public static function onRegister() {
		// L10n
		include_once __DIR__ . '/ProofreadPage.namespaces.php';

		// Content handler
		define( 'CONTENT_MODEL_PROOFREAD_PAGE', 'proofread-page' );
		define( 'CONTENT_MODEL_PROOFREAD_INDEX', 'proofread-index' );
	}
}
