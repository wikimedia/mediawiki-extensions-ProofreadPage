<?php

class ProofreadPageDbConnector {
	/**
	 * @param $values array
	 * @param ResultWrapper
	 */
	public static function getCategoryNamesForPageIds( $pageIds ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
				array( 'categorylinks' ),
				array( 'cl_from', 'cl_to' ),
				array( 'cl_from IN(' . implode( ',', $pageIds ) . ')' ),
				__METHOD__
			);
		return $res;
	}

	/**
	 * @param $pp array
	 * @param $cat array
	 * @return ResultWrapper
	 */
	public static function getPagesNameInCategory( $pp, $cat ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
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
		return $res;
	}

	/**
	 * @param $query
	 * @param $cat
	 * @return int
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
	 * @return integer | null
	 */
	public static function getNumberOfExistingPagesFromPageTitle( $pages ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
				array( 'page' ),
				array( 'COUNT(page_id) AS count'),
				array( 'page_namespace' => ProofreadPage::getPageNamespaceId(), 'page_title' => $pages ),
				__METHOD__
			);
		$count = null;

		if( $res && $dbr->numRows( $res ) > 0 ) {
			$row = $dbr->fetchObject( $res );
			$count = $row->count;
		}

		return $count;
	}

	/**
	 * @param $id
	 * @return string | null
	 */
	public static function  getIndexTitleForPageId( $id ) {
		$indexTitle = null;
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
				$indexTitle = $res2->title;
			}
		}
		return $indexTitle;
	}

	/**
	 * @param $id
	 * @return integer | null
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