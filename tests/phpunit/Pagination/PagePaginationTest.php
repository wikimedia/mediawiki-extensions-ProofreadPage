<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\PagePagination
 */
class PagePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$index = $this->newIndexPage();
		$page = $this->newPagePage( 'Test 2.tiff' );
		$pagination = new PagePagination(
			$index,
			[
				$this->newPagePage( 'Test 1.jpg' ),
				$page,
				$this->newPagePage( 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( 2, $pagination->getPageNumber(
			$this->newPagePage( 'Test 2.tiff' )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testGetPageNumberWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getPageNumber(
			$this->newPagePage( 'Test 2.tiff' )
		);
	}

	public function testGetDisplayedPageNumber() {
		$index = $this->newIndexPage();
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new PagePagination(
			$index,
			[ $this->newPagePage( 'Test 1.jpg' ) ],
			[ $pageNumber ]
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
		$index = $this->newIndexPage();
		$pagination = new PagePagination(
			$index,
			[
				$this->newPagePage( 'Test 1.jpg' ),
				$this->newPagePage( 'Test 2.jpg' ),
				$this->newPagePage( 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( 3, $pagination->getNumberOfPages() );
	}

	public function testGetPage() {
		$index = $this->newIndexPage();
		$page = $this->newPagePage( 'Test 1.jpg' );
		$pagination = new PagePagination(
			$index,
			[
				$this->newPagePage( 'Test 1.jpg' ),
				$this->newPagePage( 'Test 2.tiff' ),
				$this->newPagePage( 'Test:3.png' )
			],
			[
				new PageNumber( 'TOC' ),
				new PageNumber( '1' ),
				new PageNumber( '2' )
			]
		);
		$this->assertEquals( $page, $pagination->getPage( 1 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageWithFailure() {
		$index = $this->newIndexPage();
		$pagination = new PagePagination( $index, [], [] );
		$pagination->getPage( 3 );
	}

	public function testIterator() {
		$index = $this->newIndexPage();
		$page1 = $this->newPagePage( 'Test 1.jpg' );
		$page2 = $this->newPagePage( 'Test 2.jpg' );
		$pagination = new PagePagination(
			$index,
			[ $page1, $page2 ],
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
		$this->assertEquals( $page1, $pagination->current() );
	}
}
