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

	public function __construct(
		private readonly IConnectionProvider $dbProvider,
		private readonly Title $indexTitle,
	) {
		parent::__construct();
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
