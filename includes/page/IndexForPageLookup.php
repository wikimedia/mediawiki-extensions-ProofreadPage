<?php

namespace ProofreadPage\Page;

use ProofreadIndexPage;
use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 *
 * Allows to retrieve the Index: page for a Page: page
 */
interface IndexForPageLookup {

	/**
	 * Return index of the page
	 * @param ProofreadPagePage $page
	 * @return ProofreadIndexPage|null
	 */
	public function getIndexForPage( ProofreadPagePage $page );
}
