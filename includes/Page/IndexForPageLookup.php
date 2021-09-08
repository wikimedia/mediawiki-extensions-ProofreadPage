<?php

namespace ProofreadPage\Page;

use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the Index: page for a Page: page
 */
interface IndexForPageLookup {

	/**
	 * Check if the given page has a cached index
	 * @param Title $pageTitle
	 * @return bool
	 */
	public function isPageTitleInCache( Title $pageTitle ): bool;

	/**
	 * Return index of the page
	 * @param Title $pageTitle
	 * @return Title|null
	 */
	public function getIndexForPageTitle( Title $pageTitle );
}
