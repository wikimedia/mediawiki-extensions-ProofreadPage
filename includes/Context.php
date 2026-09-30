<?php

namespace ProofreadPage;

use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigException;
use MediaWiki\MediaWikiServices;
use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\DatabaseIndexContentLookup;
use ProofreadPage\Index\IndexContentLookup;
use ProofreadPage\Index\IndexQualityStatsLookup;
use ProofreadPage\Page\DatabaseIndexForPageLookup;
use ProofreadPage\Page\DatabasePageQualityLevelLookup;
use ProofreadPage\Page\IndexForPageLookup;
use ProofreadPage\Page\PageQualityLevelLookup;
use ProofreadPage\Pagination\PaginationFactory;

/**
 * @license GPL-2.0-or-later
 *
 * Extension context
 *
 * You should only get it with Context::getDefaultContext in extension entry points and then inject
 * it in objects that requires it
 * For testing, get a test compatible version with ProofreadPageTextCase::getContext()
 */
class Context {

	private PaginationFactory $paginationFactory;

	public function __construct(
		private readonly int $pageNamespaceId,
		private readonly int $indexNamespaceId,
		private readonly FileProvider $fileProvider,
		private readonly CustomIndexFieldsParser $customIndexFieldsParser,
		private readonly IndexForPageLookup $indexForPageLookup,
		private readonly IndexContentLookup $indexContentLookup,
		private readonly PageQualityLevelLookup $pageQualityLevelLookup,
		private readonly IndexQualityStatsLookup $indexQualityStatsLookup,
	) {
		$this->paginationFactory = new PaginationFactory(
			$fileProvider,
			$indexContentLookup,
			$pageNamespaceId
		);
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
	 * @return Config
	 * @throws ConfigException
	 */
	public function getConfig() {
		return MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'proofreadpage' );
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
	 * @return IndexContentLookup
	 */
	public function getIndexContentLookup() {
		return $this->indexContentLookup;
	}

	/**
	 * @return PageQualityLevelLookup
	 */
	public function getPageQualityLevelLookup() {
		return $this->pageQualityLevelLookup;
	}

	/**
	 * @return IndexQualityStatsLookup
	 */
	public function getIndexQualityStatsLookup(): IndexQualityStatsLookup {
		return $this->indexQualityStatsLookup;
	}

	/**
	 * @param bool $purge
	 * @return Context
	 */
	public static function getDefaultContext( $purge = false ) {
		static $defaultContext;

		if ( $defaultContext === null || $purge ) {
			$dbProvider = MediaWikiServices::getInstance()->getConnectionProvider();
			$repoGroup = MediaWikiServices::getInstance()->getRepoGroup();
			$pageNamespaceId = ProofreadPageInit::getNamespaceId( 'page' );
			$indexNamespaceId = ProofreadPageInit::getNamespaceId( 'index' );
			$defaultContext = new self( $pageNamespaceId, $indexNamespaceId,
				new FileProvider( $repoGroup ),
				new CustomIndexFieldsParser(),
				new DatabaseIndexForPageLookup( $indexNamespaceId, $repoGroup ),
				new DatabaseIndexContentLookup(),
				new DatabasePageQualityLevelLookup( $pageNamespaceId ),
				new IndexQualityStatsLookup( $dbProvider )
			);
		}

		return $defaultContext;
	}
}
