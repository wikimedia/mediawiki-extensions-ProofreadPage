<?php

namespace ProofreadPage\Index;

use MediaWiki\Deferred\DataUpdate;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @license GPL-2.0-or-later
 */
class DeleteIndexQualityStats extends DataUpdate {

	/** @var IConnectionProvider */
	private $dbProvider;

	/** @var Title */
	private $indexTitle;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param Title $indexTitle
	 */
	public function __construct( IConnectionProvider $dbProvider, Title $indexTitle ) {
		parent::__construct();

		$this->dbProvider = $dbProvider;
		$this->indexTitle = $indexTitle;
	}

	/**
	 * @inheritDoc
	 */
	public function doUpdate() {
		$this->dbProvider->getPrimaryDatabase()->newDeleteQueryBuilder()
			->deleteFrom( 'pr_index' )
			->where( [ 'pr_page_id' => $this->indexTitle->getArticleID( IDBAccessObject::READ_LATEST ) ] )
			->caller( __METHOD__ )
			->execute();
	}
}
