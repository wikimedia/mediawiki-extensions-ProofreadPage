<?php

namespace ProofreadPage\Pagination;

use File;
use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadIndexPageTest;
use ProofreadPagePage;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\FilePagination
 */
class FilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index,
			new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( 2, $pagination->getPageNumber(
			new ProofreadPagePage( Title::makeTitle( 250, 'LoremIpsum.djvu/2' ), $index )
		) );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider getPageNumberWithFailureProvider
	 */
	public function testGetPageNumberWithFailure( Pagination $pagination, ProofreadPagePage $page ) {
		$pagination->getPageNumber( $page );
	}

	public function getPageNumberWithFailureProvider() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		return array(
			array(
				$pagination,
				new ProofreadPagePage( Title::makeTitle( 250, 'Test.djvu/2' ) )
			),
			array(
				$pagination,
				new ProofreadPagePage( Title::makeTitle( 250, 'Test2.djvu/2' ) )
			),
			array(
				$pagination,
				new ProofreadPagePage( Title::makeTitle( 250, '42.jpg' ), $index )
			),
		);
	}

	public function testGetDisplayedPageNumberBasic() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new FilePagination(
			$index, new PageList( array( '1' => 'TOC' ) ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetDisplayedPageNumberDefault() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pageNumber = new PageNumber( 1 );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetDisplayedPageNumberWithFailure() {
		$index = ProofreadIndexPageTest::newIndexPage();
		$pagination = new PagePagination( $index, array(), array() );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( 5, $pagination->getNumberOfPages() );
	}

	public function testGetPage() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$page = new ProofreadPagePage( Title::makeTitle( 250, 'LoremIpsum.djvu/2' ), $index );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$this->assertEquals( $page, $pagination->getPage( 2 ) );
	}

	/**
	 * @expectedException OutOfBoundsException
	 */
	public function testGetPageWithFailure() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
			$this->getContext()
		);
		$pagination->getPage( 42 );
	}

	public function testIterator() {
		$index = ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu' );
		$page1 = new ProofreadPagePage( Title::makeTitle( 250, 'LoremIpsum.djvu/1' ), $index );
		$page2 = new ProofreadPagePage( Title::makeTitle( 250, 'LoremIpsum.djvu/2' ), $index );
		$pagination = new FilePagination(
			$index, new PageList( array() ),
			$this->getContext()->getFileProvider()->getFileFromTitle( Title::makeTitle( NS_MEDIA, 'LoremIpsum.djvu' ) ),
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