<?php

use ProofreadPage\Context;
use ProofreadPage\FileProvider;
use ProofreadPage\FileProviderMock;
use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Index\IndexContentLookupMock;
use ProofreadPage\Page\IndexForPageLookupMock;
use ProofreadPage\ProofreadPageInit;

/**
 * @group ProofreadPage
 */
abstract class ProofreadPageTestCase extends MediaWikiLangTestCase {

	protected static $customIndexFieldsConfiguration = [
		'Title' => [
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Title',
			'values' => null,
			'header' => true,
			'data' => 'title'
		],
		'Author' => [
			'type' => 'page',
			'size' => 1,
			'default' => '',
			'label' => 'Author',
			'values' => null,
			'header' => true,
			'data' => 'author'
		],
		'Year' => [
			'type' => 'number',
			'size' => 1,
			'default' => '',
			'label' => 'Year of publication',
			'values' => null,
			'header' => false,
			'data' => 'year'
		],
		'Pages' => [
			'type' => 'string',
			'size' => 20,
			'default' => '',
			'label' => 'Pages',
			'values' => null,
			'header' => false
		],
		'Header' => [
			'type' => 'string',
			'size' => 10,
			'default' => 'head',
			'label' => 'Header',
			'values' => null,
			'header' => false
		],
		'Footer' => [
			'default' => '<references />',
			'header' => true,
			'hidden' => true
		],
		'TOC' => [
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Table of content',
			'values' => null,
			'header' => false
		],
		'Comment' => [
			'header' => true,
			'hidden' => true
		],
		'width' => [
			'type' => 'number',
			'label' => 'Image width',
			'header' => false
		],
		'CSS' => [
			'type' => 'string',
			'label' => 'CSS',
			'header' => false
		],
	];

	/**
	 * @var FileProvider
	 */
	private $fileProvider;

	protected function setUp() {
		parent::setUp();

		global $wgNamespacesWithSubpages;
		$wgNamespacesWithSubpages[NS_MAIN] = true;
		ProofreadPageInit::initNamespaces();
	}

	/**
	 * @param Title[] $indexForPage
	 * @param IndexContent[] $indexContent
	 * @return Context
	 */
	protected function getContext( array $indexForPage = [], array $indexContent = [] ) {
		return new Context(
			ProofreadPageInit::getNamespaceId( 'page' ),
			ProofreadPageInit::getNamespaceId( 'index' ),
			$this->getFileProvider(),
			new CustomIndexFieldsParser( self::$customIndexFieldsConfiguration ),
			new IndexForPageLookupMock( $indexForPage ),
			new IndexContentLookupMock( $indexContent )
		);
	}

	/**
	 * @return int
	 */
	protected function getPageNamespaceId() {
		return $this->getContext()->getPageNamespaceId();
	}

	/**
	 * @return int
	 */
	protected function getIndexNamespaceId() {
		return $this->getContext()->getIndexNamespaceId();
	}

	/**
	 * Returns a FileProvider that use files puts in data/media
	 *
	 * @return FileProvider
	 */
	private function getFileProvider() {
		if ( $this->fileProvider === null ) {
			$this->fileProvider = new FileProviderMock( $this->buildFileList() );
		}
		return $this->fileProvider;
	}

	/**
	 * @return File[]
	 */
	protected function buildFileList() {
		$backend = new FSFileBackend( [
			'name' => 'localtesting',
			'wikiId' => wfWikiID(),
			'containerPaths' => [ 'data' => __DIR__ . '/../data/media/' ]
		] );
		$fileRepo = new FileRepo( [
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $backend
		] );

		return [
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
		];
	}
}
