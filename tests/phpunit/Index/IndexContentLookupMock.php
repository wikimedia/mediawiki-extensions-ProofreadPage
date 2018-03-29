<?php

namespace ProofreadPage\Index;

use Title;

/**
 * @license GNU GPL v2+
 */
class IndexContentLookupMock implements IndexContentLookup {

	private $contentForIndex = [];

	public function __construct( $contentForIndex ) {
		$this->contentForIndex = $contentForIndex;
	}

	/**
	 * @inheritDoc
	 */
	public function getIndexContentForTitle( Title $indexTitle ) {
		if ( !array_key_exists( $indexTitle->getDBkey(), $this->contentForIndex ) ) {
			return null;
		}
		return $this->contentForIndex[ $indexTitle->getDBkey() ];
	}
}
