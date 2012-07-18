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
 * @ingroup SpecialPage
 */

/**
 * Special page that lists the texts that have no transclusions
 * Pages in MediaWiki:Proofreadpage_notnaked_category are excluded.
 */
class PagesWithoutScans extends DisambiguationsPage {

	function __construct( $name = 'PagesWithoutScans' ) {
		parent::__construct( $name );
	}

	function getPageHeader() {
		return '';
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );

		// Construct subqueries
		$pagesWithScansSubquery = $dbr->selectSQLText(
			array( 'templatelinks', 'page' ),
			'DISTINCT tl_from',
			array(
				'page_id=tl_from',
				'tl_namespace' => ProofreadPage::getPageNamespaceId(),
				'page_namespace' => NS_MAIN
			)
		);

		// Exclude disambiguation pages too
		$disambigPagesSubquery = $dbr->selectSQLText(
			array( 'page', 'templatelinks' ),
			'page_id',
			array(
				'page_id=tl_from',
				'page_namespace' => NS_MAIN,
				$this->getQueryFromLinkBatch(),
			)
		);

		return array(
			'tables' => 'page',
			'fields' => array(
				"'PagesWithoutScans' AS type",
				'page_namespace AS namespace',
				'page_title AS title',
				'page_len AS value' ),
			'conds' => array(
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				"page_id NOT IN ($pagesWithScansSubquery)",
				"page_id NOT IN ($disambigPagesSubquery)" ),
			'options' => array( 'USE INDEX' => 'page_len' )
		);
	}

	function getOrderFields() {
		return array( 'value' );
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( "{$result->namespace}:{$result->title}" ) . '-->';
		}
		$hlink = Linker::linkKnown(
			$title,
			wfMsgHtml( 'hist' ),
			array(),
			array( 'action' => 'history' )
		);
		$plink = $this->isCached()
					? Linker::link( $title )
					: Linker::linkKnown( $title );
		$size = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ),
			$this->getLanguage()->formatNum( htmlspecialchars( $result->value ) )
		);

		return $title->exists()
				? "({$hlink}) {$dm}{$plink} {$dm}[{$size}]"
				: "<s>({$hlink}) {$dm}{$plink} {$dm}[{$size}]</s>";
	}
}
