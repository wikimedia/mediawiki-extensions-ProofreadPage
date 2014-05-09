<?php

use ProofreadPage\Context;
use ProofreadPage\FileProvider;
use ProofreadPage\FileProviderMock;

/**
 * @group ProofreadPage
 */
abstract class ProofreadPageTestCase extends MediaWikiLangTestCase {

	/**
	 * @var Context
	 */
	private $context;

	protected function setUp() {
		global $wgProofreadPageNamespaceIds, $wgNamespacesWithSubpages;
		parent::setUp();

		$wgProofreadPageNamespaceIds = array(
			'page' => 250,
			'index' => 252
		);
		$wgNamespacesWithSubpages[NS_MAIN] = true;
	}

	/**
	 * @return Context
	 */
	protected function getContext() {
		if( $this->context === null ) {
			$this->context = new Context(
				250,
				252,
				$this->getFileProvider()
			);
		}

		return $this->context;
	}

	/**
	 * Returns a FileProvider that use files puts in data/media
	 *
	 * @return FileProvider
	 */
	private function getFileProvider() {
		return new FileProviderMock( $this->buildFileList() );
	}

	/**
	 * @return File[]
	 */
	private function buildFileList() {
		$backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'wikiId' => wfWikiId(),
			'containerPaths' => array( 'data' => __DIR__ . '/../data/media/' )
		) );
		$fileRepo = new FSRepo( array(
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $backend
		) );

		return array(
			new UnregisteredLocalFile(
				false,
				$fileRepo,
				'mwstore://localtesting/data/LoremIpsum.djvu',
				'image/x.djvu'
			),
			new UnregisteredLocalFile(
				false,
				$fileRepo,
				'mwstore://localtesting/data/Test.jpg',
				'image/jpg'
			)
		);
	}
}