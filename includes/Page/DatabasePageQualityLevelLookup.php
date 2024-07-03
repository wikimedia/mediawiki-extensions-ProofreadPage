<?php

namespace ProofreadPage\Page;

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Title\Title;

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
	 * @var PageIdentity[]
	 */
	private $categoryForQualityLevel;

	/** @var (int|null)[] */
	private $cache = [];

	/**
	 * @param int $pageNamespaceId
	 */
	public function __construct( $pageNamespaceId ) {
		$this->pageNamespaceId = $pageNamespaceId;
	}

	/**
	 * @inheritDoc
	 */
	public function isPageTitleInCache( PageIdentity $pageTitle ): bool {
		return array_key_exists( $pageTitle->getDBkey(), $this->cache );
	}

	/**
	 * @inheritDoc
	 */
	public function flushCacheForPage( PageIdentity $pageTitle ) {
		unset( $this->cache[ $pageTitle->getDBkey() ] );
	}

	/**
	 * @inheritDoc
	 */
	public function getQualityLevelForPageTitle( PageIdentity $pageTitle ) {
		if ( !$pageTitle->getNamespace() === $this->pageNamespaceId ) {
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
			return $pageTitle instanceof PageIdentity &&
				$pageTitle->getNamespace() === $this->pageNamespaceId &&
				!array_key_exists( $pageTitle->getDBkey(), $this->cache );
		} );

		$this->fetchQualityLevelForPageTitles( $pageTitles );
	}

	/**
	 * @param PageIdentity[] $pageTitles
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
	 * @param PageIdentity[] $pageTitles
	 */
	private function fetchQualityLevelForPageTitlesFromPageProperties( array $pageTitles ) {
		if ( !$pageTitles ) {
			return;
		}

		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->getReplicaDatabase();
		$results = $dbr->newSelectQueryBuilder()
			->select( [ 'page_title', 'pp_value' ] )
			->from( 'page' )
			->join( 'page_props', null, 'page_id = pp_page' )
			->where( [
				'pp_propname' => 'proofread_page_quality_level',
				'page_namespace' => $this->pageNamespaceId,
				'page_title' => array_map( static function ( PageIdentity $pageTitle ) {
					return $pageTitle->getDBkey();
				}, $pageTitles )
			] )
			->caller( __METHOD__ )
			->fetchResultSet();
		foreach ( $results as $result ) {
			$this->cache[$result->page_title] = intval( $result->pp_value );
		}
	}

	/**
	 * @param PageIdentity[] $pageTitles
	 */
	private function fetchQualityLevelForPageTitlesFromPageCategories( array $pageTitles ) {
		if ( !$pageTitles ) {
			return;
		}

		$pageDbKeys = array_map( static function ( PageIdentity $title ){
			return $title->getDBkey();
		}, $pageTitles );

		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->getReplicaDatabase();
		foreach ( $this->getCategoryForQualityLevels() as $qualityLevel => $qualityCategory ) {
			$results = $dbr->newSelectQueryBuilder()
				->select( 'page_title' )
				->from( 'page' )
				->join( 'categorylinks', null, 'page_id = cl_from' )
				->where( [
					'page_title' => $pageDbKeys,
					'cl_to' => $qualityCategory->getDBkey(),
					'page_namespace' => $this->pageNamespaceId
				] )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $results as $result ) {
				$this->cache[$result->page_title] = $qualityLevel;
			}
		}
	}

	/**
	 * @param array $pageTitles
	 * @return array
	 */
	private function filterPagesWithoutKnownQuality( array $pageTitles ) {
		return array_filter( $pageTitles, function ( PageIdentity $pageTitle ) {
			return $this->cache[$pageTitle->getDBkey()] === null;
		} );
	}

	/**
	 * @return array categoryForQualityLevel
	 */
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
