<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use MediaHandler;
use OutOfBoundsException;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\FilePagination
 */
class FilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index,
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( 2, $pagination->getPageNumber(
			$this->newPagePage( 'LoremIpsum.djvu/2', $index )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider getPageNumberWithFailureProvider
	 */
	public function testGetPageNumberWithFailure(
		Pagination $pagination, ProofreadPagePage $page
	) {
		$pagination->getPageNumber( $page );
	}

	public function getPageNumberWithFailureProvider() {
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		return [
			[
				$pagination,
				$this->newPagePage( 'Test.djvu/2' )
			],
			[
				$pagination,
				$this->newPagePage( 'Test2.djvu/2' )
			],
			[
				$pagination,
				$this->newPagePage( '42.jpg' )
			],
		];
	}

	public function testGetDisplayedPageNumberBasic() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new FilePagination(
			$index, new PageList( [ '1' => 'TOC' ] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetDisplayedPageNumberDefault() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pageNumber = new PageNumber( 1 );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetDisplayedPageNumberWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( 5, $pagination->getNumberOfPages() );
	}

	public function testGetPage() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$page = $this->newPagePage( 'LoremIpsum.djvu/2', $index );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( $page, $pagination->getPage( 2 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageWithFailure() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$pagination->getPage( 42 );
	}

	public function testIterator() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$index = $this->newIndexPage( 'LoremIpsum.djvu' );
		$page1 = $this->newPagePage( 'LoremIpsum.djvu/1', $index );
		$page2 = $this->newPagePage( 'LoremIpsum.djvu/2', $index );
		$pagination = new FilePagination(
			$index, new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);

		$this->assertEquals( 1, $pagination->key() );
		$pagination->next();
		$this->assertEquals( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$this->assertEquals( $page2, $pagination->current() );
		$pagination->rewind();
		$this->assertEquals( 1, $pagination->key() );
		$this->assertEquals( $page1, $pagination->current() );
	}
}
