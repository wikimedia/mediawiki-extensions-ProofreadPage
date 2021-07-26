<?php

namespace ProofreadPage\Page;

use Content;
use MediaWiki\MediaWikiServices;
use ParserOptions;
use ProofreadPageTestCase;
use RequestContext;
use Status;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContent
 */
class PageContentTest extends ProofreadPageTestCase {

	/**
	 * @var RequestContext
	 */
	private $requestContext;

	protected function setUp(): void {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->setMwGlobals( [
			'wgTextModelsToParse' => [
				CONTENT_MODEL_PROOFREAD_PAGE
			]
		] );

		$this->requestContext = new RequestContext();
		$this->requestContext->setTitle( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) );
		$this->requestContext->setUser( $user );
	}

	public function testGetModel() {
		$content = self::buildPageContent();
		$this->assertSame( CONTENT_MODEL_PROOFREAD_PAGE, $content->getModel() );
	}

	public function testGetHeader() {
		$pageContent = self::buildPageContent( 'testString' );
		$this->assertEquals( new WikitextContent( 'testString' ), $pageContent->getHeader() );
	}

	public function testGetFooter() {
		$pageContent = self::buildPageContent( '', '', 'testString' );
		$this->assertEquals( new WikitextContent( 'testString' ), $pageContent->getFooter() );
	}

	public function testGetBody() {
		$pageContent = self::buildPageContent( '', 'testString' );
		$this->assertEquals( new WikitextContent( 'testString' ), $pageContent->getBody() );
	}

	public function testGetLevel() {
		$level = new PageLevel( 2, null );
		$pageContent = self::buildPageContent( '', '', '', 2, null );
		$this->assertEquals( $level, $pageContent->getLevel() );
	}

	public function testGetContentHandler() {
		$content = self::buildPageContent();
		$this->assertSame(
			CONTENT_MODEL_PROOFREAD_PAGE, $content->getContentHandler()->getModelID()
		);
	}

	public function testCopy() {
		$content = self::buildPageContent( '123', 'aert', '', 1, 'Test' );
		$this->assertSame( $content, $content->copy() );
	}

	public function equalsProvider() {
		return [
			[
				self::buildPageContent(),
				null,
				false
			],
			[
				self::buildPageContent( 'a', 'hallo' ),
				self::buildPageContent( 'a', 'hallo' ),
				true
			],
			[
				self::buildPageContent( 'a', 'hallo' ),
				self::buildPageContent( 'A', 'hallo' ),
				false
			],
			[
				self::buildPageContent( '', 'a', '', 1, 'test' ),
				self::buildPageContent( '', 'a', '', 1, null ),
				false
			],
			[
				self::buildPageContent( '', 'a', '', 1, 'ater_ir' ),
				self::buildPageContent( '', 'a', '', 1, 'ater ir' ),
				true
			],
			[
				self::buildPageContent( '', 'hallo' ),
				new WikitextContent( 'hallo' ),
				false
			]
		];
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( Content $a, ?Content $b, bool $equal ) {
		$this->assertSame( $equal, $a->equals( $b ) );
	}

	public function testGetWikitextForTransclusion() {
		$content = self::buildPageContent( 'aa', 'test', 'bb', 2, 'ater' );
		$this->assertSame( 'test', $content->getWikitextForTransclusion() );
	}

	public function getTextForSummaryProvider() {
		return [
			[
				self::buildPageContent( 'aaaa', "hello\nworld.", '', 1, 'test' ),
				16,
				'hello world.'
			],
			[
				self::buildPageContent( 'aaaa', "hello world." ),
				8,
				'hello...'
			],
			[
				self::buildPageContent( 'aaaa', "[[hello world]]." ),
				8,
				'hel...'
			]
		];
	}

	/**
	 * @dataProvider getTextForSummaryProvider
	 */
	public function testGetTextForSummary( PageContent $content, $length, $result ) {
		$this->assertSame( $result, $content->getTextForSummary( $length ) );
	}

	public function preloadTransformProvider() {
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
	 * @dataProvider preloadTransformProvider
	 */
	public function testPreloadTransform( PageContent $content, $expectedContent ) {
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();

		$options = ParserOptions::newFromUserAndLang(
			$this->requestContext->getUser(), $contLang
		);

		$content = $content->preloadTransform( $this->requestContext->getTitle(), $options );

		$this->assertEquals( $expectedContent, $content );
	}

	public function prepareSaveProvider() {
		return [
			[
				Status::newGood(),
				self::buildPageContent()
			],
			[
				Status::newFatal( 'invalid-content-data' ),
				self::buildPageContent( '', '', '', 5 )
			],
			[
				Status::newFatal( 'proofreadpage_notallowedtext' ),
				self::buildPageContent( '', '', '', PageLevel::VALIDATED )
			]
		];
	}

	/**
	 * @dataProvider prepareSaveProvider
	 */
	public function testPrepareSave( Status $expectedResult, PageContent $content ) {
		$wikiPage = $this->getMockBuilder( WikiPage::class )
			->disableOriginalConstructor()
			->getMock();
		$this->assertEquals( $expectedResult, $content->prepareSave(
			$wikiPage, 0, -1, $this->requestContext->getUser()
		) );
	}

	public function testRedirectTarget() {
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$content = self::buildPageContent( '', '#REDIRECT [[Test]]' );
		$this->assertTrue( $title->equals( $content->getRedirectTarget() ) );
	}

	public function testUpdateRedirect() {
		$title = Title::makeTitle( NS_MAIN, 'Someplace' );

		$content = self::buildPageContent( '', 'RRRR' );
		$newContent = $content->updateRedirect( $title );
		// no update
		$this->assertSame( $content, $newContent );
		$content = self::buildPageContent( '', '#REDIRECT [[Test]]' );
		$newContent = $content->updateRedirect( $title );
		$this->assertTrue( $title->equals( $newContent->getRedirectTarget() ) );
	}

	public function testGetSize() {
		$content = self::buildPageContent( 'aa', 'Test', 'éè' );
		$this->assertSame( 10, $content->getSize() );
	}

	public function getParserOutputHtmlProvider(): array {
		return [
			[
				self::buildPageContent( '', 'Test', '' ),
				'<p><br />Test</p><>'
			],
			[
				self::buildPageContent( 'start', 'Test', 'end' ),
				'<p>start</p><p>Testend</p><>'
			],
			[
				self::buildPageContent( 'start', "\n\nTest", '' ),
				'<p>start</p><p><br /></p><p>Test</p><>'
			],
			[
				self::buildPageContent( 'start', "<br/>\n\nTest", '' ),
				'<p>start</p><p><br /></p><p>Test</p><>'
			],
			[
				self::buildPageContent( 'start', '<nowiki/>Test', '' ),
				'<p>start</p><p>Test</p><>'
			],
			[
				self::buildPageContent( 'start', "<nowiki/>\nTest", '' ),
				'<p>start</p><p>Test</p><>'
			],
			[
				self::buildPageContent( 'start', "<nowiki/>\n\nTest", '' ),
				'<p>start</p><p class="mw-empty-elt"></p><p>Test</p><>'
			],
			[
				self::buildPageContent( '', "<nowiki/>\n\nTest", '' ),
				'<p><br /></p><p>Test</p><>'
			]
		];
	}

	/**
	 * @dataProvider getParserOutputHtmlProvider
	 */
	public function testGetParserOutputHtml( PageContent $content, string $html ) {
		$expected = '<div class="prp-page-qualityheader quality1">This page has not been proofread</div>' .
			'<div class="pagetext"><div class="mw-parser-output">' . $html . '</div></div>';
		$output = $content->getParserOutput(
			Title::makeTitle( $this->getPageNamespaceId(), 'LoremIpsum.djvu/1' )
		);
		$actual = preg_replace( '<!--.*-->', '', str_replace( "\n", '', $output->mText ) );
		$this->assertSame( $expected, $actual );
	}
}
