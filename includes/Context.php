<?php

namespace ProofreadPage;

use ProofreadPage\Pagination\PaginationFactory;
use ProofreadPage\ProofreadPageInit;
use RepoGroup;

/**
 * @licence GNU GPL v2+
 *
 * Extension context
 *
 * You should only get it with Context::getDefaultContext in extension entry points and then inject it in objects that requires it
 * For testing, get a test compatible version with ProofreadPageTextCase::getContext()
 */
class Context {

	/**
	 * @var integer
	 */
	private $pageNamespaceId;

	/**
	 * @var integer
	 */
	private $indexNamespaceId;

	/**
	 * @var FileProvider
	 */
	private $fileProvider;

	/**
	 * @var PaginationFactory
	 */
	private $paginationFactory;

	/**
	 * @param int $pageNamespaceId
	 * @param int $indexNamespaceId
	 * @param FileProvider $fileProvider
	 */
	public function __construct( $pageNamespaceId, $indexNamespaceId, FileProvider $fileProvider ) {
		$this->pageNamespaceId = $pageNamespaceId;
		$this->indexNamespaceId = $indexNamespaceId;
		$this->fileProvider = $fileProvider;
		$this->paginationFactory = new PaginationFactory( $this );
	}

	/**
	 * @return integer
	 */
	public function getPageNamespaceId() {
		return $this->pageNamespaceId;
	}

	/**
	 * @return integer
	 */
	public function getIndexNamespaceId() {
		return $this->indexNamespaceId;
	}

	/**
	 * @return FileProvider
	 */
	public function getFileProvider() {
		return $this->fileProvider;
	}

	/**
	 * @return PaginationFactory
	 */
	public function getPaginationFactory() {
		return $this->paginationFactory;
	}

	/**
	 * @param bool $purgeFileProvider
	 * @return Context
	 */
	public static function getDefaultContext( $purgeFileProvider = false ) {
		static $defaultContext;

		if ( $defaultContext === null ) {
			$defaultContext = new self(
				ProofreadPageInit::getNamespaceId( 'page' ),
				ProofreadPageInit::getNamespaceId( 'index' ),
				new FileProvider( RepoGroup::singleton() )
			);
		}
		if ( $purgeFileProvider ) {
			$defaultContext->fileProvider = new FileProvider( RepoGroup::singleton() );
		}

		return $defaultContext;
	}
}