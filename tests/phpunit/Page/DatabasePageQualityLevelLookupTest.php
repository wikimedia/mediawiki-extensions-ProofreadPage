<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\DatabasePageQualityLevelLookup
 */
class DatabasePageQualityLevelLookupTest extends ProofreadPageTestCase {

	/**
	 * @dataProvider prefetchQualityLevelForTitlesProvider
	 */
	public function testprefetchQualityLevelForTitles( array $titles ) {
		$lookup = new DatabasePageQualityLevelLookup( $this->getPageNamespaceId() );
		$lookup->prefetchQualityLevelForTitles( $titles );

		// FIXME: The only thing this test does is testing if the code does not fail :-(
		$this->addToAssertionCount( 1 );
	}

	public function prefetchQualityLevelForTitlesProvider() {
		return [
			[
				[]
			],
			[
				[
					Title::makeTitle( NS_MAIN, 'Foo' ),
					Title::makeTitle( $this->getPageNamespaceId(), 'Foo' ),
					null
				]
			]
		];
	}
}
