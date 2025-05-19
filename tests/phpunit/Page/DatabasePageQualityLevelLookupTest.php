<?php

namespace ProofreadPage\Page;

use MediaWiki\Title\Title;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @group Database
 * @covers \ProofreadPage\Page\DatabasePageQualityLevelLookup
 */
class DatabasePageQualityLevelLookupTest extends ProofreadPageTestCase {

	/**
	 * @dataProvider prefetchQualityLevelForTitlesProvider
	 */
	public function testprefetchQualityLevelForTitles( array $titles ) {
		$lookup = new DatabasePageQualityLevelLookup( self::getPageNamespaceId() );
		$lookup->prefetchQualityLevelForTitles( $titles );

		// FIXME: The only thing this test does is testing if the code does not fail :-(
		$this->addToAssertionCount( 1 );
	}

	public static function prefetchQualityLevelForTitlesProvider() {
		return [
			[
				[]
			],
			[
				[
					Title::makeTitle( NS_MAIN, 'Foo' ),
					Title::makeTitle( self::getPageNamespaceId(), 'Foo' ),
					null
				]
			]
		];
	}

	public function testgetQualityLevelForNotExistingPage() {
		$pageTitle = Title::makeTitle( self::getPageNamespaceId(), 'Foo' );
		$lookup = new DatabasePageQualityLevelLookup( self::getPageNamespaceId() );
		$this->assertNull( $lookup->getQualityLevelForPageTitle( $pageTitle ) );
		// FIXME: Need a test for a page with an actual value, rather than null
	}
}
