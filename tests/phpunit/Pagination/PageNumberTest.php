<?php

namespace ProofreadPage\Pagination;

use MediaWiki\MediaWikiServices;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageNumber
 */
class PageNumberTest extends ProofreadPageTestCase {

	public function formattedPageNumberProvider() {
		return [
			[ '1', '1', new PageNumber( '1' ), null ],
			[ 'X', 'X', new PageNumber( '10', PageNumber::DISPLAY_HIGHROMAN ), null ],
			[ 'x', 'x', new PageNumber( '10', PageNumber::DISPLAY_ROMAN ), null ],
			[ '12<sup>r</sup>', '12r',
				new PageNumber( '12', PageNumber::DISPLAY_FOLIO, false, true ), null ],
			[ '12<sup>v</sup>', '12v',
				new PageNumber( '12', PageNumber::DISPLAY_FOLIO, false, false ), null ],
			[ 'XIII<sup>r</sup>', 'XIIIr',
				new PageNumber( '13', PageNumber::DISPLAY_FOLIOHIGHROMAN, false, true ), null ],
			[ 'xiv<sup>v</sup>', 'xivv',
				new PageNumber( '14', PageNumber::DISPLAY_FOLIOROMAN, false, false ), null ],
			[ 'test', 'test', new PageNumber( 'test', PageNumber::DISPLAY_ROMAN ), null ],
			[ '૮', '૮', new PageNumber( '8' ), 'gu' ],
		];
	}

	/**
	 * @dataProvider formattedPageNumberProvider
	 */
	public function testGetFormattedPageNumber(
		$formattedResult, $rawResult, PageNumber $number, $language = null
	) {
		$language = ( $language === null ) ? 'en' : $language;
		$language = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $language );
		$this->assertSame( $formattedResult, $number->getFormattedPageNumber( $language ) );
		$this->assertSame( $rawResult, $number->getRawPageNumber( $language ) );
	}

	public function testIsEmpty() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN, true );
		$this->assertTrue( $number->isEmpty() );

		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN, false );
		$this->assertFalse( $number->isEmpty() );
	}

	public function testGetDisplayMode() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN );
		$this->assertSame( PageNumber::DISPLAY_ROMAN, $number->getDisplayMode() );

		$number = new PageNumber( '10' );
		$this->assertSame( PageNumber::DISPLAY_NORMAL, $number->getDisplayMode() );

		$number = new PageNumber( '10', PageNumber::DISPLAY_FOLIO );
		$this->assertSame( PageNumber::DISPLAY_FOLIO, $number->getDisplayMode() );
	}

	public function testIsNumeric() {
		$number = new PageNumber( '10', PageNumber::DISPLAY_ROMAN );
		$this->assertTrue( $number->isNumeric() );

		$number = new PageNumber( 'a' );
		$this->assertFalse( $number->isNumeric() );

		$number = new PageNumber( '10', PageNumber::DISPLAY_FOLIO );
		$this->assertTrue( $number->isNumeric() );
	}
}
