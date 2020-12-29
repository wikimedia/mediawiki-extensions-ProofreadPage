<?php

namespace ProofreadPage\Pagination;

use File;
use OutOfBoundsException;
use ProofreadPage\Context;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination based on a single page file
 */
class SimpleFilePagination extends Pagination {

	/**
	 * @var Title
	 */
	private $indexTitle;

	/**
	 * @var PageList representation of the <pagelist> tag
	 */
	private $pageList;

	/**
	 * @var Title
	 */
	private $pageTitle;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @param Title $indexTitle
	 * @param PageList $pageList representation of the <pagelist> tag that configure page numbers
	 * @param File $file the pagination file
	 * @param Context $context the current context
	 */
	public function __construct(
		Title $indexTitle, PageList $pageList, File $file, Context $context
	) {
		$this->indexTitle = $indexTitle;
		$this->pageList = $pageList;
		$this->context = $context;
	}

	/**
	 * @inheritDoc
	 */
	public function getPageNumber( Title $pageTitle ) {
		$indexTitle = $this->context->getIndexForPageLookup()->getIndexForPageTitle( $pageTitle );
		if ( $indexTitle === null || !$this->indexTitle->equals( $indexTitle ) ) {
			throw new PageNotInPaginationException(
				$pageTitle->getFullText() . ' does not belong to the pagination'
			);
		}
		return 1;
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
		return $this->pageList->getNumber( $pageNumber );
	}

	/**
	 * @inheritDoc
	 */
	public function getNumberOfPages() {
		return 1;
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

		if ( !$this->pageTitle ) {
			$this->pageTitle = Title::makeTitle(
				$this->context->getPageNamespaceId(),
				$this->indexTitle->getText()
			);
		}

		return $this->pageTitle;
	}

	/**
	 * @inheritDoc
	 */
	protected function pageNumberExists( $pageNumber ) {
		return $pageNumber == 1;
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @param int $count
	 * @return bool
	 */
	public static function isValidInterval( $from, $to, $count ) {
		return $from == 1 && $to == 1 && $count == 1;
	}

}
