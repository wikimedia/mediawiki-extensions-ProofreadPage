<?php

namespace ProofreadPage\Index;

use Title;

/**
 * @license GPL-2.0-or-later
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
