<?php

namespace ProofreadPage\Page;

use MediaWiki\Title\Title;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @group Database
 * @covers \ProofreadPage\Page\DatabaseIndexForPageLookup
 */
class DatabaseIndexForPageLookupTest extends ProofreadPageTestCase {

	public function testGetIndexForPage() {
		$repoGroupMock = $this->createMock( \RepoGroup::class );
		$repoGroupMock->expects( $this->once() )
			->method( 'findFile' )
			->willReturn( $this->buildFileList()[0] );
		$lookup = new DatabaseIndexForPageLookup(
			$this->getIndexNamespaceId(),
			$repoGroupMock
		);
		$this->assertEquals(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			$lookup->getIndexForPageTitle(
				Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.djvu/2' )
			)
		);
	}

	public function testGetIndexForSinglePageFile() {
		$repoGroupMock = $this->createMock( \RepoGroup::class );
		$repoGroupMock->expects( $this->once() )
			->method( 'findFile' )
			->willReturn( $this->buildFileList()[2] );
		$lookup = new DatabaseIndexForPageLookup(
			$this->getIndexNamespaceId(),
			$repoGroupMock
		);

		$this->assertEquals(
			Title::makeTitle( $this->getIndexNamespaceId(), 'Test.jpg' ),
			$lookup->getIndexForPageTitle(
				Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' )
			)
		);
	}

	public function testGetIndexForPageNotFound() {
		$lookup = new DatabaseIndexForPageLookup(
			$this->getIndexNamespaceId(),
			$this->getServiceContainer()->getRepoGroup()
		);
		$this->assertNull( $lookup->getIndexForPageTitle(
			Title::makeTitle( $this->getPageNamespaceId(), 'FooBar' )
		) );
	}
}
