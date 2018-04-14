<?php

namespace ProofreadPage\Page;

use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Provide a FileProviderMock mock based on a mapping
 */
class IndexForPageLookupMock implements IndexForPageLookup {

	private $indexForPage = [];

	public function __construct( $indexForPage ) {
		$this->indexForPage = $indexForPage;
	}

	/**
	 * @inheritDoc
	 */
	public function getIndexForPageTitle( Title $pageTitle ) {
		if ( !array_key_exists( $pageTitle->getDBkey(), $this->indexForPage ) ) {
			return null;
		}
		return $this->indexForPage[ $pageTitle->getDBkey() ];
	}
}
