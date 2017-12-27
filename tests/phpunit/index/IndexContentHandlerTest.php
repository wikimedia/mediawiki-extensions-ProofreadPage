<?php

namespace ProofreadPage\Index;

use Content;
use ContentHandler;
use ProofreadPageTestCase;
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

	public function setUp() {
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
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				'#REDIRECT [[Foo]]'
			]
		];
	}

	/**
	 * @dataProvider wikitextSerializationProvider
	 */
	public function testSerializeContentInWikitext( Content $content, $serialization ) {
		$this->assertEquals( $serialization, $this->handler->serializeContent( $content ) );
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
				"{{:MediaWiki:Proofreadpage_index_template\n|foo=bar\n}}[[Category:XXX]]"
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
}
