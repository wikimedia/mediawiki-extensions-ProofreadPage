<?php

namespace ProofreadPage;

use ProofreadPageTestCase;

class ProofreadPageInitTest extends ProofreadPageTestCase {

	/**
	 * @expectedException \MWException
	 */
	public function testInitNamespaceThrowsExceptionWhenNamespaceValueIsNotNumeric() {
		global $wgProofreadPageNamespaceIds;

		$wgProofreadPageNamespaceIds['page'] = 'quux';
		ProofreadPageInit::initNamespaces();
	}

	/**
	 * @expectedException \MWException
	 */
	public function testGetNamespaceIdThrowsExceptionWhenKeyDoesNotExist() {
		global $wgProofreadPageNamespaceIds;

		$wgProofreadPageNamespaceIds = [];
		ProofreadPageInit::getNamespaceId( 'page' );
	}

}
