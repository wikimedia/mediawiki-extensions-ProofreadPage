<?php

namespace ProofreadPage\Pagination;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PageNumber
 */
class PageNumberTest extends ProofreadPageTestCase {

	public static function formattedPageNumberProvider() {
		return [
			[ '1', '1', new PageNumber( '1' ), null ],
			[ 'X', 'X', new PageNumber( '10', PageNumber::DISPLAY_HIGHROMAN ), null ],
			[ 'x', 'x', new PageNumber( '10', PageNumber::DISPLAY_ROMAN ), null ],
			[ '๒๓', '๒๓', new PageNumber( '23', 'thai' ), null ],
			[ '๑๐๐๐๐๐', '๑๐๐๐๐๐', new PageNumber( '100000', 'thai' ), null ],
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
			[ '৩৵৹', '৩৵৹', new PageNumber( '50', PageNumber::DISPLAY_BENGALI_CURRENCY ), null ],
			[ '৩৲', '৩৲', new PageNumber( '48', PageNumber::DISPLAY_BENGALI_CURRENCY ), null ],
			[ '১১৲', '১১৲', new PageNumber( '176', PageNumber::DISPLAY_BENGALI_CURRENCY ), null ],
			[ '৷৵৹', '৷৵৹', new PageNumber( '6', PageNumber::DISPLAY_BENGALI_CURRENCY ), null ],
			[ '০', '০', new PageNumber( '-1', PageNumber::DISPLAY_BENGALI_CURRENCY ), null ],
			[ 'قكه', 'قكه', new PageNumber( '125', PageNumber::DISPLAY_ARABIC_JOMML ), null ],
			[ 'بغقكه', 'بغقكه', new PageNumber( '2125', PageNumber::DISPLAY_ARABIC_JOMML ), null ],
			[ 'غا', 'غا', new PageNumber( '1001', PageNumber::DISPLAY_ARABIC_JOMML ), null ],
			[ 'قكه', 'قكه', new PageNumber( '125', PageNumber::DISPLAY_ARABIC_MAGHRIBI_JOMML ), null ],
			[ 'بشقكه', 'بشقكه', new PageNumber( '2125', PageNumber::DISPLAY_ARABIC_MAGHRIBI_JOMML ), null ],
			[ 'شا', 'شا', new PageNumber( '1001', PageNumber::DISPLAY_ARABIC_MAGHRIBI_JOMML ), null ],

		];
	}

	/**
	 * @dataProvider formattedPageNumberProvider
	 */
	public function testGetFormattedPageNumber(
		$formattedResult, $rawResult, PageNumber $number, $language = null
	) {
		$language = ( $language === null ) ? 'en' : $language;
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( $language );
		$this->assertSame( $formattedResult, $number->getFormattedPageNumber( $language ) );
		$this->assertSame( $rawResult, $number->getRawPageNumber( $language ) );
	}

	public function testCustomPageNumberFormatsAreSupported() {
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		foreach ( PageNumber::getDisplayModes() as $displayMode ) {
			if ( $displayMode !== PageNumber::DISPLAY_NORMAL && $displayMode !== 'latn' ) {
				$pageNumber = new PageNumber( '2', $displayMode );
				$this->assertNotSame( '2', $pageNumber->getFormattedPageNumber( $language ) );
			}
		}
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

	public function testHuge() {
		$language = $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'en' );
		$number = new PageNumber( '1000000000000000000000000000000000000', PageNumber::DISPLAY_NORMAL );
		$this->assertEquals( PHP_INT_MAX, $number->getRawPageNumber( $language ) );
		$this->assertTrue( $number->isNumeric() );
	}
}
