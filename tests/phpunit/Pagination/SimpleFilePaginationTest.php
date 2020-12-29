<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Pagination\SimpleFilePagination
 */
class SimpleFilePaginationTest extends ProofreadPageTestCase {

	public function testGetPageNumber() {
		$context = $this->getContext( [
			'LoremIpsum.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' )
		] );
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$context->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$context
		);
		$this->assertSame( 1, $pagination->getPageNumber(
			Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.jpg' )
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

	public function getPageNumberWithFailureProvider() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$this->getContext()
		);
		return [
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' )
			],
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg/2' )
			],
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'Test2.jpg' )
			],
			[
				$pagination,
				Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.djvu' )
			],
		];
	}

	public function testGetDisplayedPageNumber() {
		$pageNumber = new PageNumber( 1 );
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$this->getContext()
		);
		$this->assertEquals( $pageNumber, $pagination->getDisplayedPageNumber( 1 ) );
	}

	public function testGetNumberOfPages() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$this->getContext()
		);
		$this->assertSame( 1, $pagination->getNumberOfPages() );
	}

	public function testGetPageTitle() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$this->getContext()
		);
		$this->assertSame(
			'Page:LoremIpsum.jpg',
			$pagination->getPageTitle( 1 )->getFullText()
		);
	}

	public function testGetPageTitleWithFailure() {
		$pagination = new SimpleFilePagination(
			Title::makeTitle( $this->getIndexNamespaceId(), 'LoremIpsum.jpg' ),
			new PageList( [] ),
			$this->getContext()->getFileProvider()->getFileFromTitle(
				Title::makeTitle( NS_MEDIA, 'LoremIpsum.jpg' )
			),
			$this->getContext()
		);
		$this->expectException( OutOfBoundsException::class );
		$pagination->getPageTitle( 42 );
	}

}
