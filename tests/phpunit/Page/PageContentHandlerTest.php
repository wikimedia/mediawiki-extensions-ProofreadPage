<?php

namespace ProofreadPage\Page;

use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use ProofreadPageTestCase;
use SlotDiffRenderer;

/**
 * @group ProofreadPage
 * @group Database
 * @covers \ProofreadPage\Page\PageContentHandler
 */
class PageContentHandlerTest extends ProofreadPageTestCase {

	/**
	 * @var ContentHandler
	 */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->handler = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_PROOFREAD_PAGE );
	}

	private static function newContent(
		$header = '', $body = '', $footer = '', $level = 1, $proofreader = null
	) {
		$user = PageLevel::getUserFromUserName( $proofreader );
		return new PageContent(
			new WikitextContent( $header ), new WikitextContent( $body ), new WikitextContent( $footer ),
			new PageLevel( $level, $user )
		);
	}

	public function testCanBeUsedOn() {
		$this->assertTrue( $this->handler->canBeUsedOn(
			Title::makeTitle( self::getPageNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn(
			Title::makeTitle( self::getIndexNamespaceId(), 'Test' )
		) );
		$this->assertFalse( $this->handler->canBeUsedOn( Title::makeTitle( NS_MAIN, 'Test' ) ) );
	}

	public static function pageWikitextSerializationProvider() {
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
				'<div style="color: red;">',
				'Experimental body',
				'</div>',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot"></pagequality><div style="color: red;">' .
					"\n" .
					'</noinclude>Experimental body<noinclude></div></noinclude>'
			],
			[
				'Experimental header',
				'Experimental body',
				'Experimental footer</div>',
				2,
				'Woot',
				'<noinclude><pagequality level="2" user="Woot"></pagequality>Experimental header' .
					"\n" .
					'</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>'
			],
			[
				'',
				'123',
				'',
				1,
				null,
				'123'
			],
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

		$this->assertSame( $serializedString, $this->handler->serializeContent( $pageContent ) );
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
		$result = $this->handler->unserializeContent( $this->handler->serializeContent( $content ) );
		$this->assertTrue( $content->equals( $result ) );
	}

	public function testSerializeContentInJson() {
		$pageContent = self::newContent( 'Foo', 'Bar', 'FooBar', 2, '1.2.3.4' );

		$this->assertSame(
			json_encode( [
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

	public static function pageJsonSerializationProvider() {
		return [
			[ 'Foo', 'Bar', 'FooBar', 2, '1.2.3.4', json_encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [
					'level' => 2,
					'user' => '1.2.3.4'
				]
			] ) ],
			[ 'Foo', 'Bar', 'FooBar', 2, null, json_encode( [
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

	public static function badPageJsonSerializationProvider() {
		return [
			[ '' ],
			[ '124' ],
			[ '{}' ],
			[ json_encode( [
				'body' => 'Bar',
				'footer' => 'FooBar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ json_encode( [
				'header' => 'Foo',
				'footer' => 'FooBar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ json_encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'level' => [ 'level' => 2 ]
			] ) ],
			[ json_encode( [
				'header' => 'Foo',
				'body' => 'Bar',
				'footer' => 'FooBar'
			] ) ],
		];
	}

	/**
	 * @dataProvider badPageJsonSerializationProvider
	 */
	public function testUnserializeBadContentInJson( $text ) {
		$this->expectException( MWContentSerializationException::class );
		$this->handler->unserializeContent( $text, CONTENT_FORMAT_JSON );
	}

	/**
	 * @dataProvider pageJsonSerializationProvider
	 */
	public function testRoundTripSerializeContentInJson(
		$header, $body, $footer, $level, $proofreader, $text
	) {
		$content = self::newContent( $header, $body, $footer, $level, $proofreader );
		$result = $this->handler->unserializeContent(
			$this->handler->serializeContent( $content, CONTENT_FORMAT_JSON ),
			CONTENT_FORMAT_JSON
		);
		$this->assertTrue( $content->equals( $result ) );
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
		$this->assertSame(
			$expected, $this->handler->getAutosummary( $oldContent, $newContent, 0 )
		);
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

	public static function providePreSaveTransform() {
		return [
			[
				self::buildPageContent( 'hello this is ~~~', '~~~' ),
				self::buildPageContent(
					'hello this is [[Special:Contributions/123.123.123.123|123.123.123.123]]',
					'[[Special:Contributions/123.123.123.123|123.123.123.123]]'
				)
			],
			[
				self::buildPageContent( "hello \'\'this\'\' is <nowiki>~~~</nowiki>" ),
				self::buildPageContent( "hello \'\'this\'\' is <nowiki>~~~</nowiki>" )
			],
			[
				// rtrim
				self::buildPageContent( '\n ', 'foo \n ', '  ' ),
				self::buildPageContent( '\n', 'foo \n', '' )
			],
		];
	}

	/**
	 * @dataProvider providePreSaveTransform
	 */
	public function testPreSaveTransform( PageContent $content, $expectedContent ) {
		$services = $this->getServiceContainer();
		$user = UserIdentityValue::newAnonymous( '123.123.123.123' );
		$options = ParserOptions::newFromUser( $user );

		$contentTransformer = $services->getContentTransformer();
		$content = $contentTransformer->preSaveTransform(
			$content,
			PageReferenceValue::localReference( self::getIndexNamespaceId(), 'Test.pdf' ),
			$user,
			$options
		);

		$this->assertTrue( $content->equals( $expectedContent ) );
	}

	public static function providePreloadTransform() {
		return [
			[ self::buildPageContent( 'hello this is ~~~' ),
				self::buildPageContent( "hello this is ~~~" )
			],
			[
				self::buildPageContent( 'hello \'\'this\'\' is <noinclude>foo</noinclude>' .
					'<includeonly>bar</includeonly>' ),
				self::buildPageContent( 'hello \'\'this\'\' is bar' )
			],
		];
	}

	/**
	 * @dataProvider providePreloadTransform
	 */
	public function testPreloadTransform( PageContent $content, $expectedContent ) {
		$contentTransformer = $this->getServiceContainer()->getContentTransformer();
		$options = ParserOptions::newFromAnon();

		$content = $contentTransformer->preloadTransform(
			$content,
			PageReferenceValue::localReference( self::getIndexNamespaceId(), 'Test.pdf' ),
			$options
		);

		$this->assertEquals( $expectedContent, $content );
	}

	public static function getParserOutputHtmlProvider(): array {
		return [
			[
				self::buildPageContent( '', 'Test', '' ),
				'<p>Test</p>'
			],
			[
				self::buildPageContent( 'start', 'Test', 'end' ),
				'<p>start</p><p>Testend</p>'
			],
			[
				self::buildPageContent( 'start', "\n\nTest", '' ),
				'<p>start</p><p><br /></p><p>Test</p>'
			],
			[
				self::buildPageContent( 'start', "<br/>\n\nTest", '' ),
				'<p>start</p><p><br /></p><p>Test</p>'
			],
			[
				self::buildPageContent( 'start', '<nowiki/>Test', '' ),
				'<p>start</p><p>Test</p>'
			],
			[
				self::buildPageContent( 'start', "<nowiki/>\nTest", '' ),
				'<p>start</p><p>Test</p>'
			],
			[
				self::buildPageContent( 'start', "<nowiki/>\n\nTest", '' ),
				'<p>start</p><p class="mw-empty-elt"></p><p>Test</p>'
			],
			[
				self::buildPageContent( '', "<nowiki/>\n\nTest", '' ),
				'<p class="mw-empty-elt"></p><p>Test</p>'
			]
		];
	}

	/**
	 * @dataProvider getParserOutputHtmlProvider
	 */
	public function testGetParserOutputHtml( PageContent $content, string $html ) {
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$output = $contentRenderer->getParserOutput(
			$content,
			Title::makeTitle( self::getPageNamespaceId(), 'Test' )
		);
		$actual = str_replace( "\n", '', $output->getRawText() );
		$this->assertStringStartsWith(
			'<div class="prp-page-qualityheader quality1">This page has not been proofread</div>' .
				'<div class="pagetext">',
			$actual,
			'prepended'
		);
		$this->assertStringContainsString( $html, $actual );
	}

	public static function getParserOutputRedirectHtmlProvider(): array {
		return [
			[
				self::buildPageContent( '', '#REDIRECT [[Test]]' ),
				'Test'
			],
			[
				self::buildPageContent( '', '#REDIRECT [[Special:BlankPage]]' ),
				'Special:BlankPage'
			]
		];
	}

	/**
	 * @dataProvider getParserOutputRedirectHtmlProvider
	 */
	public function testGetParserOutputRedirectHtml( PageContent $content, string $redirTarget ) {
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$output = $contentRenderer->getParserOutput(
			$content,
			Title::makeTitle( self::getPageNamespaceId(), 'Test' )
		);
		$actual = $output->runOutputPipeline( ParserOptions::newFromAnon(), [] )->getContentHolderText();
		$this->assertStringContainsString( '<div class="redirectMsg">', $actual );
		$this->assertMatchesRegularExpression( '!<a[^<>]+>' . $redirTarget . '</a>!', $actual );
	}
}
