<?php

/**
 * @group ProofreadPage
 * @covers ProofreadIndexPage
 */
class ProofreadIndexPageTest extends ProofreadPageTestCase {

	public function testEquals() {
		$page = $this->newIndexPage( 'Test.djvu' );
		$page2 = $this->newIndexPage( 'Test.djvu' );
		$page3 = $this->newIndexPage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( $this->getIndexNamespaceId(), 'Test.djvu' );
		$page = ProofreadIndexPage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}
}
