<?php

namespace ProofreadPage\Index;

use Revision;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * Allows to retrieve the content of the Index: page
 */
class DatabaseIndexContentLookup implements IndexContentLookup {

	private $cache = [];

	/**
	 * @see IndexContentLookup::getIndexContentForTitle
	 */
	public function getIndexContentForTitle( Title $indexTitle ) {
		$cacheKey = $indexTitle->getDBkey();

		if ( !array_key_exists( $cacheKey, $this->cache ) ) {
			$rev = Revision::newFromTitle( $indexTitle );
			if ( $rev === null ) {
				$this->cache[$cacheKey] = new IndexContent( [] );
			} else {
				$content = $rev->getContent();
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
