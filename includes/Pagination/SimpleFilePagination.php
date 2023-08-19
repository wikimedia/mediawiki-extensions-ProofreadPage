<?php

namespace ProofreadPage\Pagination;

use MediaWiki\Title\Title;
use OutOfBoundsException;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination based on a single page file
 */
class SimpleFilePagination extends Pagination {

	/** @var Title */
	private $indexTitle;

	/**
	 * @var PageList representation of the <pagelist> tag
	 */
	private $pageList;

	/** @var Title */
	private $pageTitle;

	/**
	 * @param Title $indexTitle
	 * @param PageList $pageList representation of the <pagelist> tag that configure page numbers
	 * @param int $pageNamespaceId
	 */
	public function __construct(
		Title $indexTitle, PageList $pageList, int $pageNamespaceId
	) {
		$this->indexTitle = $indexTitle;
		$this->pageTitle = Title::makeTitle( $pageNamespaceId, $this->indexTitle->getText() );
		$this->pageList = $pageList;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageNumber( Title $pageTitle ): int {
		if ( !$pageTitle->equals( $this->pageTitle ) ) {
			throw new PageNotInPaginationException(
				$pageTitle->getFullText() . ' does not belong to the pagination'
			);
		}
		return 1;
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
		return 1;
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
		return $this->pageTitle;
	}

	/**
	 * @inheritDoc
	 */
	protected function pageNumberExists( int $pageNumber ): bool {
		return $pageNumber === 1;
	}

}
