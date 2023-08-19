<?php

namespace ProofreadPage\Pagination;

use MediaWiki\Title\Title;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\FileProvider;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Index\IndexContentLookup;

/**
 * @license GPL-2.0-or-later
 */
class PaginationFactory {

	/** @var FileProvider */
	private $fileProvider;

	/** @var IndexContentLookup */
	private $indexContentLookup;

	/** @var int */
	private $pageNamespaceId;

	/** @var Pagination[] */
	private $paginations = [];

	/**
	 * @param FileProvider $fileProvider
	 * @param IndexContentLookup $indexContentLookup
	 * @param int $pageNamespaceId
	 */
	public function __construct(
		FileProvider $fileProvider,
		IndexContentLookup $indexContentLookup,
		int $pageNamespaceId
	) {
		$this->fileProvider = $fileProvider;
		$this->indexContentLookup = $indexContentLookup;
		$this->pageNamespaceId = $pageNamespaceId;
	}

	/**
	 * Check if the given index has a cached pagination
	 * @param Title $indexTitle
	 * @return bool
	 */
	public function isIndexTitleInCache( Title $indexTitle ): bool {
		return array_key_exists( $indexTitle->getDBkey(), $this->paginations );
	}

	/**
	 * @param Title $indexTitle
	 * @return Pagination
	 */
	public function getPaginationForIndexTitle( Title $indexTitle ) {
		$key = $indexTitle->getDBkey();

		if ( !array_key_exists( $key, $this->paginations ) ) {
			$indexContent = $this->indexContentLookup->getIndexContentForTitle( $indexTitle );
			$this->paginations[$key] = $this->buildPaginationForIndexContent( $indexTitle, $indexContent );
		}

		return $this->paginations[$key];
	}

	/**
	 * @param Title $indexTitle
	 * @param IndexContent $indexContent
	 * @return Pagination
	 */
	public function buildPaginationForIndexContent( Title $indexTitle, IndexContent $indexContent ): Pagination {
		try {
			$file = $this->fileProvider->getFileForIndexTitle( $indexTitle );
		} catch ( FileNotFoundException $e ) {
			$file = false;
		}

		// check if it is using pagelist
		$pagelist = $indexContent->getPagelistTagContent();
		if ( $pagelist !== null && $file ) {
			if ( $file->isMultipage() ) {
				return new FilePagination(
					$indexTitle,
					$pagelist,
					$file->pageCount(),
					$this->pageNamespaceId
				);
			} else {
				return new SimpleFilePagination(
					$indexTitle,
					$pagelist,
					$this->pageNamespaceId
				);
			}
		} else {
			$links = $indexContent->getLinksToNamespace( $this->pageNamespaceId );
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
