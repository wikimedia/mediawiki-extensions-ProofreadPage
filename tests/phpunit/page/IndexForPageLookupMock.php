<?php

namespace ProofreadPage\Page;

use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 *
 * Provide a FileProviderMock mock based on a mapping
 */
class IndexForPageLookupMock implements IndexForPageLookup {

	private $indexForPage = [];

	public function __construct( $indexForPage ) {
		$this->indexForPage = $indexForPage;
	}

	/**
	 * @see IndexForPageLookup::getIndexForPage
	 */
	public function getIndexForPage( ProofreadPagePage $page ) {
		if ( !array_key_exists( $page->getTitle()->getDBkey(), $this->indexForPage ) ) {
			return null;
		}
		return $this->indexForPage[ $page->getTitle()->getDBkey() ];
	}
}
