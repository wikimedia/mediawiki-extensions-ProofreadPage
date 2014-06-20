<?php

namespace ProofreadPage\Pagination;

use Language;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageNumber
 */
class ProofreadPageNumberTest extends ProofreadPageTestCase {

	public function formattedPageNumberProvider() {
		return array(
			array( '1', new PageNumber( '1' ), null ),
			array( 'X', new PageNumber( '10', PageNumber::DISPLAY_HIGHROMAN ), null ),
			array( 'x', new PageNumber( '10', PageNumber::DISPLAY_ROMAN ), null ),
			array( 'test', new PageNumber( 'test', PageNumber::DISPLAY_ROMAN ), null ),
			array( '૮', new PageNumber( '8'), Language::factory( 'gu' ) ),
		);
	}

	/**
	 * @dataProvider formattedPageNumberProvider
	 */
	public function testGetFormattedPageNumber( $result, PageNumber $number, $language = null ) {
		$language = ( $language === null ) ? Language::factory( 'en' ) : $language;
		$this->assertEquals( $result, $number->getFormattedPageNumber( $language ) );
	}

	public function testIsEmpty() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN, true );
		$this->assertTrue( $number->isEmpty() );

		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN, false );
		$this->assertFalse( $number->isEmpty() );
	}

	public function testGetDisplayMode() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN );
		$this->assertEquals( $number->getDisplayMode(), PageNumber::DISPLAY_ROMAN );

		$number = new PageNumber( '10' );
		$this->assertEquals( $number->getDisplayMode(), PageNumber::DISPLAY_NORMAL );
	}

	public function testIsNumeric() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN );
		$this->assertTrue( $number->isNumeric() );

		$number = new PageNumber( 'a' );
		$this->assertFalse( $number->isNumeric() );
	}
}