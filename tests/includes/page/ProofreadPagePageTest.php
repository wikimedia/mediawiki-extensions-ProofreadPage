<?php

/**
 * @group ProofreadPage
 */
class ProofreadPagePageTest extends ProofreadPageTestCase {

	/**
	 * Constructor of a new ProofreadPagePage
	 * @param $title Title|string
	 * @param $content ProofreadPageContent|null
	 * @param $index ProofreadIndexPage|null
	 * @return ProofreadPagePage
	 */
	public static function newPagePage( $title = 'test.jpg', ProofreadPageContent $content = null, ProofreadIndexPage $index = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 250, $title );
		}
		return new ProofreadPagePage( $title, $content, $index );
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
		$this->assertEquals( $index, self::newPagePage( 'Test.jpg', null, $index )->getIndex() );
	}

	public function testGetContent() {
		$pageContent = ProofreadPageContentTest::newContent( '', 'aaa' );
		$this->assertEquals( $pageContent, self::newPagePage( 'Test.jpg', $pageContent )->getContent() );
	}

	public function testGetImageWidth() {
		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|width= 500 \n}}" );
		$this->assertEquals( 500, self::newPagePage( 'Test.jpg', null, $index )->getImageWidth() );

		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|title=500\n}}" );
		$this->assertEquals( ProofreadPagePage::DEFAULT_IMAGE_WIDTH, self::newPagePage( 'Test.jpg', null, $index )->getImageWidth() );
	}

	public function testGetCustomCss() {
		$index = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|CSS= width:300px; \n}}" );
		$this->assertEquals( 'width:300px;', self::newPagePage( 'Test.jpg', null, $index )->getCustomCss() );
	}
}