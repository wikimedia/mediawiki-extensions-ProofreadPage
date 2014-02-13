<?php

namespace ProofreadPage\Page;

use FileRepo;
use FSFileBackend;
use FSRepo;
use IContextSource;
use ProofreadIndexPageTest;
use ProofreadPage\FileProvider;
use ProofreadPage\FileProviderMock;
use ProofreadPageContent;
use ProofreadPageContentTest;
use ProofreadPagePage;
use ProofreadPagePageTest;
use ProofreadPageTestCase;
use RequestContext;
use UnregisteredLocalFile;
use User;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContentBuilder
 */
class ProofreadPageContentBuilderTest extends ProofreadPageTestCase {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @var FileRepo
	 */
	private $fileRepo;

	/**
	 * @var FileProvider
	 */
	private $fileProvider;

	protected function setUp() {
		parent::setUp();

		$this->context = new RequestContext();
		$this->context->setUser( User::newFromName( 'Test' ) );

		$backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'wikiId' => wfWikiId(),
			'containerPaths' => array( 'data' => __DIR__ . '/../../data/media/' )
		) );
		$this->fileRepo = new FSRepo( array(
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $backend
		) );

		$this->fileProvider = new FileProviderMock( array(
			$this->getDataFile( 'LoremIpsum.djvu', 'image/x.djvu' )
		) );
	}

	protected function getDataFile( $name, $type ) {
		return new UnregisteredLocalFile(
			false,
			$this->fileRepo,
			'mwstore://localtesting/data/' . $name,
			$type
		);
	}

	/**
	 * @dataProvider buildDefaultContentForPageProvider
	 */
	public function testBuildDefaultContentForPage( ProofreadPagePage $page, ProofreadPageContent $defaultContent ) {
		$contentBuilder = new PageContentBuilder( $this->context, $this->fileProvider );
		$this->assertEquals( $defaultContent, $contentBuilder->buildDefaultContentForPage( $page ) );
	}

	public function buildDefaultContentForPageProvider() {
		return array(
			array(
				ProofreadPagePageTest::newPagePage(
					'Test.djvu/1',
					ProofreadIndexPageTest::newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Header={{{title}}}\n}}" )
				),
				ProofreadPageContentTest::newContent( 'Test book', '', '<references />', 1 ),
			),
			array(
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2'
				),
				ProofreadPageContentTest::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			),
			//TODO: test pagenum argument
		);
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput( $header, $body, $footer, $level, ProofreadPageContent $oldContent, ProofreadPageContent $newContent ) {
		$contentBuilder = new PageContentBuilder( $this->context, $this->fileProvider );
		$this->assertEquals( $newContent, $contentBuilder->buildContentFromInput( $header, $body, $footer, $level, $oldContent ) );
	}

	public function buildContentFromInputProvider() {
		return array(
			array(
				'42',
				'42',
				'42',
				2,
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 2, 'Test2' ),
			),
			array(
				'42',
				'42',
				'42',
				2,
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, null ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 2, 'Test' ),
			),
			array(
				'42',
				'42',
				'42',
				3,
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 3, 'Test' ),
			),
		);
	}
}