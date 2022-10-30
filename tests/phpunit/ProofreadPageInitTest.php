<?php

namespace ProofreadPage;

use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\ProofreadPageInit
 */
class ProofreadPageInitTest extends ProofreadPageTestCase {

	public function testInitNamespaceThrowsExceptionWhenNamespaceValueIsNotNumeric() {
		global $wgProofreadPageNamespaceIds;

		$oldValue = $wgProofreadPageNamespaceIds;

		$this->expectException( \MWException::class );
		try {
			$wgProofreadPageNamespaceIds['page'] = 'quux';
			$config = new \HashConfig( [
				'ProofreadPageNamespaceIds' => [
					'page' => 'quux'
				],
				'TemplateStylesNamespaces' => [
					'10' => true
				]
			] );
			$proofreadPageInit = new ProofreadPageInit( $config );
			$proofreadPageInit->onSetupAfterCache();
		} finally {
			$wgProofreadPageNamespaceIds = $oldValue;
		}
	}

	public function testGetNamespaceIdThrowsExceptionWhenKeyDoesNotExist() {
		global $wgProofreadPageNamespaceIds;

		$oldValue = $wgProofreadPageNamespaceIds;
		$this->expectException( \MWException::class );
		try {
			$wgProofreadPageNamespaceIds = [];
			ProofreadPageInit::getNamespaceId( 'page' );
		} finally {
			$wgProofreadPageNamespaceIds = $oldValue;
		}
	}

}
