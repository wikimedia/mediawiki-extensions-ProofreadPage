<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Content\WikitextContent;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\UnregisteredLocalFile;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use ProofreadPage\Context;
use ProofreadPage\FileProvider;
use ProofreadPage\FileProviderMock;
use ProofreadPage\Index\CustomIndexFieldsParser;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Index\IndexContentLookupMock;
use ProofreadPage\Index\IndexQualityStatsLookupMock;
use ProofreadPage\Page\IndexForPageLookupMock;
use ProofreadPage\Page\PageContent;
use ProofreadPage\Page\PageLevel;
use ProofreadPage\Page\PageQualityLevelLookupMock;
use ProofreadPage\ProofreadPageInit;
use Wikimedia\FileBackend\FSFileBackend;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ProofreadPage
 */
abstract class ProofreadPageTestCase extends MediaWikiLangTestCase {

	/** @var array */
	protected static $customIndexFieldsConfiguration = [
		'Title' => [
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Title',
			'values' => null,
			'header' => true,
			'data' => 'title',
		],
		'Author' => [
			'type' => 'page',
			'size' => 1,
			'default' => '',
			'label' => 'Author',
			'values' => null,
			'header' => true,
			'data' => 'author',
			'js' => true
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
			'header' => false,
			'data' => 'pagelist'
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
			'header' => false,
			'js' => true
		],
		'CSS' => [
			'type' => 'string',
			'label' => 'CSS',
			'header' => false,
			'js' => true
		],
	];

	/**
	 * @var FileProvider
	 */
	private static $fileProvider;

	protected function setUp(): void {
		parent::setUp();

		global $wgNamespacesWithSubpages;
		$wgNamespacesWithSubpages[NS_MAIN] = true;
		$config = new HashConfig( [
			'ProofreadPageNamespaceIds' => [
				'page' => 101
			],
			'TemplateStylesNamespaces' => [
				'10' => true
			]
		] );
		$mockServiceContainer = $this->createNoOpMock( MediaWikiServices::class, [ 'getMainConfig' ] );
		$mockServiceContainer->method( 'getMainConfig' )->willReturn( $config );
		$proofreadPageInit = new ProofreadPageInit();
		$proofreadPageInit->onMediaWikiServices( $mockServiceContainer );
	}

	/**
	 * @param Title[] $indexForPage
	 * @param IndexContent[] $indexContent
	 * @param int[] $levelForPage
	 * @param @param array<string,PagesQualityStats> $qualityStatsForIndex
	 * @return Context
	 */
	protected static function getContext(
		array $indexForPage = [], array $indexContent = [], array $levelForPage = [],
		array $qualityStatsForIndex = []
	) {
		return new Context(
			ProofreadPageInit::getNamespaceId( 'page' ),
			ProofreadPageInit::getNamespaceId( 'index' ),
			self::getFileProvider(),
			new CustomIndexFieldsParser( self::$customIndexFieldsConfiguration ),
			new IndexForPageLookupMock( $indexForPage ),
			new IndexContentLookupMock( $indexContent ),
			new PageQualityLevelLookupMock( $levelForPage ),
			new IndexQualityStatsLookupMock( $qualityStatsForIndex )
		);
	}

	/**
	 * @return int
	 */
	protected static function getPageNamespaceId() {
		return self::getContext()->getPageNamespaceId();
	}

	/**
	 * @return int
	 */
	protected static function getIndexNamespaceId() {
		return self::getContext()->getIndexNamespaceId();
	}

	/**
	 * Returns a FileProvider that use files puts in data/media
	 *
	 * @return FileProvider
	 */
	private static function getFileProvider() {
		if ( self::$fileProvider === null ) {
			self::$fileProvider = new FileProviderMock( self::buildFileList() );
		}
		return self::$fileProvider;
	}

	/**
	 * @return File[]
	 */
	protected static function buildFileList() {
		$backend = new FSFileBackend( [
			'name' => 'localtesting',
			'wikiId' => WikiMap::getCurrentWikiId(),
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
				'mwstore://localtesting/data/LoremIpsum.jpg',
				'image/jpg'
			),
			new UnregisteredLocalFile(
				false,
				$fileRepo,
				'mwstore://localtesting/data/Test.jpg',
				'image/jpg'
			)
		];
	}

	/**
	 * Build a page content object with the given values and status level
	 * @param string $header the header wikitext string
	 * @param string $body the body wikitext string
	 * @param string $footer the footer wikitext string
	 * @param int $level the proofreading levelA
	 * @param string|null $proofreader the proofreading user name
	 * @return PageContent the built page content
	 */
	protected static function buildPageContent(
		string $header = '', string $body = '', string $footer = '',
		int $level = PageLevel::NOT_PROOFREAD, ?string $proofreader = null
	) {
		return new PageContent(
			new WikitextContent( $header ), new WikitextContent( $body ), new WikitextContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	/**
	 * Helper to obtain a Title in the Page: namespace with the page language set to English, to avoid DB lookups.
	 *
	 * @param string $titleText
	 * @return Title
	 */
	protected function makeEnglishPagePageTitle( string $titleText ): Title {
		$ret = Title::makeTitle( self::getPageNamespaceId(), $titleText );
		$wrapper = TestingAccessWrapper::newFromObject( $ret );
		$wrapper->mPageLanguage = [ 'en', 'en' ];
		return $wrapper->object;
	}
}
