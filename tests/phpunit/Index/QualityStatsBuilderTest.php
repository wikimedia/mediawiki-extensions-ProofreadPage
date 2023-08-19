<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;
use ProofreadPage\Page\PageQualityLevelLookupMock;
use ProofreadPage\Pagination\PageNumber;
use ProofreadPage\Pagination\PagePagination;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\QualityStatsBuilder
 */
class QualityStatsBuilderTest extends ProofreadPageTestCase {

	/**
	 * @dataProvider buildStatsForPaginationProviderWithOverride
	 */
	public function testBuildStatsForPaginationWithOverride(
		PagePagination $pagination,
		array $qualityLevels,
		PagesQualityStats $qualityStats,
		Title $overridePage = null,
		int $overridePageLevel = null
	) {
		$builder = new QualityStatsBuilder( new PageQualityLevelLookupMock( $qualityLevels ) );
		$this->assertEquals(
			$qualityStats,
			$builder->buildStatsForPaginationWithOverride( $pagination, $overridePage, $overridePageLevel )
		);
	}

	public function buildStatsForPaginationProviderWithOverride() {
		return [
			[
				new PagePagination(
					[
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 0.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 3.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 4.jpg' ),
					],
					[
						new PageNumber( '0' ),
						new PageNumber( '1' ),
						new PageNumber( '2' ),
						new PageNumber( '3' ),
						new PageNumber( '4' ),
					]
				),
				[
					'Test_0.jpg' => 0,
					'Test_1.jpg' => 1,
					'Test_2.jpg' => 2,
					'Test_3.jpg' => 3,
					'Test_4.jpg' => 4,
				],
				new PagesQualityStats( 5, [ 1, 1, 1, 1, 1 ] )
			],
			[
				new PagePagination(
					[],
					[]
				),
				[],
				new PagesQualityStats( 0, [] )
			],
			[
				new PagePagination(
					[
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 0.jpg' ),
					],
					[
						new PageNumber( '0' ),
					]
				),
				[
				],
				new PagesQualityStats( 1, [] )
			],
			[
				new PagePagination(
					[
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 0.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
					],
					[
						new PageNumber( '0' ),
						new PageNumber( '1' )
					]
				),
				[
					'Test_0.jpg' => 0,
					'Test_1.jpg' => 0,
				],
				new PagesQualityStats( 2, [ 0 => 2 ] )
			],
			[
				new PagePagination(
					[
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 0.jpg' ),
						Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
					],
					[
						new PageNumber( '0' ),
						new PageNumber( '1' )
					]
				),
				[
					'Test_0.jpg' => 0,
					'Test_1.jpg' => 0,
				],
				new PagesQualityStats( 2, [ 0 => 1,  4 => 1 ] ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 0.jpg' ),
				4
			],
		];
	}
}
