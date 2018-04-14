<?php

namespace ProofreadPage\Pagination;

use Iterator;
use OutOfBoundsException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination of a book
 */
abstract class Pagination implements Iterator {

	/**
	 * @var integer position of the iterator
	 */
	private $position = 1;

	/**
	 * Returns the internal page number
	 *
	 * @param Title $pageTitle
	 * @return integer
	 * @throws PageNotInPaginationException
	 */
	abstract public function getPageNumber( Title $pageTitle );

	/**
	 * Returns the page number as it should be displayed from an internal page number
	 *
	 * @param int $pageNumber
	 * @return PageNumber
	 * @throws OutOfBoundsException
	 */
	abstract public function getDisplayedPageNumber( $pageNumber );

	/**
	 * Returns the number of pages
	 *
	 * @return integer
	 */
	abstract public function getNumberOfPages();

	/**
	 * Returns the page number $pageNumber of the book
	 *
	 * @param int $pageNumber page number
	 * @return Title
	 * @throws OutOfBoundsException
	 */
	abstract public function getPageTitle( $pageNumber );

	/**
	 * Returns if a page number $pageNumber exits
	 *
	 * @param int $pageNumber page number
	 * @return boolean
	 */
	abstract protected function pageNumberExists( $pageNumber );

	/**
	 * @inheritDoc
	 */
	public function rewind() {
		$this->position = 1; // pages numbers starts with 1
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		$this->position++;
	}

	/**
	 * @inheritDoc
	 *
	 * @return Title
	 */
	public function current() {
		return $this->getPageTitle( $this->position );
	}

	/**
	 * @inheritDoc
	 */
	public function valid() {
		return $this->pageNumberExists( $this->position );
	}
}
