<?php
namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use OutOfBoundsException;
use ProofreadIndexPage;
use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 *
 * Pagination of a book based on a set of independants pages
 */
class PagePagination extends Pagination {

	/**
	 * @var ProofreadPagePage[]
	 */
	private $pages = array();

	/**
	 * @var PageNumber[]
	 */
	private $pageNumbers = array();

	/**
	 * @param ProofreadIndexPage $index
	 * @param ProofreadPagePage[] $pages the ordered pages
	 * @param PageNumber[] $pageNumbers with $pageNumbers[i] the page number of the page $pages[i]
	 * @throws InvalidArgumentException
	 */
	public function __construct( ProofreadIndexPage $index, array $pages, array $pageNumbers ) {
		parent::__construct( $index );

		if ( count( $pages ) !== count( $pageNumbers ) ) {
			throw new InvalidArgumentException( 'The number of page numbers is not the same as the number of pages' );
		}

		$this->pages = $pages;
		$this->pageNumbers = $pageNumbers;
	}

	/**
	 * @see ProofreadPagination::getPageNumber
	 */
	public function getPageNumber( ProofreadPagePage $page ) {
		foreach ( $this->pages as $i => $page2 ) {
			if ( $page->equals( $page2 ) ) {
				return $i + 1;
			}
		}
		throw new PageNotInPaginationException( '$page does not belong to the pagination' );
	}

	/**
	 * @see ProofreadPagination::getDisplayedPageNumber
	 */
	public function getDisplayedPageNumber( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException( 'There is no page number ' . $pageNumber . ' in the pagination.' );
		}
		return $this->pageNumbers[$pageNumber - 1];
	}

	/**
	 * @see ProofreadPagination::getNumberOfPages
	 */
	public function getNumberOfPages() {
		return count( $this->pages );
	}

	/**
	 * @see ProofreadPagination::getPage
	 */
	public function getPage( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException( 'There is no page number ' . $pageNumber . ' in the pagination.' );
		}
		return $this->pages[$pageNumber - 1];
	}

	/**
	 * @see ProofreadPagination::pageNumExists
	 */
	protected function pageNumberExists( $pageNumber ) {
		return array_key_exists( $pageNumber - 1, $this->pages );
	}
}