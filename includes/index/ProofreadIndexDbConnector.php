<?php

class ProofreadIndexDbConnector{

/**
	 * @param $updater DatabaseUpdater
	 * @return bool
	 */
	public static function onLoadExtensionSchemaUpdates( $updater = null ) {
		$base = __DIR__;
		if ( $updater === null ) {
			global $wgExtNewTables;
			$wgExtNewTables[] = array( 'pr_index', "$base/ProofreadIndex.sql" );
		} else {
			$updater->addExtensionUpdate( array( 'addTable', 'pr_index',
				"$base/ProofreadIndex.sql", true ) );
		}
		return true;
	}

	/**
	 * Query the database to find if the current page is referred in an Index page.
	 * @param $title Title
	 * @return ResultWrapper
	 */
	public static function getRowsFromTitle( $title ) {
		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select(
			array( 'page', 'pagelinks' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'pl_namespace' => $title->getNamespace(),
				'pl_title' => $title->getDBkey(),
				'pl_from=page_id'
			),
			__METHOD__
		);
		return $result;
	}

	/**
	* @param $x Object
	* @param $index_id integer
	* @param $article Article
	*/
	public static function replaceIndexById( $x, $index_id, $article ) {
		$n  = $x->pr_count;
		$n0 = $x->pr_q0;
		$n1 = $x->pr_q1;
		$n2 = $x->pr_q2;
		$n3 = $x->pr_q3;
		$n4 = $x->pr_q4;

		switch( $article->new_q ) {
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
		switch( $article->old_q ) {
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
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
				'pr_index',
				array( 'pr_page_id' ),
				array(
					'pr_page_id' => $index_id,
					'pr_count' => $n,
					'pr_q0' => $n0,
					'pr_q1' => $n1,
					'pr_q2' => $n2,
					'pr_q3' => $n3,
					'pr_q4' => $n4
				),
				__METHOD__
			);
	}

	/**
	 * @param $indexId
	 * @param $n, $n0, $n1, $n2, $n3, $n4 int
	 */
	public static function setIndexData( $n, $n0, $n1, $n2, $n3, $n4, $indexId ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace(
			'pr_index',
			array( 'pr_page_id' ),
			array(
				'pr_page_id' => $indexId,
				'pr_count' => $n,
				'pr_q0' => $n0,
				'pr_q1' => $n1,
				'pr_q2' => $n2,
				'pr_q3' => $n3,
				'pr_q4' => $n4
			),
			__METHOD__
		);
	}

	/**
	 * Remove index data from pr_index table.
	 * @param $pageId Integer page identifier
	 */
	public static function removeIndexData( $pageId ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'pr_index', array( 'pr_page_id' => $pageId ), __METHOD__ );
	}

	/**
	 * @param $indexTitle
	 * @return Object
	 */
	public static function getIndexDataFromIndexTitle( $indexTitle ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
				array( 'pr_index', 'page' ),
				array( 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ),
				array( 'page_title' => $indexTitle, 'page_namespace' => ProofreadPage::getIndexNamespaceId() ),
				__METHOD__,
				null,
				array( 'page' => array( 'LEFT JOIN', 'page_id=pr_page_id' ) )
				);
		return $res;
	}

	/**
	 * @param $indexId integer
	 * @return Object
	 */
	public static function getIndexDataFromIndexPageId( $indexId ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
				array( 'pr_index' ),
				array( 'pr_count', 'pr_q0', 'pr_q1', 'pr_q2', 'pr_q3', 'pr_q4' ),
				array( 'pr_page_id' => $indexId ),
				__METHOD__
			);
		return $res;
	}

}
