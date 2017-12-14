<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use MediaHandler;
use OutOfBoundsException;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\FilePagination
 */
class FilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$context = $this->getContext( [
			'LoremIpsum.djvu/2' => Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' )
		] );
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$context->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$context
		);
		$this->assertEquals( 2, $pagination->getPageNumber(
			Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.djvu/2' )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider getPageNumberWithFailureProvider
	 */
	public function testGetPageNumberWithFailure(
		Pagination $pagination, Title $pageTitle
	) {
		$pagination->getPageNumber( $pageTitle );
	}

	public function getPageNumberWithFailureProvider() {
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		return [
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'Test.djvu/2' )
			],
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'Test2.djvu/2' )
			],
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), '42.jpg' )
			],
		];
	}

	public function testGetDisplayedPageNumberBasic() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [ '1' => 'TOC' ] ),
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
		$pageNumber = new PageNumber( 1 );
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
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
		$pagination = new PagePagination( [], [] );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals( 5, $pagination->getNumberOfPages() );
	}

	public function testGetPageTitle() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$this->assertEquals(
			'Page:LoremIpsum.djvu/2',
			$pagination->getPageTitle( 2 )->getFullText()
		);
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageTitleWithFailure() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);
		$pagination->getPageTitle( 42 );
	}

	public function testIterator() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' )
			),
			$this->getContext()
		);

		$this->assertEquals( 1, $pagination->key() );
		$pagination->next();
		$this->assertEquals( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$this->assertEquals(
			'Page:LoremIpsum.djvu/2',
			$pagination->current()->getFullText()
		);
		$pagination->rewind();
		$this->assertEquals( 1, $pagination->key() );
		$this->assertEquals(
			 'Page:LoremIpsum.djvu/1',
			$pagination->current()->getFullText()
		);
	}
}
