<?php

namespace ProofreadPage\Index;

use FauxRequest;
use ParserOptions;
use ProofreadPage\Context;
use ProofreadPage\Link;
use ProofreadPage\Pagination\PageList;
use ProofreadPageTestCase;
use RequestContext;
use Status;
use Title;
use User;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\IndexContent
 */
class IndexContentTest extends ProofreadPageTestCase {

	/**
	 * @var RequestContext
	 */
	private $requestContext;

	protected function setUp() {
		parent::setUp();

		$this->requestContext = new RequestContext( new FauxRequest() );
		$this->requestContext->setTitle( Title::makeTitle( $this->getIndexNamespaceId(), 'Test.pdf' ) );
		$this->requestContext->setUser( new User() );
	}

	public function testGetModel() {
		$content = new IndexContent( [] );
		$this->assertEquals( CONTENT_MODEL_PROOFREAD_INDEX, $content->getModel() );
	}

	public function testGetFields() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals( [ 'foo' => new WikitextContent( 'bar' ) ], $content->getFields() );
	}

	public function testGetContentHandler() {
		$content = new IndexContent( [] );
		$this->assertEquals(
			CONTENT_MODEL_PROOFREAD_INDEX, $content->getContentHandler()->getModelID()
		);
	}

	public function testCopy() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals( $content, $content->copy() );
	}

	public function equalsProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				true
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( 'bar' ),
					'bar' => new WikitextContent( 'foo' )
				] ),
				new IndexContent( [
					'bar' => new WikitextContent( 'foo' ),
					'foo' => new WikitextContent( 'bar' )
				] ),
				true
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'baz' ) ] ),
				false
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [] ),
				false
			],
			[
				new IndexContent( [] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				false
			],
		];
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( IndexContent $a, IndexContent $b, $equal ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public function testGetWikitextForTransclusion() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals(
			"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}",
			$content->getWikitextForTransclusion()
		);
	}

	public function getTextForSummaryProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				16,
				''
			]
		];
	}

	/**
	 * @dataProvider getTextForSummaryProvider
	 */
	public function testGetTextForSummary( IndexContent $content, $length, $result ) {
		$this->assertEquals( $result, $content->getTextForSummary( $length ) );
	}

	public function preSaveTransformProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'Hello ~~~' ) ] ),
				new IndexContent( [
					'foo' => new WikitextContent(
						'Hello [[Special:Contributions/127.0.0.1|127.0.0.1]]'
					)
				] )
			],
		];
	}

	/**
	 * @dataProvider preSaveTransformProvider
	 */
	public function testPreSaveTransform( IndexContent $content, IndexContent $expectedContent ) {
		global $wgContLang;

		$options = ParserOptions::newFromUserAndLang(
			$this->requestContext->getUser(), $wgContLang
		);

		$content = $content->preSaveTransform(
			$this->requestContext->getTitle(), $this->requestContext->getUser(), $options
		);

		$this->assertEquals( $expectedContent, $content );
	}

	public function preloadTransformProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'hello this is ~~~' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'hello this is ~~~' ) ] )
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent(
					'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>'
				) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'hello \'\'this\'\' is bar' ) ] )
			],
		];
	}

	/**
	 * @dataProvider preloadTransformProvider
	 */
	public function testPreloadTransform( IndexContent $content, IndexContent $expectedContent ) {
		global $wgContLang;

		$options = ParserOptions::newFromUserAndLang(
			$this->requestContext->getUser(), $wgContLang
		);

		$content = $content->preloadTransform( $this->requestContext->getTitle(), $options );

		$this->assertEquals( $expectedContent, $content );
	}

	public function prepareSaveProvider() {
		return [
			[
				Status::newGood(),
				new IndexContent( [] )
			],
			[
				Status::newFatal( 'proofreadpage_indexdupetext' ),
				new IndexContent( [
					'page' => new WikitextContent( '[[Page:Foo]] [[Page:Bar]] [[Page:Foo]]' )
				] )
			]
		];
	}

	/**
	 * @dataProvider prepareSaveProvider
	 */
	public function testPrepareSave( Status $expectedResult, IndexContent $content ) {
		$this->assertEquals( $expectedResult, $content->prepareSave(
			$this->requestContext->getWikiPage(), 0, -1, $this->requestContext->getUser()
		) );
	}

	public function testGetSize() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals( 3, $content->getSize() );
	}

	public function testGetLinksToMainNamespace() {
		$content = new IndexContent( [
			'Pages' => new WikitextContent( '[[Page:Test.jpg]]' ),
			'TOC' => new WikitextContent( "* [[Test/Chapter 1]]\n* [[Azerty:Test/Chapter_2|Chapter 2]]" ),
		] );
		$links = [
			new Link( Title::newFromText( 'Test/Chapter 1' ), 'Chapter 1' ),
			new Link( Title::newFromText( 'Azerty:Test/Chapter_2' ), 'Chapter 2' )
		];
		$this->assertEquals(
			$links,
			$content->getLinksToNamespace( NS_MAIN, null, true )
		);
	}

	public function testGetLinksToPageNamespace() {
		$content = new IndexContent( [
			'Pages' => new WikitextContent(
				'[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test:3.png|2]]'
			),
			'Author' => new WikitextContent( '[[Author:Me]]' ),
		] );
		$links = [
			new Link( Title::newFromText( 'Page:Test 1.jpg' ), 'TOC' ),
			new Link( Title::newFromText( 'Page:Test 2.tiff' ), '1' ),
			new Link( Title::newFromText( 'Page:Test:3.png' ), '2' )
		];
		$this->assertEquals(
			$links,
			$content->getLinksToNamespace(
				Context::getDefaultContext()->getPageNamespaceId(),
				Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
			)
		);
	}

	/**
	 * @dataProvider getPagelistTagContentProvider
	 */
	public function testGetPagelistTagContent(
		IndexContent $content, PageList $pageList = null
	) {
		$this->assertEquals( $pageList, $content->getPagelistTagContent() );
	}

	public function getPagelistTagContentProvider() {
		return [
			[
				new IndexContent( [ 'Pages' => new WikitextContent(
					'<pagelist to=24 1to4=- 5=1 5to24=roman />' .
					'<pagelist from=25 25=1 1021to1024=- />\n|Author=[[Author:Me]]\n}}'
				) ] ),
				new PageList( [
					'1to4' => '-',
					'5' => '1',
					'5to24' => 'roman',
					'25' => '1',
					'1021to1024' => '-',
					'to' => 24,
					'from' => 25
				] )
			],
			[
				new IndexContent( [
					'Pages' => new WikitextContent( '<pagelist/>' ),
					'Author' => new WikitextContent( '[[Author:Me]]' )
				] ),
				new PageList( [] )
			],
			[
				new IndexContent( [
					'Pages' => new WikitextContent( '' ),
					'Author' => new WikitextContent( '[[Author:Me]]' )
				] ),
				null
			]
		];
	}
}
