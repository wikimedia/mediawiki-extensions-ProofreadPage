<?php

namespace ProofreadPage;

use ProofreadPageTestCase;

class ProofreadPageInitTest extends ProofreadPageTestCase {

	/**
	 * @expectedException \MWException
	 */
	public function testInitNamespaceThrowsExceptionWhenNamespaceValueIsNotNumeric() {
		global $wgProofreadPageNamespaceIds;

		$oldValue = $wgProofreadPageNamespaceIds;

		try {
			$wgProofreadPageNamespaceIds['page'] = 'quux';
			ProofreadPageInit::initNamespaces();
		} finally {
			$wgProofreadPageNamespaceIds = $oldValue;
		}
	}

	/**
	 * @expectedException \MWException
	 */
	public function testGetNamespaceIdThrowsExceptionWhenKeyDoesNotExist() {
		global $wgProofreadPageNamespaceIds;

		$oldValue = $wgProofreadPageNamespaceIds;
		try {
			$wgProofreadPageNamespaceIds = [];
			ProofreadPageInit::getNamespaceId( 'page' );
		} finally {
			$wgProofreadPageNamespaceIds = $oldValue;
		}
	}

}
