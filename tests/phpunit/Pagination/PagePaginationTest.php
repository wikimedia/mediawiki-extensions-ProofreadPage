<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadPageTestCase;
use Title;

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
		$this->assertEquals( 2, $pagination->getPageNumber(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 2.tiff' )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetPageNumberWithFailure() {
		$pagination = new PagePagination( [], [] );
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
		$this->assertEquals( 3, $pagination->getNumberOfPages() );
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

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageTitleWithFailure() {
		$pagination = new PagePagination( [], [] );
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

		$this->assertEquals( 1, $pagination->key() );
		$pagination->next();
		$this->assertEquals( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$pagination->next();
		$this->assertFalse( $pagination->valid() );
		$pagination->rewind();
		$this->assertEquals( 1, $pagination->key() );
		$this->assertEquals(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test 1.jpg' ), $pagination->current()
		);
	}
}
