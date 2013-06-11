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
}
