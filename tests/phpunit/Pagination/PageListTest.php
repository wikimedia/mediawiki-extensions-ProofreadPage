<?php

namespace ProofreadPage\Pagination;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageList
 */
class PageListTest extends ProofreadPageTestCase {

	public function getNumberProvider() {
		return [
			[
				new PageNumber( '2' ),
				[ '5' => '1' ],
				6
			],
			[
				new PageNumber( '3' ),
				[ '5' => '1' ],
				3
			],
			[
				new PageNumber( '-' ),
				[ '5' => '1', '5to10' => '-' ],
				8
			],
			[
				new PageNumber( '6', PageNumber::DISPLAY_ROMAN ),
				[ '5to24' => 'roman' ],
				6
			],
			[
				new PageNumber( '2', PageNumber::DISPLAY_HIGHROMAN ),
				[ '5' => '1', '5to24' => 'highroman' ],
				6
			],
			[
				new PageNumber( '8', PageNumber::DISPLAY_HIGHROMAN ),
				[ '5to24' => 'roman', '6to28' => 'highroman' ],
				8
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL, true ),
				[ '5' => '1', '5to24' => 'empty' ],
				7
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_HIGHROMAN, true ),
				[ '5' => '1', '5to24' => 'highroman', '7to22' => 'empty' ],
				8
			],
			[
				new PageNumber( '1', PageNumber::DISPLAY_HIGHROMAN ),
				[ '4' => '1;highroman' ],
				4
			]
		];
	}

	/**
	 * @dataProvider getNumberProvider
	 */
	public function testGetNumber( $result, $config, $number ) {
		$list = new PageList( $config );
		$this->assertEquals( $result, $list->getNumber( $number ) );
	}
}
