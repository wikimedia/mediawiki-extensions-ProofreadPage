<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 */
class IndexQualityStatsLookupMock extends IndexQualityStatsLookup {

	private array $qualityStatsForIndex;

	/**
	 * @param array<string,PagesQualityStats> $qualityStatsForIndex
	 */
	public function __construct( array $qualityStatsForIndex ) {
		$this->qualityStatsForIndex = $qualityStatsForIndex;
	}

	public function getStatsForIndexTitle( Title $indexTitle ): PagesQualityStats {
		if ( !array_key_exists( $indexTitle->getPrefixedDBkey(), $this->qualityStatsForIndex ) ) {
			return new PagesQualityStats( 0, [] );
		}
		return $this->qualityStatsForIndex[$indexTitle->getPrefixedDBkey()];
	}
}
