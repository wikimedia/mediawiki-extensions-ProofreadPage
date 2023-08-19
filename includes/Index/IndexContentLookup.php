<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 *
 * Allows to retrieve the content of the Index: page
 */
interface IndexContentLookup {

	/**
	 * Report if the given index page's content is cached already
	 * @param Title $indexTitle the title of an index page
	 * @return bool true if the content for this index is already cached
	 */
	public function isIndexTitleInCache( Title $indexTitle ): bool;

	/**
	 * Returns content of the index page
	 * @param Title $indexTitle
	 * @return IndexContent
	 */
	public function getIndexContentForTitle( Title $indexTitle );
}
