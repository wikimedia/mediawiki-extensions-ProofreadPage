<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;
use RepoGroup;
use Title;

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
			Title::makeTitle( $this->getIndexNamespaceId(),  'LoremIpsum.djvu' ),
			$lookup->getIndexForPageTitle(
				Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.djvu/2' )
			)
		);
	}

	public function testGetIndexForPageNotFound() {
		$lookup = new DatabaseIndexForPageLookup( $this->getIndexNamespaceId(), RepoGroup::singleton() );
		$this->assertNull( $lookup->getIndexForPageTitle(
			Title::makeTitle( $this->getPageNamespaceId(), 'FooBar' )
		) );
	}
}
