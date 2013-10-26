<?php

use ProofreadPage\Page\PageContent;

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class ProofreadPagePageTest extends ProofreadPageTestCase {

	/**
	 * Constructor of a new ProofreadPagePage
	 * @param $title Title|string
	 * @param $content PageContent|null
	 * @param $index ProofreadIndexPage|null
	 * @return ProofreadPagePage
	 */
	public static function newPagePage( $title = 'test.jpg', ProofreadIndexPage $index = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 250, $title );
		}
		return new ProofreadPagePage( $title, $index );
	}

	public function testEquals() {
		$page = self::newPagePage( 'Test.djvu' );
		$page2 = self::newPagePage( 'Test.djvu' );
		$page3 = self::newPagePage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 250, 'Test.djvu' );
		$page = ProofreadPagePage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetPageNumber() {
		$this->assertEquals( 1, self::newPagePage( 'Test.djvu/1' )->getPageNumber() );

		$this->assertNull( self::newPagePage( 'Test.djvu' )->getPageNumber() );
	}

	public function testGetIndex() {
		$index = ProofreadIndexPageTest::newIndexPage();
		$this->assertEquals( $index, self::newPagePage( 'Test.jpg', $index )->getIndex() );
	}

	public function testGetImageWidth() {
		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|width= 500 \n}}" );
		$this->assertEquals( 500, self::newPagePage( 'Test.jpg', $index )->getImageWidth() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|title=500\n}}" );
		$this->assertEquals( ProofreadPagePage::DEFAULT_IMAGE_WIDTH, self::newPagePage( 'Test.jpg', $index )->getImageWidth() );
	}

	public function testGetCustomCss() {
		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|CSS= width:300px; \n}}" );
		$this->assertEquals( 'width:300px;', self::newPagePage( 'Test.jpg', $index )->getCustomCss() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|CSS= background: url('/my-bad-url.jpg');\n}}" );
		$this->assertEquals( '/* insecure input */', self::newPagePage( 'Test.jpg', $index )->getCustomCss() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|CSS= width:300px;<style> \n}}" );
		$this->assertEquals( 'width:300px;&lt;style&gt;', self::newPagePage( 'Test.jpg', $index )->getCustomCss() );
	}
}