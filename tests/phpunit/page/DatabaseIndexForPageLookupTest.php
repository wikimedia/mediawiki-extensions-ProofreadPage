<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;
use RepoGroup;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\DatabaseIndexForPageLookup
 */
class DatabaseIndexForPageLookupTest extends ProofreadPageTestCase {

	public function testGetIndexForPage() {
		$repoGroupMock = $this->getMockBuilder( '\RepoGroup' )->disableOriginalConstructor()->getMock();
		$repoGroupMock->expects( $this->once() )
			->method( 'findFile' )
			->willReturn( $this->buildFileList()[0] );
		$lookup = new DatabaseIndexForPageLookup(
			$this->getIndexNamespaceId(),
			$repoGroupMock
		);
		$this->assertEquals(
			$this->newIndexPage( 'LoremIpsum.djvu' ),
			$lookup->getIndexForPage( $this->newPagePage( 'LoremIpsum.djvu/2' ) )
		);
	}

	public function testGetIndexForPageNotFound() {
		$lookup = new DatabaseIndexForPageLookup( $this->getIndexNamespaceId(), RepoGroup::singleton() );
		$this->assertNull( $lookup->getIndexForPage( $this->newPagePage( 'FooBar' ) ) );
	}
}
