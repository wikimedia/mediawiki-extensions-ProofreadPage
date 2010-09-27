<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Extensions
 */

/**
 * Special page that lists the texts that have no transclusions
 * Pages in MediaWiki:Proofreadpage_notnaked_category are excluded.
 */
class PagesWithoutScans extends SpecialPage {

	public function __construct() {
		parent::__construct( 'PagesWithoutScans' );
	}

	public function execute( $parameters ) {
		$this->setHeaders();
		list( $limit, $offset ) = wfCheckLimits();
		$cnl = new PagesWithoutScansQuery();
		$cnl->doQuery( $offset, $limit );
	}
}

class PagesWithoutScansQuery extends QueryPage {

	function __construct() {
		wfLoadExtensionMessages( 'ProofreadPage' );
		$this->page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
		$this->cat = wfMsgForContent( 'proofreadpage_notnaked_category' );
	}

	function getName() {
		return 'PagesWithoutScans';
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		global $wgContentNamespaces;

		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$templatelinks = $dbr->tableName( 'templatelinks' );
		$categorylinks = $dbr->tableName( 'categorylinks' );
		$forceindex = $dbr->useIndexClause( 'page_len' );
		$page_ns_index = MWNamespace::getCanonicalIndex( strtolower( $this->page_namespace ) );
		$cat = $dbr->strencode( str_replace( ' ' , '_' , $this->cat ) );
		$clause = "page_namespace=" . NS_MAIN . " AND page_is_redirect=0 AND page_id NOT IN ( SELECT DISTINCT tl_from FROM $templatelinks LEFT JOIN $page ON page_id=tl_from WHERE tl_namespace=$page_ns_index AND page_namespace=" . NS_MAIN . " ) AND page_id NOT IN ( SELECT DISTINCT cl_from FROM $categorylinks WHERE cl_to='$cat' )";

		return
			"SELECT page_namespace as namespace,
			        page_title as title,
			        page_len AS value
			FROM $page $forceindex
			WHERE $clause";
	}

	function sortDescending() {
		return true;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( "{$result->namespace}:{$result->title}" ) . '-->';
		}
		$hlink = $skin->linkKnown(
			$title,
			wfMsgHtml( 'hist' ),
			array(),
			array( 'action' => 'history' )
		);
		$plink = $this->isCached()
					? $skin->link( $title )
					: $skin->linkKnown( $title );
		$size = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ), $wgLang->formatNum( htmlspecialchars( $result->value ) ) );

		return $title->exists()
				? "({$hlink}) {$dm}{$plink} {$dm}[{$size}]"
				: "<s>({$hlink}) {$dm}{$plink} {$dm}[{$size}]</s>";
	}
}

