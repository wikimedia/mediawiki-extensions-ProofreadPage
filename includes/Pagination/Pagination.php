<?php

namespace ProofreadPage\Pagination;

use Iterator;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use OutOfBoundsException;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination of a book
 */
abstract class Pagination implements Iterator {

	/**
	 * Have we already prefetched the page list? If so, don't do it again.
	 *
	 * @var bool
	 */
	private $linksCached = false;

	/**
	 * @var int position of the iterator
	 */
	private $position = 1;

	/**
	 * Returns the internal page number
	 *
	 * @param Title $pageTitle
	 * @return int
	 * @throws PageNotInPaginationException
	 */
	abstract public function getPageNumber( Title $pageTitle ): int;

	/**
	 * Returns the page number as it should be displayed from an internal page number
	 *
	 * @param int $pageNumber
	 * @return PageNumber
	 * @throws OutOfBoundsException
	 */
	abstract public function getDisplayedPageNumber( int $pageNumber ): PageNumber;

	/**
	 * Returns the number of pages
	 *
	 * @return int
	 */
	abstract public function getNumberOfPages(): int;

	/**
	 * Returns the page number $pageNumber of the book
	 *
	 * @param int $pageNumber
	 * @return Title
	 * @throws OutOfBoundsException
	 */
	abstract public function getPageTitle( int $pageNumber ): Title;

	/**
	 * Returns if a page number $pageNumber exists
	 *
	 * @param int $pageNumber
	 * @return bool
	 */
	abstract protected function pageNumberExists( int $pageNumber ): bool;

	/**
	 * @inheritDoc
	 */
	public function rewind(): void {
		// pages numbers starts with 1
		$this->position = 1;
	}

	/**
	 * @inheritDoc
	 */
	public function key(): int {
		return $this->position;
	}

	/**
	 * @inheritDoc
	 */
	public function next(): void {
		$this->position++;
	}

	/**
	 * @inheritDoc
	 *
	 * @return Title
	 */
	public function current(): Title {
		return $this->getPageTitle( $this->position );
	}

	/**
	 * @inheritDoc
	 */
	public function valid(): bool {
		return $this->pageNumberExists( $this->position );
	}

	/**
	 * Prefetch links for the index, so the subsequent getArticleID lookups
	 * can be cached for faster access
	 */
	public function prefetchPageLinks(): void {
		if ( !$this->linksCached ) {
			$batch = MediaWikiServices::getInstance()
				->getLinkBatchFactory()->newLinkBatch( $this );
			$batch->setCaller( __METHOD__ );
			$batch->execute();
			$this->linksCached = true;
		}
	}
}
