<?php

namespace ProofreadPage\Pagination;

use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use Title;

/**
 * @license GPL-2.0-or-later
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
	 * @param Title $indexTitle
	 * @return Pagination
	 */
	public function getPaginationForIndexTitle( Title $indexTitle ) {
		$key = $indexTitle->getDBkey();

		if ( !array_key_exists( $key, $this->paginations ) ) {
			$this->paginations[$key] = $this->buildPaginationForIndexTitle( $indexTitle );
		}

		return $this->paginations[$key];
	}

	/**
	 * @param Title $indexTitle
	 * @return Pagination
	 */
	private function buildPaginationForIndexTitle( Title $indexTitle ) {
		try {
			$file = $this->context->getFileProvider()->getFileForIndexTitle( $indexTitle );
		} catch ( FileNotFoundException $e ) {
			$file = false;
		}

		// check if it is using pagelist
		$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
		$pagelist = $indexContent->getPagelistTagContent();
		if ( $pagelist !== null && $file && $file->isMultipage() ) {
			return new FilePagination( $indexTitle, $pagelist, $file, $this->context );
		} else {
			$links = $indexContent->getLinksToNamespace(
				Context::getDefaultContext()->getPageNamespaceId()
			);
			$pages = [];
			$pageNumbers = [];
			foreach ( $links as $link ) {
				$pages[] = $link->getTarget();
				$pageNumbers[] = new PageNumber( $link->getLabel() );
			}
			return new PagePagination( $pages, $pageNumbers );
		}
	}
}
