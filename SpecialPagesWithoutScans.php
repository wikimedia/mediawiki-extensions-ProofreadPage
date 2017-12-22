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
class SpecialPagesWithoutScans extends QueryPage {
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
	 * Return a clause with the list of disambiguation templates.
	 * This function was copied verbatim from specials/SpecialDisambiguations.php
	 * @param \Wikimedia\Rdbms\IDatabase $dbr
	 * @return mixed
	 */
	public function getDisambiguationTemplates( $dbr ) {
		$dMsgText = wfMessage( 'proofreadpage-disambiguationspage' )->inContentLanguage()->text();

		$linkBatch = new LinkBatch;

		# If the text can be treated as a title, use it verbatim.
		# Otherwise, pull the titles from the links table
		$dp = Title::newFromText( $dMsgText );
		if ( $dp ) {
			if ( $dp->getNamespace() != NS_TEMPLATE ) {
				# FIXME we assume the disambiguation message is a template but
				# the page can potentially be from another namespace :/
				wfDebug( "Mediawiki:proofreadpage-disambiguationspage message " .
					"does not refer to a template!\n" );
			}
			$linkBatch->addObj( $dp );
		} else {
			# Get all the templates linked from the Mediawiki:Disambiguationspage
			$disPageObj = Title::makeTitleSafe( NS_MEDIAWIKI, 'disambiguationspage' );
			$res = $dbr->select(
				[ 'pagelinks', 'page' ],
				'pl_title',
				[ 'page_id = pl_from', 'pl_namespace' => NS_TEMPLATE,
					'page_namespace' => $disPageObj->getNamespace(),
					'page_title' => $disPageObj->getDBkey() ],
				__METHOD__ );

			foreach ( $res as $row ) {
				$linkBatch->addObj( Title::makeTitle( NS_TEMPLATE, $row->pl_title ) );
			}
		}
		return $linkBatch->constructSet( 'tl', $dbr );
	}

	public function getQueryInfo() {
		$dbr = wfGetDB( DB_REPLICA );

		// Construct subqueries
		$pagesWithScansSubquery = $dbr->selectSQLText(
			[ 'templatelinks', 'page' ],
			'DISTINCT tl_from',
			[
				'page_id=tl_from',
				'tl_namespace' => ProofreadPage::getPageNamespaceId(),
				'page_namespace' => NS_MAIN
			]
		);

		// Exclude disambiguation pages too
		// FIXME: Update to filter against 'disambiguation' page property
		// instead. See https://www.mediawiki.org/wiki/Extension:Disambiguator.
		// May want to verify that wikis using ProofreadPage have implemented
		// the __DISAMBIG__ magic word for their disambiguation pages before
		// changing this.
		$dt = $this->getDisambiguationTemplates( $dbr );
		$disambigPagesSubquery = $dbr->selectSQLText(
			[ 'page', 'templatelinks' ],
			'page_id',
			[
				'page_id=tl_from',
				'page_namespace' => NS_MAIN,
				$dt
			]
		);

		return [
			'tables' => 'page',
			'fields' => [
				"'PagesWithoutScans' AS type",
				'page_namespace AS namespace',
				'page_title AS title',
				'page_len AS value' ],
			'conds' => [
				'page_namespace' => NS_MAIN,
				'page_is_redirect' => 0,
				"page_id NOT IN ($pagesWithScansSubquery)",
				"page_id NOT IN ($disambigPagesSubquery)" ],
			'options' => [ 'USE INDEX' => 'page_len' ]
		];
	}

	public function sortDescending() {
		return true;
	}

	public function formatResult( $skin, $result ) {
		global $wgContLang;
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .
				htmlspecialchars( "{$result->namespace}:{$result->title}" ) . '-->';
		}
		$hlink = Linker::linkKnown(
			$title,
			$this->msg( 'hist' )->escaped(),
			[],
			[ 'action' => 'history' ]
		);
		$plink = $this->isCached() ? Linker::link( $title ) : Linker::linkKnown( $title );
		$size = $this->msg( 'nbytes', $result->value )->escaped();

		return $title->exists()
				? "({$hlink}) {$dm}{$plink} {$dm}[{$size}]"
				: "<s>({$hlink}) {$dm}{$plink} {$dm}[{$size}]</s>";
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
