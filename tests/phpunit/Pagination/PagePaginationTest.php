<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use MediaWiki\Title\Title;
use OutOfBoundsException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PagePagination
 */
class PagePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$pagination = new PagePagination(
			[
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.tiff' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertSame( 2, $pagination->getPageNumber(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.tiff' )
		) );
	}

	public function testGetPageNumberWithFailure() {
		$pagination = new PagePagination( [], [] );
		$this->expectException( InvalidArgumentException::class );
		$pagination->getPageNumber(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.tiff' )
		);
	}

	public function testGetDisplayedPageNumber() {
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new PagePagination(
			[ Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ) ],
			[ $pageNumber ]
		);
		$this->assertSame( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetDisplayedPageNumberWithFailure() {
		$pagination = new PagePagination( [], [] );
		$this->expectException( OutOfBoundsException::class );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		$pagination = new PagePagination(
			[
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.jpg' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertSame( 3, $pagination->getNumberOfPages() );
	}

	public function testGetPageTitle() {
		$pagination = new PagePagination(
			[
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.tiff' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
			$pagination->getPageTitle( 1 )
		);
	}

	public function testGetPageTitleWithFailure() {
		$pagination = new PagePagination( [], [] );
		$this->expectException( OutOfBoundsException::class );
		$pagination->getPageTitle( 3 );
	}

	public function testIterator() {
		$pagination = new PagePagination(
			[
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ),
				Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.jpg' )
			],
			[ new PageNumber( '1' ), new PageNumber( '2' ) ]
		);

		$this->assertSame( 1, $pagination->key() );
		$pagination->next();
		$this->assertSame( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$pagination->next();
		$this->assertFalse( $pagination->valid() );
		$pagination->rewind();
		$this->assertSame( 1, $pagination->key() );
		$this->assertEquals(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ), $pagination->current()
		);
	}
}
