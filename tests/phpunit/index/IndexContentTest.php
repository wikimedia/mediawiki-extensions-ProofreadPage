<?php

namespace ProofreadPage\Index;

use FauxRequest;
use ParserOptions;
use ProofreadPageTestCase;
use RequestContext;
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
		$this->requestContext->setTitle( Title::makeTitle( 252, 'Test.pdf' ) );
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
		return $this->assertEquals(
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

	public function testGetSize() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals( 3, $content->getSize() );
	}
}
