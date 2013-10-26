<?php

namespace ProofreadPage\Pagination;

use File;
use OutOfBoundsException;
use ProofreadIndexPage;
use ProofreadPage\Context;
use ProofreadPagePage;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * Pagination of a book based on a multipage file
 */
class FilePagination extends Pagination {

	/**
	 * @var PageList representation of the <pagelist> tag
	 */
	private $pageList;

	/**
	 * @var integer
	 */
	private $numberOfPages = 0;

	/**
	 * @var ProofreadPagePage[] cache of build pages of the pagination as $pageNumber => $page array
	 */
	private $pages = array();

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @param ProofreadIndexPage $index
	 * @param PageList $pageList representation of the <pagelist> tag that configure page numbers
	 * @param File $file the pagination file
	 * @param Context $context the current context
	 */
	public function __construct( ProofreadIndexPage $index, PageList $pageList, File $file, Context $context ) {
		parent::__construct( $index );

		$this->pageList = $pageList;
		$this->context = $context;

		if ( $file->isMultipage() ) {
			$this->numberOfPages = $file->pageCount();
		}
	}

	/**
	 * @see ProofreadPagination::getPageNumber
	 */
	public function getPageNumber( ProofreadPagePage $page ) {
		$pageNumber = $page->getPageNumber();
		$index = $page->getIndex();
		if ( $pageNumber === null || $index === false || !$this->index->equals( $index ) ) {
			throw new PageNotInPaginationException( '$page does not belong to the pagination' );
		}
		return $pageNumber;
	}

	/**
	 * @see ProofreadPagination::getDisplayedPageNumber
	 */
	public function getDisplayedPageNumber( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException( 'There is no page number ' . $pageNumber . ' in the pagination.' );
		}
		return $this->pageList->getNumber( $pageNumber );
	}

	/**
	 * @see ProofreadPagination::getNumberOfPages
	 */
	public function getNumberOfPages() {
		return $this->numberOfPages;
	}

	/**
	 * @see ProofreadPagination::getPage
	 */
	public function getPage( $pageNumber ) {
		if ( !$this->pageNumberExists( $pageNumber ) ) {
			throw new OutOfBoundsException( 'There is no page number ' . $pageNumber . ' in the pagination.' );
		}

		if ( !array_key_exists( $pageNumber, $this->pages ) ) {
			$this->pages[$pageNumber] = $this->buildPagePage( $pageNumber );
		}

		return $this->pages[$pageNumber];
	}

	/**
	 * @param integer $pageNumber
	 * @return ProofreadPagePage
	 */
	private function buildPagePage( $pageNumber ) {
		$i18nNumber = $this->index->getTitle()->getPageLanguage()->formatNum( $pageNumber, true );
		$title = $this->buildPageTitleFromPageNumber( $i18nNumber );

		//fallback to arabic number
		if ( $i18nNumber !== $pageNumber && !$title->exists() ) {
			$arabicTitle = $this->buildPageTitleFromPageNumber( $pageNumber );
			if ( $arabicTitle->exists() ) {
				return new ProofreadPagePage( $arabicTitle, $this->index );
			}
		}

		return new ProofreadPagePage( $title, $this->index );
	}

	/**
	 * @param string $pageNumber
	 * @return Title
	 */
	private function buildPageTitleFromPageNumber( $pageNumber ) {
		return Title::makeTitle(
			$this->context->getPageNamespaceId(),
			$this->index->getTitle()->getText() . '/' . $pageNumber
		);
	}

	/**
	 * @see ProofreadPagination::pageNumExists
	 */
	protected function pageNumberExists( $pageNumber ) {
		return 1 <= $pageNumber && $pageNumber <= $this->numberOfPages;
	}
}