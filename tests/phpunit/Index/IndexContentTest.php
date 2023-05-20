<?php

namespace ProofreadPage\Index;

use ProofreadPage\Context;
use ProofreadPage\Link;
use ProofreadPage\Pagination\PageList;
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

	protected function setUp(): void {
		parent::setUp();

		$this->requestContext = new RequestContext();
		$this->requestContext->setTitle( Title::makeTitle( $this->getIndexNamespaceId(), 'Test.pdf' ) );
		$this->requestContext->setUser( new User() );
	}

	public function testGetModel() {
		$content = new IndexContent( [] );
		$this->assertSame( CONTENT_MODEL_PROOFREAD_INDEX, $content->getModel() );
	}

	public function testGetFields() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertEquals( [ 'foo' => new WikitextContent( 'bar' ) ], $content->getFields() );
	}

	public function testGetCategoriesText() {
		$category = Title::makeTitle( NS_CATEGORY,  'Foo' );
		$content = new IndexContent( [], [ $category ] );
		$this->assertSame( [ $category ], $content->getCategories() );
	}

	public function testGetContentHandler() {
		$content = new IndexContent( [] );
		$this->assertSame(
			CONTENT_MODEL_PROOFREAD_INDEX, $content->getContentHandler()->getModelID()
		);
	}

	public function testCopy() {
		$content = new IndexContent(
			[ 'foo' => new WikitextContent( 'bar' ) ],
			[ Title::makeTitle( NS_CATEGORY,  'Cat' ) ]
		);
		$this->assertSame( $content, $content->copy() );
	}

	public static function isEmptyProvider() {
		return [
			[
				new IndexContent( [] )
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '' ) ] )
			]
		];
	}

	/**
	 * @dataProvider isEmptyProvider
	 */
	public function testIsEmpty( IndexContent $content ) {
		$this->assertTrue( $content->isEmpty() );
	}

	public static function isNotEmptyProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'Bar' ) ] )
			],
			[
				new IndexContent( [], [ Title::makeTitle( NS_CATEGORY, 'Foo' ) ] )
			]
		];
	}

	/**
	 * @dataProvider isNotEmptyProvider
	 */
	public function testIsNotEmpty( IndexContent $content ) {
		$this->assertFalse( $content->isEmpty() );
	}

	public static function isValidProvider() {
		return [
			[
				new IndexContent( [], [ Title::makeTitle( NS_CATEGORY, 'Foo' ) ] )
			]
		];
	}

	/**
	 * @dataProvider isValidProvider
	 */
	public function testIsValid( IndexContent $content ) {
		$this->assertTrue( $content->isValid() );
	}

	public static function isNotValidProvider() {
		return [
			[
				new IndexContent( [], [ Title::makeTitle( -4, 'Foo' ) ] )
			]
		];
	}

	/**
	 * @dataProvider isNotValidProvider
	 */
	public function testIsNotValid( IndexContent $content ) {
		$this->assertFalse( $content->isValid() );
	}

	public static function equalsProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] )
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( 'bar' ),
					'bar' => new WikitextContent( 'foo' )
				] ),
				new IndexContent( [
					'bar' => new WikitextContent( 'foo' ),
					'foo' => new WikitextContent( 'bar' )
				] )
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( 'bar' ) ],
					[
						Title::makeTitle( NS_CATEGORY, 'Foo' ),
						Title::makeTitle( NS_CATEGORY, 'Bar' )
					]
				),
				new IndexContent(
					[ 'foo' => new WikitextContent( 'bar' ) ],
					[
						Title::makeTitle( NS_CATEGORY, 'Bar' ),
						Title::makeTitle( NS_CATEGORY, 'Foo' )
					]
				)
			],
		];
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( IndexContent $a, IndexContent $b ) {
		$this->assertTrue( $a->equals( $b ) );
	}

	public static function notEqualsProvider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'baz' ) ] )
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [] )
			],
			[
				new IndexContent( [] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] )
			],
			[
				new IndexContent( [], [] ),
				new IndexContent( [], [ Title::makeTitle( NS_CATEGORY, 'Foo' ) ] )
			],
		];
	}

	/**
	 * @dataProvider notEqualsProvider
	 */
	public function testNotEquals( IndexContent $a, IndexContent $b ) {
		$this->assertFalse( $a->equals( $b ) );
	}

	public function testGetWikitextForTransclusion() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertSame(
			"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}",
			$content->getWikitextForTransclusion()
		);
	}

	public static function getTextForSummaryProvider() {
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
		$this->assertSame( $result, $content->getTextForSummary( $length ) );
	}

	public function testGetSize() {
		$content = new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] );
		$this->assertSame( 3, $content->getSize() );
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
			$content->getLinksToNamespace( NS_MAIN )
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
			$content->getLinksToNamespace( Context::getDefaultContext()->getPageNamespaceId() )
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

	public static function getPagelistTagContentProvider() {
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
