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
class FixProofreadPagePagesContentModel extends LoggedUpdateMaintenance {

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Set the content model type for Page: pages' );

		$this->requireExtension( 'ProofreadPage' );
	}

	/**
	 * @inheritDoc
	 */
	public function doDBUpdates() {
		$db = wfGetDB( DB_PRIMARY );
		if ( !$db->fieldExists( 'page', 'page_content_model', __METHOD__ ) ) {
			$this->error( 'page_content_model field of page table does not exists.' );
			return false;
		}

		$db->update(
			'page',
			[
				'page_content_model' => CONTENT_MODEL_PROOFREAD_PAGE
			],
			[
				'page_namespace' => ProofreadPage::getPageNamespaceId(),
				'page_content_model' => CONTENT_MODEL_WIKITEXT
			],
			__METHOD__
		);

		$this->output( "Update of the content model for Page: pages is done.\n" );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getUpdateKey() {
		return 'FixPagePagesContentModel';
	}

}

$maintClass = FixProofreadPagePagesContentModel::class;
require_once RUN_MAINTENANCE_IF_MAIN;
