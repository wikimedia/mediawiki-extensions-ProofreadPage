<?php

namespace ProofreadPage\Pagination;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageList
 */
class PageListTest extends ProofreadPageTestCase {

	public function getNumberProvider() {
		return array(
			array(
				new PageNumber( '2' ),
				array( '5' => '1' ),
				6
			),
			array(
				new PageNumber( '3' ),
				array( '5' => '1' ),
				3
			),
			array(
				new PageNumber( '-' ),
				array( '5' => '1', '5to10' => '-' ),
				8
			),
			array(
				new PageNumber( '6', PageNumber::DISPLAY_ROMAN ),
				array( '5to24' => 'roman' ),
				6
			),
			array(
				new PageNumber( '2', PageNumber::DISPLAY_HIGHROMAN ),
				array( '5' => '1', '5to24' => 'highroman' ),
				6
			),
			array(
				new PageNumber( '8', PageNumber::DISPLAY_HIGHROMAN ),
				array( '5to24' => 'roman', '6to28' => 'highroman' ),
				8
			),
			array(
				new PageNumber( '3', PageNumber::DISPLAY_NORMAL, true ),
				array( '5' => '1', '5to24' => 'empty' ),
				7
			),
			array(
				new PageNumber( '4', PageNumber::DISPLAY_HIGHROMAN, true ),
				array( '5' => '1', '5to24' => 'highroman', '7to22' => 'empty' ),
				8
			),
			array(
				new PageNumber( '1', PageNumber::DISPLAY_HIGHROMAN ),
				array( '4' => '1;highroman' ),
				4
			)
		);
	}

	/**
	 * @dataProvider getNumberProvider
	 */
	public function testGetNumber( $result, $config, $number ) {
		$list = new PageList( $config );
		$this->assertEquals( $result, $list->getNumber( $number ) );
	}
}