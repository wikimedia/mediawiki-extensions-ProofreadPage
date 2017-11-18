<?php

/**
 * @group ProofreadPage
 * @covers ProofreadPagePage
 */
class ProofreadPagePageTest extends ProofreadPageTestCase {

	public function testEquals() {
		$page = $this->newPagePage( 'Test.djvu' );
		$page2 = $this->newPagePage( 'Test.djvu' );
		$page3 = $this->newPagePage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( $this->getPageNamespaceId(), 'Test.djvu' );
		$page = $this->newPagePage( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetPageNumber() {
		$this->assertEquals( 1, $this->newPagePage( 'Test.djvu/1' )->getPageNumber() );

		$this->assertNull( $this->newPagePage( 'Test.djvu' )->getPageNumber() );
	}
}
