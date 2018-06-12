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
	 * @param Title $pageTitle
	 * @return int|null
	 */
	public function getQualityLevelForPageTitle( Title $pageTitle );

	/**
	 * @param Title[] $pageTitles
	 */
	public function prefetchQualityLevelForTitles( array $pageTitles );
}
