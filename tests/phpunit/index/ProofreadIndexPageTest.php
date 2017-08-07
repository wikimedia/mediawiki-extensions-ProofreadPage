<?php

use ProofreadPage\Index\CustomIndexField;
use ProofreadPage\Index\IndexContent;

/**
 * @group ProofreadPage
 * @covers ProofreadIndexPage
 */
class ProofreadIndexPageTest extends ProofreadPageTestCase {

	/**
	 * Constructor of a new ProofreadIndexPage
	 * @param Title|string $title
	 * @param string|IndexContent|null $content
	 * @return ProofreadIndexPage
	 */
	public static function newIndexPage( $title = 'test.djvu', $content = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 252, $title );
		}
		if ( is_string( $content ) ) {
			$content = ContentHandler::getForModelID( CONTENT_MODEL_PROOFREAD_INDEX )
				->unserializeContent( $content );
		}
		return new ProofreadIndexPage( $title, $content );
	}

	public function testEquals() {
		$page = self::newIndexPage( 'Test.djvu' );
		$page2 = self::newIndexPage( 'Test.djvu' );
		$page3 = self::newIndexPage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 252, 'Test.djvu' );
		$page = ProofreadIndexPage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function mimeTypesProvider() {
		return [
			[ 'image/vnd.djvu', 'Test.djvu' ],
			[ 'application/pdf', 'Test.pdf' ],
			[ null, 'Test' ]
		];
	}

	/**
	 * @dataProvider mimeTypesProvider
	 */
	public function testGetMimeType( $mime, $name ) {
		$this->assertEquals( $mime, self::newIndexPage( $name )->getMimeType() );
	}
}
