<?php

namespace ProofreadPage\Special;

use MediaWiki\SpecialPage\PageQueryPage;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
 *
 * Special page that lists the texts that have no transclusions
 */
class SpecialPagesWithoutScans extends PageQueryPage {

	/**
	 * @param string $name
	 */
	public function __construct( $name = 'PagesWithoutScans' ) {
		parent::__construct( $name );
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function getQueryInfo() {
		$context = Context::getDefaultContext();
		$queryBuilder = $this->getRecacheDB()->newSelectQueryBuilder()
			->select( [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_len'
			] )
			->from( 'page' )
			->leftJoin( 'page_props', null, [ 'page_id = pp_page', 'pp_propname' => 'disambiguation' ] )
			->where( [
				'pp_page' => null,
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				] );
		$subQuery = $queryBuilder->newSubquery()
			->select( 'page_id' )
			->from( 'page' )
			->join( 'templatelinks', null, 'page_id = tl_from' )
			->join( 'linktarget', null, 'tl_target_id = lt_id' )
			->where( [ 'lt_namespace' => $context->getPageNamespaceId(), 'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0, ] );
		$queryBuilder->where( 'page_id NOT IN (' . $subQuery->getSQL() . ')' );

		return $queryBuilder->getQueryInfo();
	}

	/**
	 * @return string
	 */
	protected function getGroupName() {
		return 'maintenance';
	}
}
