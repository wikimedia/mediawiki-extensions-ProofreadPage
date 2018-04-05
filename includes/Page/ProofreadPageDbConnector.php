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

use Wikimedia\Rdbms\IResultWrapper;

class ProofreadPageDbConnector {

	/**
	 * @param array $pageIds
	 * @return IResultWrapper
	 */
	public static function getCategoryNamesForPageIds( $pageIds ) {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->select(
			[ 'categorylinks' ],
			[ 'cl_from', 'cl_to' ],
			[ 'cl_from IN(' . implode( ',', $pageIds ) . ')' ],
			__METHOD__
		);
	}

	/**
	 * @param array $pp
	 * @param array $cat
	 * @return IResultWrapper
	 */
	public static function getPagesNameInCategory( $pp, $cat ) {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->select(
			[ 'page', 'categorylinks' ],
			[ 'page_title' ],
			[
				'page_title' => $pp,
				'cl_to' => $cat,
				'page_namespace' => ProofreadPage::getPageNamespaceId()
			],
			__METHOD__,
			null,
			[ 'categorylinks' => [ 'LEFT JOIN', 'cl_from=page_id' ] ]
		);
	}

	/**
	 * @param array $query
	 * @param string $cat
	 * @return int
	 */
	public static function queryCount( $query, $cat ) {
		$dbr = wfGetDB( DB_REPLICA );
		$query['conds']['cl_to'] = str_replace( ' ', '_',
			wfMessage( $cat )->inContentLanguage()->text() );
		$res = $dbr->select(
			$query['tables'],
			$query['fields'],
			$query['conds'],
			__METHOD__,
			[],
			$query['joins']
		);

		if ( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			$n = $row->count;
			return $n;
		}
		return 0;
	}

	/**
	 * @param string $pages
	 * @return int|null
	 */
	public static function getNumberOfExistingPagesFromPageTitle( $pages ) {
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
				[ 'page' ],
				[ 'COUNT(page_id) AS count' ],
				[ 'page_namespace' => ProofreadPage::getPageNamespaceId(), 'page_title' => $pages ],
				__METHOD__
			);

		if ( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			return $row->count;
		}
		return null;
	}

	/**
	 * @param int $id
	 * @return string|null
	 */
	public static function  getIndexTitleForPageId( $id ) {
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->selectRow(
			[ 'templatelinks' ],
			[ 'tl_title AS title' ],
			[ 'tl_from' => $id, 'tl_namespace' => ProofreadPage::getPageNamespaceId() ],
			__METHOD__,
			[ 'LIMIT' => 1 ]
		);
		if ( $res ) {
			$res2 = $dbr->selectRow(
				[ 'pagelinks', 'page' ],
				[ 'page_title AS title' ],
				[
					'pl_title' => $res->title,
					'pl_namespace' => ProofreadPage::getPageNamespaceId(),
					'page_namespace' => ProofreadPage::getIndexNamespaceId()
				],
				__METHOD__,
				[ 'LIMIT' => 1 ],
				[ 'page' => [ 'LEFT JOIN', 'page_id=pl_from' ] ]
			);
			if ( $res2 ) {
				return $res2->title;
			}
		}
		return null;
	}

	/**
	 * @param int $id
	 * @return int|null
	 */
	public static function countTransclusionFromPageId( $id ) {
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			[ 'templatelinks', 'page' ],
			[ 'COUNT(page_id) AS count' ],
			[ 'tl_from' => $id, 'tl_namespace' => ProofreadPage::getPageNamespaceId() ],
			__METHOD__,
			null,
			[ 'page' => [ 'LEFT JOIN', 'page_title=tl_title AND page_namespace=tl_namespace' ] ]
		);
		if ( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			return $row->count;
		} else {
			return null;
		}
	}
}
