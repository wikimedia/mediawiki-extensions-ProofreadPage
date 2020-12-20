<?php

namespace ProofreadPage\Index;

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the content of the Index: page
 */
class DatabaseIndexContentLookup implements IndexContentLookup {

	/** @var IndexContent[] */
	private $cache = [];

	/**
	 * @inheritDoc
	 */
	public function getIndexContentForTitle( Title $indexTitle ) {
		$cacheKey = $indexTitle->getDBkey();

		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$revision = MediaWikiServices::getInstance()->getRevisionStore()
				->getRevisionByTitle( $indexTitle );
			if ( $revision === null ) {
				$this->cache[$cacheKey] = new IndexContent( [] );
			} else {
				$content = $revision->getContent( SlotRecord::MAIN );
				if ( $content instanceof IndexContent ) {
					$this->cache[$cacheKey] = $content;
				} else {
					$this->cache[$cacheKey] = new IndexContent( [] );
				}
			}
		}

		return $this->cache[$cacheKey];
	}
}
