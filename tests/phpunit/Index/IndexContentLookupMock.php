<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 */
class IndexContentLookupMock implements IndexContentLookup {

	/** @var IndexContent[] */
	private $contentForIndex;

	/**
	 * @param IndexContent[] $contentForIndex
	 */
	public function __construct( array $contentForIndex ) {
		$this->contentForIndex = $contentForIndex;
	}

	/**
	 * @inheritDoc
	 */
	public function isIndexTitleInCache( Title $indexTitle ): bool {
		return array_key_exists( $indexTitle->getDBkey(), $this->cache );
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
