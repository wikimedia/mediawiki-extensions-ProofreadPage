<?php

namespace ProofreadPage\Pagination;

use File;
use OutOfBoundsException;
use ProofreadPage\Context;
use ProofreadPage\PageNumberNotFoundException;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Pagination of a book based on a multipage file
 */
class FilePagination extends Pagination {

	/**
	 * @var Title
	 */
	private $indexTitle;

	/**
	 * @var PageList representation of the <pagelist> tag
	 */
	private $pageList;

	/**
	 * @var integer
	 */
	private $numberOfPages = 0;

	/**
	 * @var Title[] cache of build pages of the pagination as $pageNumber => $page array
	 */
	private $pages = [];

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

		if ( $file->isMultipage() ) {
			$this->numberOfPages = $file->pageCount();
		}
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
		try {
			return $this->context->getFileProvider()->getPageNumberForPageTitle( $pageTitle );
		} catch ( PageNumberNotFoundException $e ) {
			throw new PageNotInPaginationException(
				$pageTitle->getFullText() . ' does not have page numbers'
			);
		}
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
		return $this->numberOfPages;
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

		if ( !array_key_exists( $pageNumber, $this->pages ) ) {
			$this->pages[$pageNumber] = $this->buildPageTitle( $pageNumber );
		}

		return $this->pages[$pageNumber];
	}

	/**
	 * @param integer $pageNumber
	 * @return Title
	 */
	private function buildPageTitle( $pageNumber ) {
		$i18nNumber = $this->indexTitle->getPageLanguage()->formatNum( $pageNumber, true );
		$title = $this->buildPageTitleFromPageNumber( $i18nNumber );

		// fallback to arabic number
		if ( $i18nNumber !== $pageNumber && !$title->exists() ) {
			$arabicTitle = $this->buildPageTitleFromPageNumber( $pageNumber );
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
	private function buildPageTitleFromPageNumber( $pageNumber ) {
		return Title::makeTitle(
			$this->context->getPageNamespaceId(),
			$this->indexTitle->getText() . '/' . $pageNumber
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function pageNumberExists( $pageNumber ) {
		return 1 <= $pageNumber && $pageNumber <= $this->numberOfPages;
	}
}
