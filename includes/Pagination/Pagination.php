<?php

namespace ProofreadPage\Pagination;

use Iterator;
use OutOfBoundsException;
use ProofreadIndexPage;
use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 *
 * Pagination of a book
 */
abstract class Pagination implements Iterator {

	/**
	 * @var ProofreadIndexPage
	 */
	protected $index;

	/**
	 * @var integer position of the iterator
	 */
	private $position = 1;

	/**
	 * @param ProofreadIndexPage $index
	 */
	public function __construct( ProofreadIndexPage $index ) {
		$this->index = $index;
	}

	/**
	 * Returns the index page
	 *
	 * @return ProofreadIndexPage
	 */
	public function getIndex() {
		return $this->index;
	}

	/**
	 * Returns the internal page number
	 *
	 * @param ProofreadPagePage $page
	 * @return integer
	 * @throws PageNotInPaginationException
	 */
	public abstract function getPageNumber( ProofreadPagePage $page );

	/**
	 * Returns the page number as it should be displayed from an internal page number
	 *
	 * @param integer $pageNumber
	 * @return PageNumber
	 * @throws OutOfBoundsException
	 */
	public abstract function getDisplayedPageNumber( $pageNumber );

	/**
	 * Returns the number of pages
	 *
	 * @return integer
	 */
	public abstract function getNumberOfPages();

	/**
	 * Returns the page number $pageNumber of the book
	 *
	 * @param integer $pageNumber page number
	 * @return ProofreadPagePage
	 * @throws OutOfBoundsException
	 */
	public abstract function getPage( $pageNumber );

	/**
	 * Returns if a page number $pageNumber exits
	 *
	 * @param integer $pageNumber page number
	 * @return boolean
	 */
	protected abstract function pageNumberExists( $pageNumber );

	/**
	 * @see Iterator::rewind
	 */
	public function rewind() {
		$this->position = 1; //pages numbers starts with 1
	}

	/**
	 * @see Iterator::key
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * @see Iterator::next
	 */
	public function next() {
		$this->position++;
	}

	/**
	 * @see Iterator::current
	 *
	 * @return ProofreadPagePage
	 */
	public function current() {
		return $this->getPage( $this->position );
	}

	/**
	 * @see Iterator::valid
	 */
	public function valid() {
		return $this->pageNumberExists( $this->position );
	}
}