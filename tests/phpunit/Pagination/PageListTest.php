<?php

namespace ProofreadPage\Pagination;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageList
 */
class PageListTest extends ProofreadPageTestCase {

	public static function getNumberProvider() {
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
			],
			[
				new PageNumber( '7', PageNumber::DISPLAY_FOLIO, false, false ),
				[ '7to10' => 'folio' ],
				8
			],
			[
				new PageNumber( '8', PageNumber::DISPLAY_FOLIO, false, true ),
				[ '7to10' => 'folio' ],
				9
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOROMAN, false, true ),
				[ '1to20' => 'folioroman' ],
				5
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOHIGHROMAN, false, false ),
				[ '1to20' => 'foliohighroman' ],
				6
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIO, false, true ),
				[ '7' => '2', '7to10' => 'folio' ],
				9
			],
			[
				new PageNumber( '7', PageNumber::DISPLAY_FOLIO, false, true ),
				[ '7' => 'folio' ],
				7
			],
			[
				new PageNumber( '21', PageNumber::DISPLAY_NORMAL ),
				[ '1to20' => 'folio' ],
				21
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_ROMAN ),
				[ '3to10odd' => 'roman' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIO, false, true ),
				[ '3to10odd' => 'folio' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOROMAN, false, true ),
				[ '3to10odd' => 'folioroman' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOHIGHROMAN, false, true ),
				[ '3to10odd' => 'foliohighroman' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL ),
				[ '3to10even' => 'roman' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL ),
				[ '3to10even' => 'folio' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL ),
				[ '3to10even' => 'folioroman' ],
				3
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL ),
				[ '3to10even' => 'foliohighroman' ],
				3
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_NORMAL, false, true ),
				[ '3to10odd' => 'roman' ],
				4
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_NORMAL, false, true ),
				[ '3to10odd' => 'folio' ],
				4
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_NORMAL, false, true ),
				[ '3to10odd' => 'folioroman' ],
				4
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_NORMAL, false, true ),
				[ '3to10odd' => 'foliohighroman' ],
				4
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_ROMAN, false, true ),
				[ '3to10even' => 'roman' ],
				4
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIO, false, false ),
				[ '3to10even' => 'folio' ],
				4
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOROMAN, false, false ),
				[ '3to10even' => 'folioroman' ],
				4
			],
			[
				new PageNumber( '3', PageNumber::DISPLAY_FOLIOHIGHROMAN, false, false ),
				[ '3to10even' => 'foliohighroman' ],
				4
			],
			[
				new PageNumber( '4', PageNumber::DISPLAY_FOLIO, false, true ),
				[ '3to10odd' => 'folio' ],
				5
			],
			[
				new PageNumber( '3', 'thai' ),
				[ '3to10' => 'thai' ],
				3
			],
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
