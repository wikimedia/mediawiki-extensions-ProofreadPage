<?php

namespace ProofreadPage\Index;

use ProofreadPage\Page\PageQualityLevelLookup;
use ProofreadPage\Pagination\Pagination;
use Title;

/**
 * @license GPL-2.0-or-later
 */
class QualityStatsBuilder {

	/** @var PageQualityLevelLookup */
	private $pageQualityLevelLookup;

	/**
	 * @param PageQualityLevelLookup $pageQualityLevelLookup
	 */
	public function __construct( PageQualityLevelLookup $pageQualityLevelLookup ) {
		$this->pageQualityLevelLookup = $pageQualityLevelLookup;
	}

	/**
	 * @param Pagination $pagination
	 * @param Title|null $overridePage
	 * @param int|null $overridePageLevel
	 * @return PagesQualityStats
	 */
	public function buildStatsForPaginationWithOverride(
		Pagination $pagination,
		Title $overridePage = null,
		int $overridePageLevel = null
	): PagesQualityStats {
		$pages = iterator_to_array( $pagination );
		$this->pageQualityLevelLookup->prefetchQualityLevelForTitles( $pages );

		$numberOfPagesByLevel = [];
		foreach ( $pages as $pageTitle ) {
			if ( $overridePage !== null && $pageTitle->equals( $overridePage ) ) {
				$pageQualityLevel = $overridePageLevel;
			} else {
				$pageQualityLevel = $this->pageQualityLevelLookup->getQualityLevelForPageTitle( $pageTitle );
			}
			if ( $pageQualityLevel === null ) {
				continue;
			}

			if ( array_key_exists( $pageQualityLevel, $numberOfPagesByLevel ) ) {
				$numberOfPagesByLevel[$pageQualityLevel]++;
			} else {
				$numberOfPagesByLevel[$pageQualityLevel] = 1;
			}
		}

		return new PagesQualityStats( $pagination->getNumberOfPages(), $numberOfPagesByLevel );
	}
}
