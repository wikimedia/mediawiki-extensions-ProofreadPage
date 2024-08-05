<?php

namespace ProofreadPage\Index;

use Content;
use ContentHandler;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use MWContentSerializationException;
use ParserOptions;
use ProofreadPage\Context;
use ProofreadPage\Pagination\PaginationFactory;
use ProofreadPageTestCase;
use SlotDiffRenderer;
use StatusValue;
use TextContent;
use Wikimedia\TestingAccessWrapper;
use WikiPage;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\IndexContentHandler
 */
class IndexContentHandlerTest extends ProofreadPageTestCase {

	/**
	 * @var ContentHandler
	 */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->handler = new IndexContentHandler();
	}

	public function testCanBeUsedOn() {
		$this->assertTrue( $this->handler->canBeUsedOn(
			Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn( Title::makeTitle( NS_MAIN, 'Test' ) ) );
	}

	public static function wikitextSerializationProvider() {
		return [
			[
				new IndexContent( [] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}",
				'{"fields":[],"categories":[]}',
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}",
				'{"fields":{"foo":"bar"},"categories":[]}',
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar|baz}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}",
				'{"fields":{"foo":"{{bar|baz}}"},"categories":[]}'
			],
			[
				new IndexContent( [], [ Title::makeTitle( NS_CATEGORY, 'Foo' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}\n[[Category:Foo]]",
				'{"fields":[],"categories":["Foo"]}'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::makeTitle( NS_CATEGORY, 'Foo' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}\n[[Category:Foo]]",
				'{"fields":{"foo":"{{bar|baz}}"},"categories":["Foo"]}'
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				'#REDIRECT [[Foo]]',
				/* throws */
				null
			]
		];
	}

	/**
	 * Check that IndexContent serialises correctly to wikitext and JSON
	 * @dataProvider wikitextSerializationProvider
	 */
	public function testSerializeContent( Content $content,
		?string $wikitextSerialization, ?string $jsonSerialization ) {
		if ( $wikitextSerialization !== null ) {
			$this->assertSame( $wikitextSerialization,
				$this->handler->serializeContent( $content ) );
		}

		if ( $jsonSerialization !== null ) {
			$this->assertSame( $jsonSerialization,
				$this->handler->serializeContent( $content, CONTENT_FORMAT_JSON ) );
		}
	}

	public static function jsonExceptionSerializationProvider() {
		return [
			'Redirects not supported in JSON' => [
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
			],
		];
	}

	/**
	 * Check that IndexContent that should raise an Exception does
	 * @dataProvider jsonExceptionSerializationProvider
	 */
	public function testJsonSerialisationExceptions( TextContent $content ) {
		$this->expectException( MWContentSerializationException::class );
		$this->handler->serializeContent( $content, CONTENT_FORMAT_JSON );
	}

	public static function wikitextUnserializationProvider() {
		return [
			[
				new IndexContent( [] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}",
			],
			[
				new IndexContent( [] ),
				'foo',
			],
			[
				new IndexContent( [] ),
				'',

			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( ' a' ),
					'bar' => new WikitextContent( ' z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n| foo = a\n| bar = z\n}}",
			],
			[
				new IndexContent( [
					'pagination' => new WikitextContent( '<pagelist />' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|pagination=<pagelist />|bar=z\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}{{foo|bar}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar}}\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar|baz=foo}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz=foo}}\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( "{{bar|bat=foo}}\n" ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|bat=foo}}\n\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n|baz\n}}",
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{{param|}}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{{param|}}}\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{{param|}}}' ),
					'baz' => new WikitextContent( 'ddd' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{{param|}}}|baz=ddd\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{bar|bat=foo}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|bat=foo}}|bar=z\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[bar]]|bar=z\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[foo|bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[foo|bar]]|bar=z\n}}",
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{baz|bat=[[foo|bar]]}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{baz|bat=[[foo|bar]]}}|bar=z}}",
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|header={{{pagenum}}}\n|bar=z}}\n}}",
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum|}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|header={{{pagenum|}}}\n|bar=z}}\n}}",
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				'#REDIRECT [[Foo]]',
			],
			[
				new IndexContent( [] ),
				'#REDIRECT [[Special:UserLogout]]',
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '#REDIRECT [[Foo]]' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=#REDIRECT [[Foo]]\n}}",
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo Bar' ) ] ),
				'[[Category:Foo Bar]]',
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo_Bar' ) ] ),
				'[[Category:Foo_Bar]]',
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}\n[[Category:Foo]]",
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}\n[[Category:Foo]]",
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				"[[Category:Foo]]\n{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}",
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ), Title::newFromText( 'Category:Bar' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}" .
				"\n[[Category:Foo]]\n[[Category:Bar]]",
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '[[Category:Foo]]' ) ],
					[]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[Category:Foo]]\n}}",
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( 'foo' ) ],
					[]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=foo\n}}\nblabla",
			],
		];
	}

	/**
	 * Test that content in wikitext unserializes correctly
	 * @dataProvider wikitextUnserializationProvider
	 */
	public function testUnserializeWikitextContent( Content $expectedContent, ?string $wikitextSerialization ) {
		$fromWikitext = $this->handler->unserializeContent( $wikitextSerialization );
		$this->assertEquals( $expectedContent, $fromWikitext );
	}

	public static function jsonUnserializationProvider() {
		return [
			[
				new IndexContent( [] ),
				"{}"
			],
			[
				new IndexContent( [] ),
				'[]'

			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				'{"fields": {"foo": "bar"} }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( ' a' ),
					'bar' => new WikitextContent( ' z' )
				] ),
				'{"fields": {"foo": " a", "bar": " z"} }'
			],
			[
				new IndexContent( [
					'pagination' => new WikitextContent( '<pagelist />' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"pagination": "<pagelist />", "bar": "z"} }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				'{"fields": {"foo": "bar" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				'{"fields": {"foo": "bar" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar}}' ) ] ),
				'{"fields": {"foo": "{{bar}}" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar|baz=foo}}' ) ] ),
				'{"fields": {"foo": "{{bar|baz=foo}}" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( "{{bar|bat=foo}}\n" ) ] ),
				'{"fields": {"foo": "{{bar|bat=foo}}\\n" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{{param|}}}' ) ] ),
				'{"fields": {"foo": "{{{param|}}}" } }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{{param|}}}' ),
					'baz' => new WikitextContent( 'ddd' )
				] ),
				'{"fields": {"foo": "{{{param|}}}", "baz": "ddd" } }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{bar|bat=foo}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"foo": "{{bar|bat=foo}}", "bar": "z" } }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"foo": "[[bar]]", "bar": "z" } }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[foo|bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"foo": "[[foo|bar]]", "bar": "z" } }'
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{baz|bat=[[foo|bar]]}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"foo": "{{baz|bat=[[foo|bar]]}}", "bar": "z" } }'
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"header": "{{{pagenum}}}", "bar": "z" } }'
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum|}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				'{"fields": {"header": "{{{pagenum|}}}", "bar": "z" } }'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '#REDIRECT [[Foo]]' ) ] ),
				'{"fields": {"foo": "#REDIRECT [[Foo]]" } }'
			],
			[
				/* check empty category list */
				new IndexContent( [], [] ),
				'{ "categories": [] }'
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo Bar' ) ] ),
				'{ "categories": [ "Foo Bar" ] }'
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo_Bar' ) ] ),
				'{ "categories": [ "Foo_Bar" ] }'
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo' ) ] ),
				'{ "fields": {}, "categories": [ "Foo" ] }'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				'{ "fields": { "foo": "{{bar|baz}}" }, "categories": [ "Foo" ] }'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				'{ "fields": { "foo": "{{bar|baz}}" }, "categories": [ "Foo" ] }'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ), Title::newFromText( 'Category:Bar' ) ]
				),
				'{ "fields": { "foo": "{{bar|baz}}" }, "categories": [ "Foo", "Bar" ] }'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '[[Category:Foo]]' ) ],
					[]
				),
				'{ "fields": { "foo": "[[Category:Foo]]" } }'
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( 'bar' ) ],
					[]
				),
				'{ "fields": { "foo": "bar" } }'
			],
			[
				/* empty-string field */
				new IndexContent(
					[ 'foo' => new WikitextContent( '' ) ],
					[]
				),
				'{ "fields": { "foo": "" } }'
			],
		];
	}

	/**
	 * Test that content in JSON unserializes correctly
	 * @dataProvider jsonUnserializationProvider
	 */
	public function testUnserializeJsonContent( Content $expectedContent, ?string $jsonSerialization ) {
		$unserialized = $this->handler->unserializeContent( $jsonSerialization );
		$this->assertTrue( $expectedContent->equals( $unserialized ) );
	}

	public static function jsonExceptionUnserializationProvider() {
		return [
			'Empty string' => [
				'',
				"The serialization is an invalid JSON array.",
			],
			'Literal' => [
				'"foo"',
				"The serialization is an invalid JSON array.",
			],
			'Categories as a literal' => [
				'{ "categories": "foo" }',
				"The serialization key 'categories' should be an array."
			],
			'Categories as an object' => [
				'{ "categories": { "foo": "bar" } }',
				"The array 'categories' should be a sequential array."
			],
			'Categories not strings' => [
				'{ "categories": [ [] ] }',
				"The array 'categories' should contain only non-empty strings."
			],
			'Categories contain empty strings' => [
				'{ "categories": [ "foo", "" ] }',
				"The array 'categories' should contain only non-empty strings."
			],
			'Categories contain illegal categories' => [
				'{ "categories": [ "foo", "_" ] }',
				"The category title '_' is invalid."
			],
			'Fields as a literal' => [
				'{ "fields": "foo" }',
				"The serialization key 'fields' should be an array."
			],
			'Invalid field value' => [
				'{"fields": {"foo": "bar", "baz": null } }',
				"The array 'fields' should contain only strings."
			],
		];
	}

	/**
	 * Check that JSON that should provide an exception does so
	 * @dataProvider jsonExceptionUnserializationProvider
	 */
	public function testJsonUnserialisationExceptions( string $jsonSerialization,
		string $expectedMessage ) {
		$this->expectException( MWContentSerializationException::class );
		$this->expectExceptionMessage( $expectedMessage );

		$fromJson = $this->handler->unserializeContent(
			$jsonSerialization, CONTENT_FORMAT_JSON );
	}

	public function testMakeEmptyContent() {
		$content = $this->handler->makeEmptyContent();
		$this->assertTrue( $content->isEmpty() );
	}

	public static function merge3Provider() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] )
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] )
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'baz' ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( 'bat' ) ] ),
				false
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( "test\n" ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( "test2\n" ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( "test\n" ) ] ),
				new IndexContent( [ 'foo' => new WikitextContent( "test2\n" ) ] )
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo' ) ] ),
				new IndexContent( [], [ Title::newFromText( 'Category:Bar' ) ] ),
				new IndexContent( [], [ Title::newFromText( 'Category:Baz' ) ] ),
				new IndexContent( [], [
					Title::newFromText( 'Category:Bar' ),
					Title::newFromText( 'Category:Baz' )
				] ),
			],
		];
	}

	/**
	 * @dataProvider merge3Provider
	 */
	public function testMerge3( $oldContent, $myContent, $yourContent, $expected ) {
		$this->markTestSkippedIfNoDiff3();

		$merged = $this->handler->merge3( $oldContent, $myContent, $yourContent );

		$this->assertEquals( $expected, $merged );
	}

	public function testMakeRedirectContent() {
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertTrue(
			$title->equals( $this->handler->makeRedirectContent( $title )->getRedirectTarget() )
		);
	}

	public function testGetSlotDiffRenderer() {
		$this->assertInstanceOf(
			SlotDiffRenderer::class,
			$this->handler->getSlotDiffRenderer( RequestContext::getMain() )
		);
	}

	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new IndexContent( [] );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = new IndexContentHandler();

		// Super-ugly hack: avoid unnecessary DB access. There doesn't seem to be a prettier way to achieve this.
		$defaultCtxWrapper = TestingAccessWrapper::newFromObject( Context::getDefaultContext() );
		$defaultCtxWrapper->paginationFactory = $this->createMock( PaginationFactory::class );
		try {
			$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );
			$this->assertInstanceOf( UpdateIndexQualityStats::class, $updates[0] );

			$updates = $handler->getSecondaryDataUpdates( $title, new WikitextContent( '' ), SlotRecord::MAIN, $srp );
			$this->assertInstanceOf( DeleteIndexQualityStats::class, $updates[0] );
		} finally {
			// Reset the default context.
			Context::getDefaultContext( true );
		}
	}

	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = new IndexContentHandler();

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertInstanceOf( DeleteIndexQualityStats::class, $updates[0] );
	}

	public static function providePreSaveTransform() {
		return [
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'Hello ~~~' ) ] ),
				new IndexContent( [
					'foo' => new WikitextContent(
						"Hello [[Special:Contributions/123.123.123.123|123.123.123.123]]"
					)
				] )
			]
		];
	}

	/**
	 * @dataProvider providePreSaveTransform
	 */
	public function testPreSaveTransform( TextContent $content, TextContent $expectedContent ) {
		$services = $this->getServiceContainer();
		$user = UserIdentityValue::newAnonymous( '123.123.123.123' );
		$options = ParserOptions::newFromUser( $user );

		$contentTransformer = $services->getContentTransformer();
		$newContent = $contentTransformer->preSaveTransform(
			$content,
			PageReferenceValue::localReference( $this->getIndexNamespaceId(), 'Test.pdf' ),
			$user,
			$options
		);

		$this->assertTrue( $newContent->equals( $expectedContent ) );
	}

	public static function providePreloadTransform() {
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
	 * @dataProvider providePreloadTransform
	 */
	public function testPreloadTransform( IndexContent $content, IndexContent $expectedContent ) {
		$services = $this->getServiceContainer();
		$options = ParserOptions::newFromAnon();

		$contentTransformer = $services->getContentTransformer();
		$newContent = $contentTransformer->preloadTransform(
			$content,
			PageReferenceValue::localReference( $this->getIndexNamespaceId(), 'Test.pdf' ),
			$options
		);

		$this->assertEquals( $expectedContent, $newContent );
	}

	public static function provideValidateSave() {
		return [
			[
				StatusValue::newGood(),
				new IndexContent( [] )
			],
			[
				StatusValue::newFatal( 'proofreadpage_indexdupetext' ),
				new IndexContent( [
					'page' => new WikitextContent( '[[Page:Foo]] [[Page:Bar]] [[Page:Foo]]' )
				] )
			],
			[
				StatusValue::newGood(),
				new IndexRedirectContent( Title::newFromText( 'Redirect' ) )
			]
		];
	}

	/**
	 * @dataProvider provideValidateSave
	 */
	public function testValidateSave( StatusValue $expectedResult, TextContent $content ) {
		/** @var MockObject|WikiPage $wikiPage */
		$wikiPage = $this->getMockBuilder( WikiPage::class )
			->disableOriginalConstructor()
			->getMock();
		$handler = new IndexContentHandler();
		$validationParams = new ValidationParams( $wikiPage, 0 );
		$this->assertEquals(
			$expectedResult,
			$handler->validateSave( $content, $validationParams )
		);
	}
}
