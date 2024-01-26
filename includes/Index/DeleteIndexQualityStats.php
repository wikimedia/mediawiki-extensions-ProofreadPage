<?php

namespace ProofreadPage\Index;

use IDBAccessObject;
use MediaWiki\Deferred\DataUpdate;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @license GPL-2.0-or-later
 */
class DeleteIndexQualityStats extends DataUpdate {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var Title */
	private $indexTitle;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param Title $indexTitle
	 */
	public function __construct( ILoadBalancer $loadBalancer, Title $indexTitle ) {
		parent::__construct();

		$this->loadBalancer = $loadBalancer;
		$this->indexTitle = $indexTitle;
	}

	/**
	 * @inheritDoc
	 */
	public function doUpdate() {
		$this->loadBalancer->getConnection( ILoadBalancer::DB_PRIMARY )->delete(
			'pr_index',
			[ 'pr_page_id' => $this->indexTitle->getArticleID( IDBAccessObject::READ_LATEST ) ],
			__METHOD__
		);
	}
}
