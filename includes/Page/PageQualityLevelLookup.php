<?php

namespace ProofreadPage\Page;

use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the quality level for a Page: page
 */
interface PageQualityLevelLookup {

	/**
	 * Check if the quality of the given page is already cached
	 * @param Title $pageTitle
	 * @return bool
	 */
	public function isPageTitleInCache( Title $pageTitle ): bool;

	/**
	 * Flush the cache for a page
	 *
	 * This should be done after the page quality is updated.
	 * @param Title $pageTitle
	 */
	public function flushCacheForPage( Title $pageTitle );

	/**
	 * @param Title $pageTitle
	 * @return int|null
	 */
	public function getQualityLevelForPageTitle( Title $pageTitle );

	/**
	 * @param Title[] $pageTitles
	 */
	public function prefetchQualityLevelForTitles( array $pageTitles );
}
