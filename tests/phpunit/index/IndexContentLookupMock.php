<?php

namespace ProofreadPage\Index;

use ProofreadIndexPage;

/**
 * @licence GNU GPL v2+
 */
class IndexContentLookupMock implements IndexContentLookup {

	private $contentForIndex = [];

	public function __construct( $contentForIndex ) {
		$this->contentForIndex = $contentForIndex;
	}

	/**
	 * Returns content of the page
	 * @return IndexContent
	 */
	public function getIndexContent( ProofreadIndexPage $index ) {
		if ( !array_key_exists( $index->getTitle()->getDBkey(), $this->contentForIndex ) ) {
			return null;
		}
		return $this->contentForIndex[ $index->getTitle()->getDBkey() ];
	}
}
