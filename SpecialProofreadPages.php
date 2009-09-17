<?php
/**
 * @file
 * @ingroup SpecialPage
 */


if ( !defined( 'MEDIAWIKI' ) ) die( 1 );
global $wgHooks, $IP;
require_once "$IP/includes/QueryPage.php";


class ProofreadPages extends SpecialPage {

	function ProofreadPages() {
		SpecialPage::SpecialPage( 'ProofreadPages' );
	}

	function execute( $parameters ) {
		$this->setHeaders();
		list( $limit, $offset ) = wfCheckLimits();

		$cnl = new ProofreadPagesQuery();
		$cnl->doQuery( $offset, $limit );
	}
}



class ProofreadPagesQuery extends QueryPage {

	function getName() {
		return 'ProofreadPages';
	}

	function isExpensive() {
		return false;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$pr_index = $dbr->tableName( 'pr_index' );

		return
			"SELECT pr_page_id as title,
			page_title as title,
			pr_count,
			pr_q0,
			pr_q1,
			pr_q2,
			pr_q3,
			pr_q4
			FROM $pr_index 
			LEFT JOIN $page ON page_id = pr_page_id";
	}

	function getOrder() {
		return ' ORDER BY 2*pr_q4+pr_q3 ' .
			($this->sortDescending() ? 'DESC' : '');
	}

	function sortDescending() {
		return true;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$index_namespace = pr_index_ns();
		$title = Title::newFromText( $index_namespace.":".$result->title );

		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( $index_namespace.":".$result->title ). '-->';
		}
		$plink = $this->isCached()
		  ? $skin->link( $title , htmlspecialchars( $title->getText() ) )
			: $skin->linkKnown( $title , htmlspecialchars( $title->getText() ) );

		if ( !$title->exists() ) {
			return "<s>{$plink}</s>";
		}

		$size = $result->pr_count;
		$q0 = $result->pr_q0;
		$q1 = $result->pr_q1;
		$q2 = $result->pr_q2;
		$q3 = $result->pr_q3;
		$q4 = $result->pr_q4;

		$output = wfMsgExt(
			'proofreadpage_indexlist_item',
			array( 'parsemag', 'content' ),
			$plink, $size, $q0, $q1, $q2, $q3, $q4
		);
		return $output; 
	}
}
