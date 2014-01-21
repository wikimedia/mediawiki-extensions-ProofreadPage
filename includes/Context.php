<?php

namespace ProofreadPage;

use ProofreadPageInit;
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

	public function __construct( $pageNamespaceId, $indexNamespaceId, FileProvider $fileProvider ) {
		$this->pageNamespaceId = ProofreadPageInit::getNamespaceId( 'page' );
		$this->indexNamespaceId = ProofreadPageInit::getNamespaceId( 'index' );
		$this->fileProvider = $fileProvider;
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
	 * @return Context
	 */
	public static function getDefaultContext() {
		static $defaultContext;

		if ( $defaultContext === null ) {
			$defaultContext = new self(
				ProofreadPageInit::getNamespaceId( 'page' ),
				ProofreadPageInit::getNamespaceId( 'index' ),
				new FileProvider( RepoGroup::singleton() )
			);
		}

		return $defaultContext;
	}
}