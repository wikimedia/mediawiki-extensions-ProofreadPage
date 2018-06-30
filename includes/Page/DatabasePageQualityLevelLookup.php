<?php

namespace ProofreadPage\Page;

use InvalidArgumentException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the quality level of a Page: page
 *
 * TODO: drop the category fallback when all Wikisource pages will have the relevant page property
 */
class DatabasePageQualityLevelLookup implements PageQualityLevelLookup {

	/**
	 * @var int
	 */
	private $pageNamespaceId;

	/**
	 * @var Title[]
	 */
	private $categoryForQualityLevel;

	private $cache = [];

	public function __construct( $pageNamespaceId ) {
		$this->pageNamespaceId = $pageNamespaceId;
	}

	/**
	 * @inheritDoc
	 */
	public function getQualityLevelForPageTitle( Title $pageTitle ) {
		if ( !$pageTitle->inNamespace( $this->pageNamespaceId ) ) {
			throw new InvalidArgumentException( $pageTitle . ' is not in Page: namespace' );
		}
		$cacheKey = $pageTitle->getDBkey();
		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$this->fetchQualityLevelForPageTitles( [ $pageTitle ] );
		}
		return $this->cache[$cacheKey];
	}

	/**
	 * @inheritDoc
	 */
	public function prefetchQualityLevelForTitles( array $pageTitles ) {
		$pageTitles = array_filter( $pageTitles, function ( $pageTitle ) {
			return $pageTitle instanceof Title &&
				$pageTitle->inNamespace( $this->pageNamespaceId ) &&
				!array_key_exists( $pageTitle->getDBkey(), $this->cache );
		} );

		$this->fetchQualityLevelForPageTitles( $pageTitles );
	}

	/**
	 * @param Title[] $pageTitles
	 */
	private function fetchQualityLevelForPageTitles( array $pageTitles ) {
		// We set to unknown all qualities
		foreach ( $pageTitles as $pageTitle ) {
			$this->cache[$pageTitle->getDBkey()] = null;
		}

		$this->fetchQualityLevelForPageTitlesFromPageProperties( $pageTitles );
		$this->fetchQualityLevelForPageTitlesFromPageCategories(
			$this->filterPagesWithoutKnownQuality( $pageTitles )
		);
	}

	/**
	 * @param Title[] $pageTitles
	 */
	private function fetchQualityLevelForPageTitlesFromPageProperties( array $pageTitles ) {
		if ( empty( $pageTitles ) ) {
			return;
		}

		$results = wfGetDB( DB_REPLICA )->select(
			[ 'page_props', 'page' ],
			[ 'page_title', 'pp_value' ],
			[
				'pp_propname' => 'proofread_page_quality_level',
				'page_id = pp_page',
				'page_namespace' => $this->pageNamespaceId,
				'page_title' => array_map( function ( Title $pageTitle ) {
					return $pageTitle->getDBkey();
				}, $pageTitles )
			],
			__METHOD__
		);
		foreach ( $results as $result ) {
			$this->cache[$result->page_title] = $result->pp_value;
		}
	}

	/**
	 * @param Title[] $pageTitles
	 */
	private function fetchQualityLevelForPageTitlesFromPageCategories( array $pageTitles ) {
		if ( empty( $pageTitles ) ) {
			return;
		}

		$pageDbKeys = array_map( function ( Title $title ){
			return $title->getDBkey();
		}, $pageTitles );

		foreach ( $this->getCategoryForQualityLevels() as $qualityLevel => $qualityCategory ) {
			$results = wfGetDB( DB_REPLICA )->select(
				[ 'categorylinks', 'page' ],
				[ 'page_title' ],
				[
					'page_id = cl_from',
					'page_title' => $pageDbKeys,
					'cl_to' => $qualityCategory->getDBkey(),
					'page_namespace' => $this->pageNamespaceId
				],
				__METHOD__
			);

			foreach ( $results as $result ) {
				$this->cache[$result->page_title] = $qualityLevel;
			}
		}
	}

	private function filterPagesWithoutKnownQuality( array $pageTitles ) {
		return array_filter( $pageTitles, function ( Title $pageTitle ) {
			return $this->cache[$pageTitle->getDBkey()] === null;
		} );
	}

	private function getCategoryForQualityLevels() {
		if ( $this->categoryForQualityLevel === null ) {
			$this->categoryForQualityLevel = $this->computeCategoryForQualityLevels();
		}
		return $this->categoryForQualityLevel;
	}

	private function computeCategoryForQualityLevels() {
		$qualityCategories = [];
		for ( $qualityLevel = 0; $qualityLevel <= 4; $qualityLevel++ ) {
			$categoryTitle = Title::makeTitleSafe(
				NS_CATEGORY,
				wfMessage( "proofreadpage_quality{$qualityLevel}_category" )->inContentLanguage()->text()
			);
			if ( $categoryTitle !== null ) {
				$qualityCategories[$qualityLevel] = $categoryTitle;
			}
		}
		return $qualityCategories;
	}
}
