<?php

namespace ProofreadPage\Page;

use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Provide a PageQualityLevelLookup mock based on a mapping
 */
class PageQualityLevelLookupMock implements PageQualityLevelLookup {

	private $levelForPage = [];

	/**
	 * @param array $levelForPage
	 */
	public function __construct( $levelForPage ) {
		$this->levelForPage = $levelForPage;
	}

	/**
	 * @inheritDoc
	 */
	public function getQualityLevelForPageTitle( Title $pageTitle ) {
		if ( !array_key_exists( $pageTitle->getDBkey(), $this->levelForPage ) ) {
			return null;
		}
		return $this->levelForPage[ $pageTitle->getDBkey() ];
	}

	/**
	 * @inheritDoc
	 */
	public function prefetchQualityLevelForTitles( array $pageTitles ) {
	}
}
