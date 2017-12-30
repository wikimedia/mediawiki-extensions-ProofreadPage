<?php

namespace ProofreadPage\Page;

use ContentHandler;
use FormatJson;
use MWContentSerializationException;
use ProofreadPageTestCase;
use Title;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContentHandler
 */
class PageContentHandlerTest extends ProofreadPageTestCase {

	/**
	 * @var ContentHandler
	 */
	protected $handler;

	public function setUp() {
		parent::setUp();

		$this->handler = ContentHandler::getForModelID( CONTENT_MODEL_PROOFREAD_PAGE );
	}

	private static function newContent(
		$header = '', $body = '', $footer = '', $level = 1, $proofreader = null
	) {
		return new PageContent(
			new WikitextContent( $header ), new WikitextContent( $body ), new WikitextContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	public function testCanBeUsedOn() {
		$this->assertTrue( $this->handler->canBeUsedOn(
			Title::makeTitle( $this->getPageNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn(
			Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn( Title::makeTitle( NS_MAIN, 'Test' ) ) );
	}

	public function pageWikitextSerializationProvider() {
		return [
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer',
				2,
				'1.2.3.4',
				'<noinclude>{{PageQuality|2|1.2.3.4}}<div class="pagetext">Experimental header' .
					"\n\n\n" .
					'</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'',
				2,
				'Woot',
				'<noinclude>{{PageQuality|2|Woot}}<div>Experimental header' . "\n\n\n" .
					'</noinclude>Experimental body</div>'
			],
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">' .
					'Experimental header' . "\n\n\n" .
					'</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot" /><div>Experimental header' .
					"\n\n\n" . '</noinclude>Experimental body</div>'
			],
			[
				'Experimental header',
				'Experimental <noinclude>body</noinclude>',
				'Experimental footer',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">' .
					'Experimental header' . "\n\n\n" . '</noinclude>Experimental <noinclude>' .
					'body</noinclude><noinclude>Experimental footer</div></noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">' .
					'Experimental header' . "\n" . '</noinclude>Experimental body<noinclude>' .
					'Experimental footer</div></noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot" />Experimental header' . "\n" .
					'</noinclude>Experimental body<noinclude>Experimental footer</noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot"></pagequality>Experimental header' .
					"\n" .
					'</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>'
			]
		];
	}

	/**
	 * @dataProvider pageWikitextSerializationProvider
	 */
	public function testSerializeContentInWikitext(
		$header, $body, $footer, $level, $proofreader
	) {
		$pageContent = self::newContent( $header, $body, $footer, $level, $proofreader );

		$serializedString = '<noinclude><pagequality level="' . $level . '" user="';
		$serializedString .= $proofreader;
		$serializedString .= '" />' . $header . '</noinclude>';
		$serializedString .= $body;
		$serializedString .= '<noinclude>' . $footer . '</noinclude>';

		$this->assertEquals( $serializedString, $this->handler->serializeContent( $pageContent ) );
	}

	/**
	 * @dataProvider pageWikitextSerializationProvider
	 */
	public function testUnserializeContentInWikitext(
		$header, $body, $footer, $level, $proofreader, $text
	) {
		$this->assertEquals(
			self::newContent( $header, $body, $footer, $level, $proofreader ),
			$this->handler->unserializeContent( $text )
		);
	}

	/**
	 * @dataProvider pageWikitextSerializationProvider
	 */
	public function testRoundTripSerializeContentInWikitext(
		$header, $body, $footer, $level, $proofreader, $text
	) {
		$content = self::newContent( $header, $body, $footer, $level, $proofreader );
		$this->assertEquals(
			$content,
			$this->handler->unserializeContent( $this->handler->serializeContent( $content ) )
		);
	}

	public function testSerializeContentInJson() {
		$pageContent = self::newContent( 'Foo', 'Bar', 'FooBar', 2, '1.2.3.4' );

		$this->assertEquals(
			FormatJson::encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [
					'level' => 2,
					'user' => '1.2.3.4'
				]
			] ),
			$this->handler->serializeContent( $pageContent, CONTENT_FORMAT_JSON )
		);
	}

	public function pageJsonSerializationProvider() {
		return [
			[ 'Foo', 'Bar', 'FooBar', 2, '1.2.3.4', FormatJson::encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [
					'level' => 2,
					'user' => '1.2.3.4'
				]
			] ) ],
			[ 'Foo', 'Bar', 'FooBar', 2, null, FormatJson::encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [
					'level' => '2'
				]
			] ) ]
		];
	}

	/**
	 * @dataProvider pageJsonSerializationProvider
	 */
	public function testUnserializeContentInJson(
		$header, $body, $footer, $level, $proofreader, $text
	) {
		$this->assertEquals(
			self::newContent( $header, $body, $footer, $level, $proofreader ),
			$this->handler->unserializeContent( $text )
		);
	}

	public function badPageJsonSerializationProvider() {
		return [
			[ '' ],
			[ '{}' ],
			[ FormatJson::encode( [
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ FormatJson::encode( [
				'header' => 'Foo',
				'footer' => 'FooBar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ FormatJson::encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ FormatJson::encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar'
			] ) ],
		];
	}

	/**
	 * @dataProvider badPageJsonSerializationProvider
	 * @expectedException MWContentSerializationException
	 */
	public function testUnserializeBadContentInJson( $text ) {
		$this->handler->unserializeContent( $text, CONTENT_FORMAT_JSON );
	}

	/**
	 * @dataProvider pageJsonSerializationProvider
	 */
	public function testRoundTripSerializeContentInJson(
		$header, $body, $footer, $level, $proofreader, $text
	) {
		$content = self::newContent( $header, $body, $footer, $level, $proofreader );
		$this->assertEquals(
			$content,
			$this->handler->unserializeContent(
				$this->handler->serializeContent( $content, CONTENT_FORMAT_JSON ),
				CONTENT_FORMAT_JSON
			)
		);
	}

	public function testMakeEmptyContent() {
		$content = $this->handler->makeEmptyContent();
		$this->assertTrue( $content->isEmpty() );
	}

	public static function merge3Provider() {
		return [
			[
				self::newContent( '', "first paragraph\n\nsecond paragraph\n" ),
				self::newContent( '', "FIRST paragraph\n\nsecond paragraph\n" ),
				self::newContent( '', "first paragraph\n\nSECOND paragraph\n" ),
				self::newContent( '', "FIRST paragraph\n\nSECOND paragraph\n" )
			],
			[
				self::newContent( '', "test\n" ),
				self::newContent( '', "dddd\n" ),
				self::newContent( '', "ffff\n" ),
				false
			],
			[
				self::newContent( '', "test\n", '', 1, 'John' ),
				self::newContent( '', "test2\n", '', 2, 'Jack' ),
				self::newContent( '', "test\n", '', 2, 'Bob' ),
				self::newContent( '', "test2\n", '', 2, 'Bob' ),
			],
			[
				self::newContent( '', "test\n", '', 1, 'John' ),
				self::newContent( '', "test\n", '', 2, 'Jack' ),
				self::newContent( '', "test\n", '', 1, 'Bob' ),
				false
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

	public static function getAutosummaryProvider() {
		return [
			[
				self::newContent( '', '', '', 1 ),
				self::newContent( 'aa', 'aa', 'aa', 1 ),
				''
			],
			[
				null,
				self::newContent( '', 'aaa', '', 1 ),
				'/* Not proofread */'
			],
			[
				self::newContent( '', '', '', 2 ),
				self::newContent( '', '', '', 1 ),
				'/* Not proofread */'
			]
		];
	}

	/**
	 * @dataProvider getAutosummaryProvider
	 */
	public function testGetAutosummary( $oldContent, $newContent, $expected ) {
		$this->assertEquals(
			$expected, $this->handler->getAutosummary( $oldContent, $newContent, [] )
		);
	}

	public function testMakeRedirectContent() {
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertTrue(
			$title->equals( $this->handler->makeRedirectContent( $title )->getRedirectTarget() )
		);
	}
}
