<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use MediaHandler;
use MediaWiki\Title\Title;
use OutOfBoundsException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\FilePagination
 */
class FilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$context = $this->getContext( [
			'LoremIpsum.djvu/2' => Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' )
		] );
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			$context->getPageNamespaceId()
		);
		$this->assertSame( 2, $pagination->getPageNumber(
			$this->makeEnglishPagePageTitle( 'LoremIpsum.djvu/2' )
		) );
	}

	/**
	 * @dataProvider getPageNumberWithFailureProvider
	 */
	public function testGetPageNumberWithFailure(
		Pagination $pagination, string $titleText
	) {
		$this->expectException( InvalidArgumentException::class );
		$pagination->getPageNumber( $this->makeEnglishPagePageTitle( $titleText ) );
	}

	public static function getPageNumberWithFailureProvider() {
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);
		return [
			[
				$pagination,
				'Test.djvu/2'
			],
			[
				$pagination,
				'Test2.djvu/2'
			],
			[
				$pagination,
				'42.jpg'
			],
			[
				$pagination,
				'LoremIpsum.djvu'
			],
			[
				$pagination,
				'LoremIpsum.djvu/'
			],
			[
				$pagination,
				'LoremIpsum.djvu/foo'
			],
			[
				// decimal number
				$pagination,
				'LoremIpsum.djvu/1.2'
			],
			[
				// number larger than PHP_INT_MAX
				$pagination,
				'LoremIpsum.djvu/10000000000000000000000'
			],
		];
	}

	public function testGetDisplayedPageNumberBasic() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pageNumber = new PageNumber( 'TOC' );
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [ '1' => 'TOC' ] ),
			5,
			self::getPageNamespaceId()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetDisplayedPageNumberDefault() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pageNumber = new PageNumber( 1 );
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetDisplayedPageNumberWithFailure() {
		$pagination = new PagePagination( [], [] );
		$this->expectException( OutOfBoundsException::class );
		$pagination->getDisplayedPageNumber( 3 );
	}

	public function testGetNumberOfPages() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);
		$this->assertSame( 5, $pagination->getNumberOfPages() );
	}

	public function testGetPageTitle() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			$this->makeEnglishPagePageTitle( 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);
		$this->assertSame(
			'Page:LoremIpsum.djvu/2',
			$pagination->getPageTitle( 2 )->getFullText()
		);
	}

	public function testGetPageTitleWithFailure() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			Title::makeTitle( self::getIndexNamespaceId(), 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);
		$this->expectException( OutOfBoundsException::class );
		$pagination->getPageTitle( 42 );
	}

	public function testIterator() {
		if ( MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$pagination = new FilePagination(
			$this->makeEnglishPagePageTitle( 'LoremIpsum.djvu' ),
			new PageList( [] ),
			5,
			self::getPageNamespaceId()
		);

		$this->assertSame( 1, $pagination->key() );
		$pagination->next();
		$this->assertSame( 2, $pagination->key() );
		$this->assertTrue( $pagination->valid() );
		$this->assertSame(
			'Page:LoremIpsum.djvu/2',
			$pagination->current()->getFullText()
		);
		$pagination->rewind();
		$this->assertSame( 1, $pagination->key() );
		$this->assertSame(
			 'Page:LoremIpsum.djvu/1',
			$pagination->current()->getFullText()
		);
	}

	/**
	 * @dataProvider provideIntervals
	 */
	public function testIsValidInterval( $isValid, $from, $to, $count ) {
		$this->assertSame(
			$isValid,
			FilePagination::isValidInterval( $from, $to, $count )
		);
	}

	public static function provideIntervals() {
		return [
			[ true, 1, 3, 3 ],
			[ true, 1, 3, 5 ],
			[ false, 1, 3, 2 ],
			[ true, 1, 1, 1 ],
			[ false, 3, 1, 3 ],
			[ false, 0, 4, 5 ],
			[ false, 0, 0, 0 ],
		];
	}

}
