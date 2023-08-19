<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @license GPL-2.0-or-later
 */
class IndexQualityStatsLookup {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var PagesQualityStats[] */
	private $cache = [];

	/**
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct( ILoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Report if the given index page's stats are cached already
	 * @param Title $indexTitle the title of an index page
	 * @return bool true if the stat for this index are already cached
	 */
	public function isIndexTitleInCache( Title $indexTitle ): bool {
		return array_key_exists( $indexTitle->getPrefixedDBkey(), $this->cache );
	}

	/**
	 * @param Title $indexTitle
	 * @return PagesQualityStats
	 */
	public function getStatsForIndexTitle( Title $indexTitle ): PagesQualityStats {
		$cacheKey = $indexTitle->getPrefixedDBkey();
		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$this->cache[$cacheKey] = $this->fetchStatsForIndexTitle( $indexTitle );
		}
		return $this->cache[$cacheKey];
	}

	/**
	 * @param Title $indexTitle
	 * @return PagesQualityStats
	 */
	private function fetchStatsForIndexTitle( Title $indexTitle ): PagesQualityStats {
		$row = $this->loadBalancer->getConnection( ILoadBalancer::DB_REPLICA )->selectRow(
			[ 'pr_index' ],
			[ 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ],
			[ 'pr_page_id' => $indexTitle->getArticleID() ],
			__METHOD__
		);
		if ( !$row ) {
			return new PagesQualityStats( 0, [] );
		}
		return new PagesQualityStats(
			$row->pr_count,
			[ $row->pr_q0, $row->pr_q1, $row->pr_q2, $row->pr_q3, $row->pr_q4 ]
		);
	}
}
