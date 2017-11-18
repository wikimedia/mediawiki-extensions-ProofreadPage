<?php

namespace ProofreadPage\Page;

use ProofreadIndexDbConnector;
use RepoGroup;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * Allows to retrieve the Index: page for a Page: page
 */
class DatabaseIndexForPageLookup implements IndexForPageLookup {

	/**
	 * @var int
	 */
	private $indexNamespaceId;

	/**
	 * @var RepoGroup
	 */
	private $repoGroup;

	private $cache = [];

	/**
	 * @param int $indexNamespaceId
	 * @param RepoGroup $repoGroup
	 */
	public function __construct( $indexNamespaceId, RepoGroup $repoGroup ) {
		$this->indexNamespaceId = $indexNamespaceId;
		$this->repoGroup = $repoGroup;
	}

	/**
	 * @see IndexForPageLookup::getIndexForPageTitle
	 */
	public function getIndexForPageTitle( Title $pageTitle ) {
		$cacheKey = $pageTitle->getDBkey();

		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$indexTitle = $this->findIndexTitle( $pageTitle );
			if ( $indexTitle === null ) {
				$this->cache[$cacheKey] = null;
			} else {
				$this->cache[$cacheKey] = $indexTitle;
			}
		}
		return $this->cache[$cacheKey];
	}

	private function findIndexTitle( Title $pageTitle ) {
		$possibleIndexTitle = $this->findPossibleIndexTitleBasedOnName( $pageTitle );

		// Try to find links from Index: pages
		$result = ProofreadIndexDbConnector::getRowsFromTitle( $pageTitle );
		$indexesThatLinksHere = [];
		foreach ( $result as $x ) {
			$refTitle = Title::makeTitle( $x->page_namespace, $x->page_title );
			if ( $refTitle !== null &&
				$refTitle->inNamespace( $this->indexNamespaceId )
			) {
				if ( $possibleIndexTitle !== null &&
					// It is the same as the linked file, we know it's this Index:
					$refTitle->equals( $possibleIndexTitle )
				) {
					return $refTitle;
				}
				$indexesThatLinksHere[] = $refTitle;
			}
		}
		if ( !empty( $indexesThatLinksHere ) ) {
			// TODO: what should we do if there are more than 1 possible index?
			return reset( $indexesThatLinksHere );
		}

		return $possibleIndexTitle;
	}

	/**
	 * @return Title|null the index page based on the name of the Page: page and the existence
	 *   of a file with the same name
	 */
	private function findPossibleIndexTitleBasedOnName( Title $pageTitle ) {
		$m = explode( '/', $pageTitle->getText(), 2 );
		if ( isset( $m[1] ) ) {
			$imageTitle = Title::makeTitleSafe( NS_FILE, $m[0] );
			if ( $imageTitle !== null ) {
				$image = $this->repoGroup->findFile( $imageTitle );
				// if it is multipage, we use the page order of the file
				if ( $image->exists() && $image->isMultipage() ) {
					return Title::makeTitle(
						$this->indexNamespaceId, $image->getTitle()->getText()
					);
				}
			}
		}
		return null;
	}
}
