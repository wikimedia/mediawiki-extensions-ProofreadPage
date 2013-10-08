<?php

/**
 * @group ProofreadPage
 */
class ProofreadPagePageTest extends ProofreadPageTestCase {

	public function testNewFromTitle() {
		$this->assertInstanceOf( 'ProofreadPagePage', ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'Test.djvu' ) ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 250, 'Test.djvu' );
		$page = ProofreadPagePage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetPageNumber() {
		$title = Title::makeTitle( 250, 'Test.djvu' );
		$page = ProofreadPagePage::newFromTitle( Title::makeTitle( 250, 'Test.djvu/1' ) );
		$this->assertEquals( 1, $page->getPageNumber() );

		$page = new ProofreadPagePage( $title, null, null );
		$this->assertEquals( null, $page->getPageNumber() );
	}

	public function testGetIndex() {
		$title = Title::makeTitle( 252, 'Test.djvu' );
		$index = ProofreadIndexPage::newFromTitle( $title );
		$page = new ProofreadPagePage( $title, null, $index );
		$this->assertEquals( $index, $page->getIndex() );
	}

	public function testGetContent() {
		$title = Title::makeTitle( 250, 'Test.djvu' );
		$index = ProofreadIndexPage::newFromTitle( $title );

		$pageContent = ProofreadPageContentTest::newContent( '', 'aaa' );
		$page = new ProofreadPagePage( $title, $pageContent, $index );
		$this->assertEquals( $pageContent, $page->getContent() );
	}

}
