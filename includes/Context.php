<?php

namespace ProofreadPage;

use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Page\DatabaseIndexForPageLookup;
use ProofreadPage\Page\IndexForPageLookup;
use ProofreadPage\Pagination\PaginationFactory;
use RepoGroup;

/**
 * @licence GNU GPL v2+
 *
 * Extension context
 *
 * You should only get it with Context::getDefaultContext in extension entry points and then inject
 * it in objects that requires it
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
	 * @var CustomIndexFieldsParser
	 */
	private $customIndexFieldsParser;

	/**
	 * @var IndexForPageLookup
	 */
	private $indexForPageLookup;

	/**
	 * @param int $pageNamespaceId
	 * @param int $indexNamespaceId
	 * @param FileProvider $fileProvider
	 * @param CustomIndexFieldsParser $customIndexFieldsParser
	 * @param IndexForPageLookup $indexForPageLookup
	 */
	public function __construct(
		$pageNamespaceId, $indexNamespaceId, FileProvider $fileProvider,
		CustomIndexFieldsParser $customIndexFieldsParser, IndexForPageLookup $indexForPageLookup
	) {
		$this->pageNamespaceId = $pageNamespaceId;
		$this->indexNamespaceId = $indexNamespaceId;
		$this->fileProvider = $fileProvider;
		$this->customIndexFieldsParser = $customIndexFieldsParser;
		$this->indexForPageLookup = $indexForPageLookup;
	}

	/**
	 * @return int
	 */
	public function getPageNamespaceId() {
		return $this->pageNamespaceId;
	}

	/**
	 * @return int
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
		return new PaginationFactory( $this );
	}

	/**
	 * @return CustomIndexFieldsParser
	 */
	public function getCustomIndexFieldsParser() {
		return $this->customIndexFieldsParser;
	}

	/**
	 * @return IndexForPageLookup
	 */
	public function getIndexForPageLookup() {
		return $this->indexForPageLookup;
	}

	/**
	 * @param bool $purge
	 * @return Context
	 */
	public static function getDefaultContext( $purge = false ) {
		static $defaultContext;

		if ( $defaultContext === null || $purge ) {
			$repoGroup = RepoGroup::singleton();
			$pageNamespaceId = ProofreadPageInit::getNamespaceId( 'page' );
			$indexNamespaceId = ProofreadPageInit::getNamespaceId( 'index' );
			$defaultContext = new self( $pageNamespaceId, $indexNamespaceId,
				new FileProvider( $repoGroup ),
				new CustomIndexFieldsParser(),
				new DatabaseIndexForPageLookup( $indexNamespaceId, $repoGroup )
			);
		}

		return $defaultContext;
	}
}
