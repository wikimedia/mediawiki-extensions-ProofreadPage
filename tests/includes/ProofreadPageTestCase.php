<?php

/**
 * @group ProofreadPage
 */
abstract class ProofreadPageTestCase extends MediaWikiLangTestCase {
	protected function setUp() {
		global $wgProofreadPageNamespaceIds, $wgNamespacesWithSubpages;
		parent::setUp();

		$wgProofreadPageNamespaceIds =  array(
			'page' => 250,
			'index' => 252
		);
		$wgNamespacesWithSubpages[NS_MAIN] = true;
	}
}