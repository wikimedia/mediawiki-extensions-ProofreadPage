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

class ProofreadPageDbConnector {

	/**
	 * @param $values array
	 * @param ResultWrapper
	 */
	public static function getCategoryNamesForPageIds( $pageIds ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->select(
			array( 'categorylinks' ),
			array( 'cl_from', 'cl_to' ),
			array( 'cl_from IN(' . implode( ',', $pageIds ) . ')' ),
			__METHOD__
		);
	}

	/**
	 * @param $pp array
	 * @param $cat array
	 * @return ResultWrapper
	 */
	public static function getPagesNameInCategory( $pp, $cat ) {
		$dbr = wfGetDB( DB_SLAVE );
		return $dbr->select(
			array( 'page', 'categorylinks' ),
			array( 'page_title' ),
			array(
				'page_title' => $pp,
				'cl_to' => $cat,
				'page_namespace' => ProofreadPage::getPageNamespaceId()
			),
			__METHOD__,
			null,
			array( 'categorylinks' => array( 'LEFT JOIN', 'cl_from=page_id' ) )
		);
	}

	/**
	 * @param $query array
	 * @param $cat string
	 * @return integer
	 */
	public static function queryCount( $query, $cat ) {
		$dbr = wfGetDB( DB_SLAVE );
		$query['conds']['cl_to'] = str_replace( ' ' , '_' , wfMessage( $cat )->inContentLanguage()->text() );
		$res = $dbr->select( $query['tables'], $query['fields'], $query['conds'], __METHOD__, array(), $query['joins'] );

		if( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			$n = $row->count;
			return $n;
		}
		return 0;
	}

	/**
	 * @param $pages string
	 * @return integer|null
	 */
	public static function getNumberOfExistingPagesFromPageTitle( $pages ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
				array( 'page' ),
				array( 'COUNT(page_id) AS count'),
				array( 'page_namespace' => ProofreadPage::getPageNamespaceId(), 'page_title' => $pages ),
				__METHOD__
			);

		if( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			return $row->count;
		}
		return null;
	}

	/**
	 * @param $id
	 * @return string|null
	 */
	public static function  getIndexTitleForPageId( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
			array( 'templatelinks' ),
			array( 'tl_title AS title' ),
			array( 'tl_from' => $id, 'tl_namespace' => ProofreadPage::getPageNamespaceId() ),
			__METHOD__,
			array( 'LIMIT' => 1 )
		);
		if( $res ) {
			$res2 = $dbr->selectRow(
				array( 'pagelinks', 'page' ),
				array( 'page_title AS title' ),
				array(
					'pl_title' => $res->title,
					'pl_namespace' => ProofreadPage::getPageNamespaceId(),
					'page_namespace' => ProofreadPage::getIndexNamespaceId()
				),
				__METHOD__,
				array( 'LIMIT' => 1 ),
				array( 'page' => array( 'LEFT JOIN', 'page_id=pl_from' ) )
			);
			if( $res2 ) {
				return $res2->title;
			}
		}
		return null;
	}

	/**
	 * @param $id
	 * @return integer|null
	 */
	public static function countTransclusionFromPageId( $id ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'templatelinks', 'page' ),
			array( 'COUNT(page_id) AS count' ),
			array( 'tl_from' => $id, 'tl_namespace' => ProofreadPage::getPageNamespaceId() ),
			__METHOD__,
			null,
			array( 'page' => array( 'LEFT JOIN', 'page_title=tl_title AND page_namespace=tl_namespace' ) )
		);
		if( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			return $row->count;
		} else {
			return null;
		}
	}
}
