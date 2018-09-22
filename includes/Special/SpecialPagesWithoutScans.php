<?php

namespace ProofreadPage\Special;

use PageQueryPage;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
 *
 * Special page that lists the texts that have no transclusions
 */
class SpecialPagesWithoutScans extends PageQueryPage {
	public function __construct( $name = 'PagesWithoutScans' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$context = Context::getDefaultContext();
		return [
			'tables' => [ 'page', 'templatelinks', 'page_props' ],
			'fields' => [
				'page_namespace AS namespace',
				'page_title AS title',
				'page_len AS value'
			],
			'conds' => [
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				'tl_from' => null,
				'pp_page' => null
			],
			'join_conds' => [
				'templatelinks' => [
					'LEFT JOIN',
					[ 'page_id = tl_from', 'tl_namespace' => $context->getPageNamespaceId() ]
				],
				'page_props' => [
					'LEFT JOIN',
					[ 'page_id = pp_page', 'pp_propname' => 'disambiguation' ]
				]
			],
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
