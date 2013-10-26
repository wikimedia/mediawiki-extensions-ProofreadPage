<?php

namespace ProofreadPage\Pagination;

use ProofreadIndexPage;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 */
class PaginationFactory {

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var Pagination[]
	 */
	private $paginations = array();

	public function __construct( Context $context ) {
		$this->context = $context;
	}

	/**
	 * @param ProofreadIndexPage $indexPage
	 * @return Pagination
	 */
	public function getPaginationForIndexPage( ProofreadIndexPage $indexPage ) {
		$key = $indexPage->getTitle()->getDBkey();

		if ( !array_key_exists( $key, $this->paginations ) ) {
			$this->paginations[$key] = $this->buildPaginationForIndexPage( $indexPage );
		}

		return $this->paginations[$key];
	}

	/**
	 * @param ProofreadIndexPage $indexPage
	 * @return Pagination
	 */
	private function buildPaginationForIndexPage( ProofreadIndexPage $indexPage ) {
		try {
			$file = $this->context->getFileProvider()->getForIndexPage( $indexPage );
		} catch( FileNotFoundException $e ) {
			$file = false;
		}

		//check if it is using pagelist
		$pagelist = $indexPage->getPagelistTagContent();
		if ( $pagelist !== null && $file && $file->isMultipage() ) {
			return new FilePagination(
				$indexPage,
				$pagelist,
				$file,
				$this->context
			);
		} else {
			$links = $indexPage->getLinksToPageNamespace();
			$pages = array();
			$pageNumbers = array();
			foreach( $links as $link ) {
				$pages[] = new ProofreadPagePage( $link[0], $indexPage );
				$pageNumbers[] = new PageNumber( $link[1] );
			}
			return new PagePagination( $indexPage, $pages, $pageNumbers );
		}
	}
}