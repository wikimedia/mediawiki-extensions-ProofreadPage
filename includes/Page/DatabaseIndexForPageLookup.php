<?php

namespace ProofreadPage\Page;

use RepoGroup;
use Title;

/**
 * @license GPL-2.0-or-later
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
	 * @inheritDoc
	 */
	public function getIndexForPageTitle( Title $pageTitle ) {
		$cacheKey = $pageTitle->getDBkey();
		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$this->cache[$cacheKey] = $this->findIndexTitle( $pageTitle );
		}
		return $this->cache[$cacheKey];
	}

	private function findIndexTitle( Title $pageTitle ) {
		$possibleIndexTitle = $this->findPossibleIndexTitleBasedOnName( $pageTitle );

		// Try to find links from Index: pages
		$indexesThatLinksHere = [];
		foreach ( $this->findIndexesWhichLinkTo( $pageTitle ) as $indexTitle ) {
			// It is the same as the linked file, we know it's this Index:
			if ( $possibleIndexTitle !== null && $indexTitle->equals( $possibleIndexTitle ) ) {
				return $indexTitle;
			}
			$indexesThatLinksHere[] = $indexTitle;
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
				if ( $image && $image->exists() && $image->isMultipage() ) {
					return Title::makeTitle(
						$this->indexNamespaceId, $image->getTitle()->getText()
					);
				}
			}
		}
		return null;
	}

	private function findIndexesWhichLinkTo( Title $title ) {
		$results = wfGetDB( DB_REPLICA )->select(
			[ 'page', 'pagelinks' ],
			[ 'page_namespace', 'page_title' ],
			[
				'pl_namespace' => $title->getNamespace(),
				'pl_title' => $title->getDBkey(),
				'pl_from=page_id',
				'pl_from_namespace' => $this->indexNamespaceId
			],
			__METHOD__
		);
		foreach ( $results as $row ) {
			$indexTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
			if ( $indexTitle !== null ) {
				yield $indexTitle;
			}
		}
	}
}
