<?php

namespace ProofreadPage\Page;

use InvalidArgumentException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the quality level of a Page: page
 */
class DatabasePageQualityLevelLookup implements PageQualityLevelLookup {

	private $pageNamespaceId;

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

		if ( empty( $pageTitles ) ) {
			return;
		}

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
}
