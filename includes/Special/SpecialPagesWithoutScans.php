<?php

namespace ProofreadPage\Special;

use MediaWiki\MediaWikiServices;
use PageQueryPage;
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

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function getQueryInfo() {
		$context = Context::getDefaultContext();
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		$queryInfo = $linksMigration->getQueryInfo(
			'templatelinks',
			'templatelinks',
			'LEFT JOIN'
		);
		$joinConds = [
			'templatelinks' => [
				'LEFT JOIN',
				[ 'page_id = tl_from' ]
			],
			'page_props' => [
				'LEFT JOIN',
				[ 'page_id = pp_page', 'pp_propname' => 'disambiguation' ]
			]
		];
		$joinConds = array_merge( $joinConds, $queryInfo['joins'] );
		if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
			$joinConds['linktarget'][1]['lt_namespace'] = $context->getPageNamespaceId();
			$joinConds['linktarget'][0] = 'LEFT JOIN';
		} else {
			$joinConds['templatelinks'][1]['tl_namespace'] = $context->getPageNamespaceId();
		}
		return [
			'tables' => array_merge( [ 'page', 'page_props' ], $queryInfo['tables'] ),
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
			'join_conds' => $joinConds
		];
	}

	/**
	 * @return string
	 */
	protected function getGroupName() {
		return 'maintenance';
	}
}
