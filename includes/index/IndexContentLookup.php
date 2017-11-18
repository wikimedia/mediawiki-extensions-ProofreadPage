<?php

namespace ProofreadPage\Index;

use Title;

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
	public function getIndexContentForTitle( Title $indexTitle );
}
