<?php
namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination of a book based on a set of independants pages
 */
class PagePagination extends Pagination {

	/**
	 * @var Title[]
	 */
	private $pages = [];

	/**
	 * @var PageNumber[]
	 */
	private $pageNumbers = [];

	/**
	 * @param Title[] $pages the ordered pages
	 * @param PageNumber[] $pageNumbers with $pageNumbers[i] the page number of the page $pages[i]
	 * @throws InvalidArgumentException
	 */
	public function __construct( array $pages, array $pageNumbers ) {
		if ( count( $pages ) !== count( $pageNumbers ) ) {
			throw new InvalidArgumentException(
				'The number of page numbers is not the same as the number of pages'
			);
		}

		$this->pages = $pages;
		$this->pageNumbers = $pageNumbers;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageNumber( Title $pageTitle ) {
		foreach ( $this->pages as $i => $pageTitle2 ) {
			if ( $pageTitle->equals( $pageTitle2 ) ) {
				return $i + 1;
			}
		}
		throw new PageNotInPaginationException( '$page does not belong to the pagination' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDisplayedPageNumber( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException(
				'There is no page number ' . $pageNumber . ' in the pagination.'
			);
		}
		return $this->pageNumbers[$pageNumber - 1];
	}

	/**
	 * @inheritDoc
	 */
	public function getNumberOfPages() {
		return count( $this->pages );
	}

	/**
	 * @inheritDoc
	 */
	public function getPageTitle( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException(
				'There is no page number ' . $pageNumber . ' in the pagination.'
			);
		}
		return $this->pages[$pageNumber - 1];
	}

	/**
	 * @inheritDoc
	 */
	protected function pageNumberExists( $pageNumber ) {
		return array_key_exists( $pageNumber - 1, $this->pages );
	}
}
