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
		$this->page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
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

	/*
	 * return a clause with the list of disambiguation templates. 
	 * this function was copied verbatim from specials/SpecialDisambiguations.php
	 */
	function disambiguation_templates( $dbr ) {
		$dMsgText = wfMsgForContent('disambiguationspage');

		$linkBatch = new LinkBatch;

		# If the text can be treated as a title, use it verbatim.
		# Otherwise, pull the titles from the links table
		$dp = Title::newFromText($dMsgText);
		if( $dp ) {
			if($dp->getNamespace() != NS_TEMPLATE) {
				# FIXME we assume the disambiguation message is a template but
				# the page can potentially be from another namespace :/
				wfDebug("Mediawiki:disambiguationspage message does not refer to a template!\n");
			}
			$linkBatch->addObj( $dp );
		} else {
				# Get all the templates linked from the Mediawiki:Disambiguationspage
				$disPageObj = Title::makeTitleSafe( NS_MEDIAWIKI, 'disambiguationspage' );
				$res = $dbr->select(
					array('pagelinks', 'page'),
					'pl_title',
					array('page_id = pl_from', 'pl_namespace' => NS_TEMPLATE,
						'page_namespace' => $disPageObj->getNamespace(), 'page_title' => $disPageObj->getDBkey()),
					__METHOD__ );

				while ( $row = $dbr->fetchObject( $res ) ) {
					$linkBatch->addObj( Title::makeTitle( NS_TEMPLATE, $row->pl_title ));
				}

				$dbr->freeResult( $res );
		}
		return $linkBatch->constructSet( 'tl', $dbr );
	}

	function getSQL() {
		global $wgContentNamespaces;

		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$templatelinks = $dbr->tableName( 'templatelinks' );
		$forceindex = $dbr->useIndexClause( 'page_len' );
		$page_ns_index = MWNamespace::getCanonicalIndex( strtolower( $this->page_namespace ) );

		/* SQL clause to exclude pages with scans */
		$pages_with_scans = "( SELECT DISTINCT tl_from FROM $templatelinks LEFT JOIN $page ON page_id=tl_from WHERE tl_namespace=$page_ns_index AND page_namespace=" . NS_MAIN . " ) ";

		/* Exclude disambiguation pages too */
		$dt = $this->disambiguation_templates( $dbr );
		$disambiguation_pages = "( SELECT page_id FROM $page LEFT JOIN $templatelinks ON page_id=tl_from WHERE page_namespace=" . NS_MAIN . " AND " . $dt . " )";

		$sql = 	"SELECT page_namespace as namespace,
				page_title as title,
				page_len AS value
			FROM $page $forceindex
			WHERE page_namespace=" . NS_MAIN . " AND page_is_redirect=0 
			AND page_id NOT IN $pages_with_scans
			AND page_id NOT IN $disambiguation_pages";

		return $sql;
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

