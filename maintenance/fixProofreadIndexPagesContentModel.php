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
 */

use MediaWiki\MediaWikiServices;
use ProofreadPage\ProofreadPage;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

/**
 * Set the content model type for Page: pages
 *
 * @ingroup ProofreadPage
 */
class FixProofreadIndexPagesContentModel extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Set the content model type for Index: pages' );
		$this->setBatchSize( 1000 );

		$this->requireExtension( 'ProofreadPage' );
	}

	/**
	 * @inheritDoc
	 */
	public function doDBUpdates() {
		$dbw = wfGetDB( DB_PRIMARY );
		if ( !$dbw->fieldExists( 'page', 'page_content_model', __METHOD__ ) ) {
			$this->error( 'page_content_model field of page table does not exists.' );
			return false;
		}

		$this->output( "Updating content model for Index: pages..\n" );
		$total = 0;
		$namespaceId = ProofreadPage::getIndexNamespaceId();
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		do {
			$pageIds = $dbw->selectFieldValues(
				'page',
				'page_id',
				[
					'page_namespace' => $namespaceId,
					'page_content_model' => CONTENT_MODEL_WIKITEXT
				],
				__METHOD__,
				[ 'LIMIT' => $this->mBatchSize ]
			);
			if ( !$pageIds ) {
				break;
			}
			$dbw->update(
				'page',
				[ 'page_content_model' => CONTENT_MODEL_PROOFREAD_INDEX ],
				[ 'page_id' => $pageIds ],
				__METHOD__
			);
			$lbFactory->waitForReplication();
			$total += $dbw->affectedRows();
			$this->output( "$total\n" );
		} while ( true );

		$this->output( "Update of the content model for Index: pages is done.\n" );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getUpdateKey() {
		return 'FixIndexPagesContentModel';
	}

}

$maintClass = FixProofreadIndexPagesContentModel::class;
require_once RUN_MAINTENANCE_IF_MAIN;
