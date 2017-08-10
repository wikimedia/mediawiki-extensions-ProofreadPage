<?php

namespace ProofreadPage\Index;

use ProofreadIndexPage;

/**
 * @licence GNU GPL v2+
 *
 * Allows to retrieve the content of the Index: page
 */
interface IndexContentLookup {

	/**
	 * Returns content of the page
	 * @return IndexContent
	 */
	public function getIndexContent( ProofreadIndexPage $index );
}
