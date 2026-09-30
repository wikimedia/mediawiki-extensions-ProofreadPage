<?php

namespace ProofreadPage\Index;

use MediaWiki\Deferred\DataUpdate;
use MediaWiki\Title\Title;
use ProofreadPage\Page\PageQualityLevelLookup;
use ProofreadPage\Pagination\Pagination;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @license GPL-2.0-or-later
 */
class UpdateIndexQualityStats extends DataUpdate {

	public function __construct(
		private readonly IConnectionProvider $dbProvider,
		private readonly PageQualityLevelLookup $pageQualityLevelLookup,
		private readonly Pagination $pagination,
		private readonly Title $indexTitle,
		private readonly ?Title $overrideTitle = null,
		private readonly ?int $overrideLevel = null,
	) {
		parent::__construct();
	}

	/**
	 * @inheritDoc
	 */
	public function doUpdate() {
		$builder = new QualityStatsBuilder( $this->pageQualityLevelLookup );
		$stats = $builder->buildStatsForPaginationWithOverride(
			$this->pagination, $this->overrideTitle, $this->overrideLevel
		);

		$this->dbProvider->getPrimaryDatabase()->newReplaceQueryBuilder()
			->replaceInto( 'pr_index' )
			->uniqueIndexFields( 'pr_page_id' )
			->row( [
				'pr_page_id' => $this->indexTitle->getArticleID( IDBAccessObject::READ_LATEST ),
				'pr_count' => $stats->getNumberOfPages(),
				'pr_q0' => $stats->getNumberOfPagesForQualityLevel( 0 ),
				'pr_q1' => $stats->getNumberOfPagesForQualityLevel( 1 ),
				'pr_q2' => $stats->getNumberOfPagesForQualityLevel( 2 ),
				'pr_q3' => $stats->getNumberOfPagesForQualityLevel( 3 ),
				'pr_q4' => $stats->getNumberOfPagesForQualityLevel( 4 )
			] )
			->caller( __METHOD__ )
			->execute();
	}
}
