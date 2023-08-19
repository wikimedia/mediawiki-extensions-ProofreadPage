<?php

namespace ProofreadPage\Page;

use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 *
 * Provide a FileProviderMock mock based on a mapping
 */
class IndexForPageLookupMock implements IndexForPageLookup {

	/** @var Title[] */
	private $indexForPage;

	/**
	 * @param Title[] $indexForPage
	 */
	public function __construct( array $indexForPage ) {
		$this->indexForPage = $indexForPage;
	}

	/**
	 * @inheritDoc
	 */
	public function isPageTitleInCache( Title $pageTitle ): bool {
		return array_key_exists( $pageTitle->getDBkey(), $this->indexForPage );
	}

	/**
	 * @inheritDoc
	 */
	public function getIndexForPageTitle( Title $pageTitle ) {
		if ( !array_key_exists( $pageTitle->getDBkey(), $this->indexForPage ) ) {
			return null;
		}
		return $this->indexForPage[ $pageTitle->getDBkey() ];
	}
}
