<?php

namespace ProofreadPage\Page;

use MediaWiki\Page\PageIdentity;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the quality level for a Page: page
 */
interface PageQualityLevelLookup {

	/**
	 * Check if the quality of the given page is already cached
	 * @param PageIdentity $pageTitle
	 * @return bool
	 */
	public function isPageTitleInCache( PageIdentity $pageTitle ): bool;

	/**
	 * Flush the cache for a page
	 *
	 * This should be done after the page quality is updated.
	 * @param PageIdentity $pageTitle
	 */
	public function flushCacheForPage( PageIdentity $pageTitle );

	/**
	 * @param PageIdentity $pageTitle
	 * @return int|null
	 */
	public function getQualityLevelForPageTitle( PageIdentity $pageTitle );

	/**
	 * @param PageIdentity[] $pageTitles
	 */
	public function prefetchQualityLevelForTitles( array $pageTitles );
}
