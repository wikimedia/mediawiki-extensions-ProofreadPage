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
 * @ingroup ProofreadPage
 */

class ProofreadIndexDbConnector {

	/**
	 * @param Object $x
	 * @param int $indexId
	 * @param WikiPage $article
	 */
	public static function replaceIndexById( $x, $indexId, WikiPage $article ) {
		$n  = $x->pr_count;
		$n0 = $x->pr_q0;
		$n1 = $x->pr_q1;
		$n2 = $x->pr_q2;
		$n3 = $x->pr_q3;
		$n4 = $x->pr_q4;

		if ( isset( $article->new_q ) ) { // new_q is undefined in parser tests
			switch ( $article->new_q ) {
				case 0:
					$n0++;
					break;
				case 1:
					$n1++;
					break;
				case 2:
					$n2++;
					break;
				case 3:
					$n3++;
					break;
				case 4:
					$n4++;
					break;
			}
		}
		if ( isset( $article->old_q ) ) { // old_q is undefined in parser tests
			switch ( $article->old_q ) {
				case 0:
					$n0--;
					break;
				case 1:
					$n1--;
					break;
				case 2:
					$n2--;
					break;
				case 3:
					$n3--;
					break;
				case 4:
					$n4--;
					break;
			}
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'pr_index',
			[ 'pr_page_id' ],
			[
				'pr_page_id' => $indexId,
				'pr_count' => $n,
				'pr_q0' => $n0,
				'pr_q1' => $n1,
				'pr_q2' => $n2,
				'pr_q3' => $n3,
				'pr_q4' => $n4
			],
			__METHOD__
		);
	}

	/**
	 * @param int $n
	 * @param int $n0
	 * @param int $n1
	 * @param int $n2
	 * @param int $n3
	 * @param int $n4
	 * @param int $indexId
	 */
	public static function setIndexData( $n, $n0, $n1, $n2, $n3, $n4, $indexId ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'pr_index',
			[ 'pr_page_id' ],
			[
				'pr_page_id' => $indexId,
				'pr_count' => $n,
				'pr_q0' => $n0,
				'pr_q1' => $n1,
				'pr_q2' => $n2,
				'pr_q3' => $n3,
				'pr_q4' => $n4
			],
			__METHOD__
		);
	}

	/**
	 * Remove index data from pr_index table.
	 * @param int $pageId page identifier
	 */
	public static function removeIndexData( $pageId ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'pr_index', [ 'pr_page_id' => $pageId ], __METHOD__ );
	}

	/**
	 * @param string $indexTitle
	 * @return Object
	 */
	public static function getIndexDataFromIndexTitle( $indexTitle ) {
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->selectRow(
				[ 'pr_index', 'page' ],
				[ 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ],
				[ 'page_title' => $indexTitle, 'page_namespace' => ProofreadPage::getIndexNamespaceId() ],
				__METHOD__,
				null,
				[ 'page' => [ 'LEFT JOIN', 'page_id=pr_page_id' ] ]
				);
		return $res;
	}

	/**
	 * @param int $indexId
	 * @return Object
	 */
	public static function getIndexDataFromIndexPageId( $indexId ) {
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->selectRow(
				[ 'pr_index' ],
				[ 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ],
				[ 'pr_page_id' => $indexId ],
				__METHOD__
			);
		return $res;
	}

}
