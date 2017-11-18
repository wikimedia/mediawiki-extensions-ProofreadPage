<?php

namespace ProofreadPage\Page;

use Title;

/**
 * @licence GNU GPL v2+
 *
 * Allows to retrieve the Index: page for a Page: page
 */
interface IndexForPageLookup {

	/**
	 * Return index of the page
	 * @return Title|null
	 */
	public function getIndexForPageTitle( Title $pageTitle );
}
