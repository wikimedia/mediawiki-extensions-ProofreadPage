<?php

namespace ProofreadPage\Index;

use Content;
use ContentHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use ProofreadPageTestCase;
use RequestContext;
use SlotDiffRenderer;
use Title;
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

	protected function setUp() : void {
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

	public function wikitextSerializationProvider() {
		return [
			[
				new IndexContent( [] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar|baz}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}"
			],
			[
				new IndexContent( [], [ Title::makeTitle( NS_CATEGORY,  'Foo' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}\n[[Category:Foo]]"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::makeTitle( NS_CATEGORY,  'Foo' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}\n[[Category:Foo]]"
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				'#REDIRECT [[Foo]]'
			]
		];
	}

	/**
	 * @dataProvider wikitextSerializationProvider
	 */
	public function testSerializeContentInWikitext( Content $content, $serialization ) {
		$this->assertSame( $serialization, $this->handler->serializeContent( $content ) );
	}

	public function wikitextUnserializationProvider() {
		return [
			[
				new IndexContent( [] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}"
			],
			[
				new IndexContent( [] ),
				'foo'
			],
			[
				new IndexContent( [] ),
				''
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( ' a' ), 'bar' => new WikitextContent( ' z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n| foo = a\n| bar = z\n}}"
			],
			[
				new IndexContent( [
					'pagination' => new WikitextContent( '<pagelist />' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|pagination=<pagelist />|bar=z\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}{{foo|bar}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar}}\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{bar|baz=foo}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz=foo}}\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( "{{bar|bat=foo}}\n" ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|bat=foo}}\n\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( 'bar' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n|baz\n}}"
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '{{{param|}}}' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{{param|}}}\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{{param|}}}' ),
					'baz' => new WikitextContent( 'ddd' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{{param|}}}|baz=ddd\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{bar|bat=foo}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|bat=foo}}|bar=z\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[bar]]|bar=z\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '[[foo|bar]]' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[foo|bar]]|bar=z\n}}"
			],
			[
				new IndexContent( [
					'foo' => new WikitextContent( '{{baz|bat=[[foo|bar]]}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{baz|bat=[[foo|bar]]}}|bar=z}}"
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|header={{{pagenum}}}\n|bar=z}}\n}}"
			],
			[
				new IndexContent( [
					'header' => new WikitextContent( '{{{pagenum|}}}' ),
					'bar' => new WikitextContent( 'z' )
				] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|header={{{pagenum|}}}\n|bar=z}}\n}}"
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				'#REDIRECT [[Foo]]'
			],
			[
				new IndexContent( [] ),
				'#REDIRECT [[Special:UserLogout]]'
			],
			[
				new IndexContent( [ 'foo' => new WikitextContent( '#REDIRECT [[Foo]]' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=#REDIRECT [[Foo]]\n}}"
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo Bar' ) ] ),
				'[[Category:Foo Bar]]'
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo_Bar' ) ] ),
				'[[Category:Foo_Bar]]'
			],
			[
				new IndexContent( [], [ Title::newFromText( 'Category:Foo' ) ] ),
				"{{:MediaWiki:Proofreadpage_index_template\n}}\n[[Category:Foo]]"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}\n[[Category:Foo]]"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ) ]
				),
				"[[Category:Foo]]\n{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '{{bar|baz}}' ) ],
					[ Title::newFromText( 'Category:Foo' ), Title::newFromText( 'Category:Bar' ) ]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo={{bar|baz}}\n}}" .
				"\n[[Category:Foo]]\n[[Category:Bar]]"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( '[[Category:Foo]]' ) ],
					[]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=[[Category:Foo]]\n}}"
			],
			[
				new IndexContent(
					[ 'foo' => new WikitextContent( 'foo' ) ],
					[]
				),
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=foo\n}}\nblabla"
			],
		];
	}

	/**
	 * @dataProvider wikitextUnserializationProvider
	 */
	public function testUnserializeContentInWikitext( Content $content, $serialization ) {
		$this->assertEquals(
			$content,
			$this->handler->unserializeContent( $serialization )
		);
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

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );
		$this->assertInstanceOf( UpdateIndexQualityStats::class, $updates[0] );

		$updates = $handler->getSecondaryDataUpdates( $title, new WikitextContent( '' ), SlotRecord::MAIN, $srp );
		$this->assertInstanceOf( DeleteIndexQualityStats::class, $updates[0] );
	}

	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = new IndexContentHandler();

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertInstanceOf( DeleteIndexQualityStats::class, $updates[0] );
	}

}
