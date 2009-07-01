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
		return 'ProofreadPagesQuery';
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
		$name = $dbr->addQuotes( $this->getName() );

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
		global $pr_index_namespace;

		$dm = $wgContLang->getDirMark();

		$title = Title::newFromText( $pr_index_namespace.":".$result->title );

		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( $pr_index_namespace.":".$result->title ). '-->';
		}
		$plink = $this->isCached()
		  ? $skin->makeLinkObj( $title , $title->getText() )
			: $skin->makeKnownLinkObj( $title , $title->getText() );
		$size = "[".$result->pr_count."&nbsp;pages]&nbsp;&nbsp;";
		$q0 = $result->pr_q0;
		$q1 = $result->pr_q1;
		$q2 = $result->pr_q2;
		$q3 = $result->pr_q3;
		$q4 = $result->pr_q4;

		$table = "<table style=\"line-height:100%;\" border=0 cellpadding=0 cellspacing=0 >
<tr><td>{$dm}{$plink} {$dm}{$size} &nbsp;</td>
<td align=center class='quality4' width=\"$q4\">".($q4>10?$q4:"")."</td>
<td align=center class='quality3' width=\"$q3\">".($q3>10?$q3:"")."</td>
<td align=center class='quality2' width=\"$q2\">".($q2>10?$q2:"")."</td>
<td align=center class='quality1' width=\"$q1\">".($q1>10?$q1:"")."</td>
<td align=center class='quality0' width=\"$q0\">".($q0>10?$q0:"")."</td>
</tr></table>";
		return $title->exists()	? "$table" : "<s>{$dm}{$plink} {$dm}{$size}</s>";
	}
}
