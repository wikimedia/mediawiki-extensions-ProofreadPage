<?php

namespace ProofreadPage\Page;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use RepoGroup;

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

	/** @var (?Title)[] */
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
	public function isPageTitleInCache( Title $pageTitle ): bool {
		return array_key_exists( $pageTitle->getDBkey(), $this->cache );
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

	/**
	 * @param Title $pageTitle
	 * @return ?Title
	 */
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
		if ( $indexesThatLinksHere ) {
			// TODO: what should we do if there are more than 1 possible index?
			return reset( $indexesThatLinksHere );
		}

		return $possibleIndexTitle;
	}

	/**
	 * @param Title $pageTitle
	 * @return Title|null the index page based on the name of the Page: page and the existence
	 *   of a file with the same name
	 */
	private function findPossibleIndexTitleBasedOnName( Title $pageTitle ) {
		$m = explode( '/', $pageTitle->getText(), 2 );

		$fileTitle = Title::makeTitleSafe( NS_FILE, $m[0] );
		if ( $fileTitle === null ) {
			return null;
		}

		$file = $this->repoGroup->findFile( $fileTitle );
		if ( !$file || !$file->exists() ) {
			return null;
		}

		if ( !( $file->isMultipage() xor isset( $m[1] ) ) ) {
			return Title::makeTitle(
				$this->indexNamespaceId, $file->getTitle()->getText()
			);
		}

		return null;
	}

	/**
	 * @param Title $title
	 * @return \Generator<Title>
	 */
	private function findIndexesWhichLinkTo( Title $title ) {
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		$titleConditions = $linksMigration->getLinksConditions( 'pagelinks', $title );
		$results = wfGetDB( DB_REPLICA )->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->join( 'pagelinks', null, 'pl_from=page_id' )
			->where( [ 'pl_from_namespace' => $this->indexNamespaceId ] )
			->andWhere( $titleConditions )
			->caller( __METHOD__ )->fetchResultSet();

		foreach ( $results as $row ) {
			$indexTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
			if ( $indexTitle !== null ) {
				yield $indexTitle;
			}
		}
	}
}
