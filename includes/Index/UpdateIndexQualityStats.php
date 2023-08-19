<?php

namespace ProofreadPage\Index;

use DataUpdate;
use MediaWiki\Title\Title;
use ProofreadPage\Page\PageQualityLevelLookup;
use ProofreadPage\Pagination\Pagination;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @license GPL-2.0-or-later
 */
class UpdateIndexQualityStats extends DataUpdate {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var PageQualityLevelLookup */
	private $pageQualityLevelLookup;

	/** @var Pagination */
	private $pagination;

	/** @var Title */
	private $indexTitle;

	/** @var Title|null */
	private $overrideTitle;

	/** @var int|null */
	private $overrideLevel;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param PageQualityLevelLookup $pageQualityLevelLookup
	 * @param Pagination $pagination
	 * @param Title $indexTitle
	 * @param Title|null $overrideTitle
	 * @param int|null $overrideLevel
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		PageQualityLevelLookup $pageQualityLevelLookup,
		Pagination $pagination,
		Title $indexTitle,
		Title $overrideTitle = null,
		int $overrideLevel = null
	) {
		parent::__construct();

		$this->loadBalancer = $loadBalancer;
		$this->pageQualityLevelLookup = $pageQualityLevelLookup;
		$this->pagination = $pagination;
		$this->indexTitle = $indexTitle;
		$this->overrideTitle = $overrideTitle;
		$this->overrideLevel = $overrideLevel;
	}

	/**
	 * @inheritDoc
	 */
	public function doUpdate() {
		$builder = new QualityStatsBuilder( $this->pageQualityLevelLookup );
		$stats = $builder->buildStatsForPaginationWithOverride(
			$this->pagination, $this->overrideTitle, $this->overrideLevel
		);

		$this->loadBalancer->getConnection( ILoadBalancer::DB_PRIMARY )->replace(
			'pr_index',
			'pr_page_id',
			[
				'pr_page_id' => $this->indexTitle->getArticleID( Title::READ_LATEST ),
				'pr_count' => $stats->getNumberOfPages(),
				'pr_q0' => $stats->getNumberOfPagesForQualityLevel( 0 ),
				'pr_q1' => $stats->getNumberOfPagesForQualityLevel( 1 ),
				'pr_q2' => $stats->getNumberOfPagesForQualityLevel( 2 ),
				'pr_q3' => $stats->getNumberOfPagesForQualityLevel( 3 ),
				'pr_q4' => $stats->getNumberOfPagesForQualityLevel( 4 )
			],
			__METHOD__
		);
	}
}
