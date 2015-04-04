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

if ( !class_exists( 'LoggedUpdateMaintenance' ) ) {
	$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../../..';
	require_once $basePath . '/maintenance/Maintenance.php';
}

/**
 * Set the content model type for Page: pages
 */
class FixProofreadIndexPagesContentModel extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();

		$this->mDescription = 'Set the content model type for Index: pages';
		$this->setBatchSize( 1000 );
	}

	/**
	 * @see LoggedUpdateMaintenance::doDBUpdates
	 */
	public function doDBUpdates() {
		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->fieldExists( 'page', 'page_content_model', __METHOD__ ) ) {
			$this->error( 'page_content_model field of page table does not exists.' );
			return false;
		}

		do {
			$dbw->query(
				'UPDATE page SET page_content_model = ' . $dbw->addQuotes( CONTENT_MODEL_PROOFREAD_INDEX ) .
				' WHERE page_namespace  = ' . intval( ProofreadPage::getIndexNamespaceId() ) .
				' AND page_content_model = ' .  $dbw->addQuotes( CONTENT_MODEL_WIKITEXT ) .
				' ORDER BY page_namespace, page_title LIMIT ' . intval( $this->mBatchSize )
			);
			wfWaitForSlaves();
		} while ( $dbw->affectedRows() > 0 );

		$this->output( "Update of the content model for Index: pages is done.\n" );

		return true;
	}

	/**
	 * @see LoggedUpdateMaintenance::getUpdateKey
	 */
	public function getUpdateKey() {
		return 'FixIndexPagesContentModel';
	}

}

$maintClass = 'FixProofreadIndexPagesContentModel';
require_once ( RUN_MAINTENANCE_IF_MAIN );
