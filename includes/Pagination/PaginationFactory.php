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
	private $paginations = [];

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
		} catch ( FileNotFoundException $e ) {
			$file = false;
		}

		// check if it is using pagelist
		$pagelist = $indexPage->getContent()->getPagelistTagContent();
		if ( $pagelist !== null && $file && $file->isMultipage() ) {
			return new FilePagination(
				$indexPage,
				$pagelist,
				$file,
				$this->context
			);
		} else {
			$links = $indexPage->getContent()->getLinksToNamespace(
				Context::getDefaultContext()->getPageNamespaceId()
			);
			$pages = [];
			$pageNumbers = [];
			foreach ( $links as $link ) {
				$pages[] = ProofreadPagePage::newFromTitle( $link->getTarget() );
				$pageNumbers[] = new PageNumber( $link->getLabel() );
			}
			return new PagePagination( $indexPage, $pages, $pageNumbers );
		}
	}
}
