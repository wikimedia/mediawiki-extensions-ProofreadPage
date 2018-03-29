<?php

namespace ProofreadPage\Index;

use Title;

/**
 * @license GNU GPL v2+
 *
 * Allows to retrieve the content of the Index: page
 */
interface IndexContentLookup {

	/**
	 * Returns content of the page
	 * @param Title $indexTitle
	 * @return IndexContent
	 */
	public function getIndexContentForTitle( Title $indexTitle );
}
