<?php

namespace ProofreadPage\Pagination;

use OutOfBoundsException;
use ProofreadPage\PageNumberNotFoundException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination of a book based on a multipage file
 */
class FilePagination extends Pagination {

	/** @var Title */
	private $indexTitle;

	/**
	 * @var PageList representation of the <pagelist> tag
	 */
	private $pageList;

	/** @var int */
	private $numberOfPages;

	/**
	 * @var Title[] cache of build pages of the pagination as $pageNumber => $page array
	 */
	private $pages = [];

	/** @var int */
	private $pageNamespaceId;

	/**
	 * @param Title $indexTitle
	 * @param PageList $pageList representation of the <pagelist> tag that configure page numbers
	 * @param int $numberOfPages
	 * @param int $pageNamespaceId
	 */
	public function __construct(
		Title $indexTitle, PageList $pageList, int $numberOfPages, int $pageNamespaceId
	) {
		$this->indexTitle = $indexTitle;
		$this->pageList = $pageList;
		$this->numberOfPages = $numberOfPages;
		$this->pageNamespaceId = $pageNamespaceId;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageNumber( Title $pageTitle ): int {
		$parts = explode( '/', $pageTitle->getText() );
		if ( count( $parts ) !== 2 ) {
			throw new PageNotInPaginationException(
				$pageTitle->getFullText() . ' does not have page numbers'
			);
		}
		if ( $parts[0] !== $this->indexTitle->getText() ) {
			throw new PageNotInPaginationException(
				$pageTitle->getFullText() . ' does not belong to the pagination'
			);
		}
		$number = $pageTitle->getPageLanguage()->parseFormattedNumber( $parts[1] );
		if ( $number > 0 ) {
			// Valid page numbers are integer > 0.
			return (int)$number;
		} else {
			throw new PageNumberNotFoundException(
				$pageTitle->getFullText() . ' provides invalid page number ' . $number
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getDisplayedPageNumber( int $pageNumber ): PageNumber {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException(
				'There is no page number ' . $pageNumber . ' in the pagination.'
			);
		}
		return $this->pageList->getNumber( $pageNumber );
	}

	/**
	 * @inheritDoc
	 */
	public function getNumberOfPages(): int {
		return $this->numberOfPages;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageTitle( int $pageNumber ): Title {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException(
				'There is no page number ' . $pageNumber . ' in the pagination.'
			);
		}

		if ( !array_key_exists( $pageNumber, $this->pages ) ) {
			$this->pages[$pageNumber] = $this->buildPageTitle( $pageNumber );
		}

		return $this->pages[$pageNumber];
	}

	/**
	 * @param int $pageNumber
	 * @return Title
	 */
	private function buildPageTitle( int $pageNumber ): Title {
		$i18nNumber = $this->indexTitle->getPageLanguage()->formatNumNoSeparators( $pageNumber );
		$title = $this->buildPageTitleFromPageNumber( $i18nNumber );

		// fallback to arabic number
		if ( $i18nNumber !== (string)$pageNumber && !$title->exists() ) {
			$arabicTitle = $this->buildPageTitleFromPageNumber( (string)$pageNumber );
			if ( $arabicTitle->exists() ) {
				return $arabicTitle;
			}
		}

		return $title;
	}

	/**
	 * @param string $pageNumber
	 * @return Title
	 */
	private function buildPageTitleFromPageNumber( string $pageNumber ): Title {
		return Title::makeTitle(
			$this->pageNamespaceId,
			$this->indexTitle->getText() . '/' . $pageNumber
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function pageNumberExists( int $pageNumber ): bool {
		return $pageNumber >= 1 && $pageNumber <= $this->numberOfPages;
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @param int $count
	 * @return bool
	 */
	public static function isValidInterval( int $from, int $to, int $count ): bool {
		return $from >= 1 && $from <= $to && $to <= $count;
	}

}
