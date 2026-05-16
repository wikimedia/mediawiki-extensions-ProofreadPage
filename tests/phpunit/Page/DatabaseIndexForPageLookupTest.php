<?php

namespace ProofreadPage\Page;

use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Media\MediaHandler;
use MediaWiki\Title\Title;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @group Database
 * @covers \ProofreadPage\Page\DatabaseIndexForPageLookup
 */
class DatabaseIndexForPageLookupTest extends ProofreadPageTestCase {

	public function testGetIndexForPage() {
		// Skip when there is no support for DjVu files
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}

		$repoGroupMock = $this->createMock( RepoGroup::class );
		$repoGroupMock->expects( $this->once() )
			->method( 'findFile' )
			->willReturn( self::buildFileList()[0] );
		$lookup = new DatabaseIndexForPageLookup(
			self::getIndexNamespaceId(),
			$repoGroupMock
		);
		$this->assertEquals(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			$lookup->getIndexForPageTitle(
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.djvu/2' )
			)
		);
	}

	public function testGetIndexForSinglePageFile() {
		$repoGroupMock = $this->createMock( RepoGroup::class );
		$repoGroupMock->expects( $this->once() )
			->method( 'findFile' )
			->willReturn( self::buildFileList()[2] );
		$lookup = new DatabaseIndexForPageLookup(
			self::getIndexNamespaceId(),
			$repoGroupMock
		);

		$this->assertEquals(
			Title::makeTitle( self::getIndexNamespaceId(), 'Test.jpg' ),
			$lookup->getIndexForPageTitle(
				Title::makeTitle( self::getPageNamespaceId(), 'Test.jpg' )
			)
		);
	}

	public function testGetIndexForPageNotFound() {
		$lookup = new DatabaseIndexForPageLookup(
			self::getIndexNamespaceId(),
			$this->getServiceContainer()->getRepoGroup()
		);
		$this->assertNull( $lookup->getIndexForPageTitle(
			Title::makeTitle( self::getPageNamespaceId(), 'FooBar' )
		) );
	}
}
