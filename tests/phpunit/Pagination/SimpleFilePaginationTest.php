<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use MediaWiki\Title\Title;
use OutOfBoundsException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\SimpleFilePagination
 */
class SimpleFilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		$this->assertSame( 1, $pagination->getPageNumber(
			Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.jpg' )
		) );
	}

	/**
	 * @dataProvider getPageNumberWithFailureProvider
	 */
	public function testGetPageNumberWithFailure(
		Pagination $pagination, Title $pageTitle
	) {
		$this->expectException( InvalidArgumentException::class );
		$pagination->getPageNumber( $pageTitle );
	}

	public static function getPageNumberWithFailureProvider() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		return [
			[
				$pagination,
				Title::makeTitle( self::getPageNamespaceId(), 'Test.jpg' )
			],
			[
				$pagination,
				Title::makeTitle( self::getPageNamespaceId(), 'Test.jpg/2' )
			],
			[
				$pagination,
				Title::makeTitle( self::getPageNamespaceId(), 'Test2.jpg' )
			],
			[
				$pagination,
				Title::makeTitle( self::getPageNamespaceId(), 'LoremIpsum.djvu' )
			],
		];
	}

	public function testGetDisplayedPageNumber() {
		$pageNumber = new PageNumber( 1 );
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetNumberOfPages() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		$this->assertSame( 1, $pagination->getNumberOfPages() );
	}

	public function testGetPageTitle() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		$this->assertSame(
			'Page:LoremIpsum.jpg',
			$pagination->getPageTitle( 1 )->getFullText()
		);
	}

	public function testGetPageTitleWithFailure() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			self::getPageNamespaceId()
		);
		$this->expectException( OutOfBoundsException::class );
		$pagination->getPageTitle( 42 );
	}

}
