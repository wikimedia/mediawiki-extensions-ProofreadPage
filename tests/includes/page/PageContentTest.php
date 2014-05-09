<?php

namespace ProofreadPage\Page;

use FauxRequest;
use ParserOptions;
use ProofreadPageTestCase;
use RequestContext;
use Title;
use User;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers ProofreadPageContent
 */
class PageContentTest extends ProofreadPageTestCase {

	/**
	 * @var RequestContext
	 */
	private $requestContext;

	protected function setUp() {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->setMwGlobals( array(
			'wgUser' => $user,
			'wgTextModelsToParse' => array(
				CONTENT_MODEL_PROOFREAD_PAGE
			)
		) );


		$this->requestContext = new RequestContext( new FauxRequest() );
		$this->requestContext->setTitle( Title::makeTitle( 250, 'Test.jpg' ) );
		$this->requestContext->setUser( $user );
	}

	/**
	 * Constructor of a new PageContent
	 * @param $header WikitextContent|string
	 * @param $body WikitextContent|string
	 * @param $footer WikitextContent|string
	 * @param $level integer
	 * @param $proofreader User
	 * @return PageContent
	 */
	public static function newContent( $header = '', $body = '', $footer = '', $level = 1, $proofreader = null ) {
		if ( is_string( $header ) ) {
			$header = new WikitextContent( $header );
		}
		if ( is_string( $body ) ) {
			$body = new WikitextContent( $body );
		}
		if ( is_string( $footer ) ) {
			$footer = new WikitextContent( $footer );
		}
		if ( is_string( $proofreader ) ) {
			$proofreader = PageLevel::getUserFromUserName( $proofreader );
		}
		return new PageContent( $header, $body, $footer, new PageLevel( $level, $proofreader ) );
	}

	public function testGetModel() {
		$content = self::newContent();
		$this->assertEquals( CONTENT_MODEL_PROOFREAD_PAGE, $content->getModel() );
	}

	public function testGetHeader() {
		$header = new WikitextContent( "testString" );
		$pageContent = self::newContent( $header );
		$this->assertEquals( $header, $pageContent->getHeader() );
	}

	public function testGetFooter() {
		$footer = new WikitextContent( "testString" );
		$pageContent = self::newContent( '', '', $footer );
		$this->assertEquals( $footer, $pageContent->getFooter() );
	}

	public function testGetBody() {
		$body = new WikitextContent( "testString" );
		$pageContent = self::newContent( '', $body );
		$this->assertEquals( $body, $pageContent->getBody() );
	}

	public function testGetLevel() {
		$level = new PageLevel( 2, null );
		$pageContent = self::newContent( '', '', '', 2, null );
		$this->assertEquals( $level, $pageContent->getLevel() );
	}

	public function testGetContentHandler() {
		$content = self::newContent();
		$this->assertEquals( CONTENT_MODEL_PROOFREAD_PAGE, $content->getContentHandler()->getModelID() );
	}

	public function testCopy() {
		$content = self::newContent( '123', 'aert', '', 1, 'Test' );
		$this->assertEquals( $content, $content->copy() );
	}

	public function equalsProvider() {
		return array(
			array( self::newContent(), null, false ),
			array( self::newContent( 'a', 'hallo' ), self::newContent( 'a', 'hallo' ), true ),
			array( self::newContent( 'a', 'hallo' ), self::newContent( 'A', 'hallo' ), false ),
			array( self::newContent( '', 'a', '', 1, 'test' ), self::newContent( '', 'a', '', 1, null ), false ),
			array( self::newContent( '', 'a', '', 1, 'ater_ir' ), self::newContent( '', 'a', '', 1, 'ater ir' ), true ),
			array( self::newContent( '', 'hallo' ), new WikitextContent( 'hallo' ), false )
		);
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( $a, $b, $equal ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public function testGetWikitextForTransclusion() {
		$content = self::newContent( 'aa', 'test', 'bb', 2, 'ater' );
		return $this->assertEquals( 'test', $content->getWikitextForTransclusion() );
	}

	public function getTextForSummaryProvider() {
		return array(
			array(
				self::newContent( 'aaaa', "hello\nworld.", '', 1, 'test' ),
				16,
				'hello world.'
			),
			array(
				self::newContent( 'aaaa', "hello world." ),
				8,
				'hello...'
			),
			array(
				self::newContent( 'aaaa', "[[hello world]]." ),
				8,
				'hel...'
			)
		);
	}

	/**
	 * @dataProvider getTextForSummaryProvider
	 */
	public function testGetTextForSummary( $content, $length, $result ) {
		$this->assertEquals( $result, $content->getTextForSummary( $length ) );
	}

	public function preSaveTransformProvider() {
		return array(
			array(
				self::newContent( 'hello this is ~~~', '~~~' ),
				self::newContent( 'hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]', '[[Special:Contributions/127.0.0.1|127.0.0.1]]' )
			),
			array(
				self::newContent( "hello \'\'this\'\' is <nowiki>~~~</nowiki>" ),
				self::newContent( "hello \'\'this\'\' is <nowiki>~~~</nowiki>" )
			),
			array( // rtrim
				self::newContent( '\n ', 'foo \n ', '  ' ),
				self::newContent( '\n', 'foo \n', '' )
			),
		);
	}

	/**
	 * @dataProvider preSaveTransformProvider
	 */
	public function testPreSaveTransform( $content, $expectedContent ) {
		global $wgContLang;

		$options = ParserOptions::newFromUserAndLang( $this->requestContext->getUser(), $wgContLang );

		$content = $content->preSaveTransform( $this->requestContext->getTitle(), $this->requestContext->getUser(), $options );

		$this->assertEquals( $expectedContent, $content );
	}

	public function preloadTransformProvider() {
		return array(
			array( self::newContent(  'hello this is ~~~' ),
				self::newContent( "hello this is ~~~" )
			),
			array( self::newContent( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>' ),
				self::newContent( 'hello \'\'this\'\' is bar' )
			),
		);
	}

	/**
	 * @dataProvider preloadTransformProvider
	 */
	public function testPreloadTransform( $content, $expectedContent ) {
		global $wgContLang;

		$options = ParserOptions::newFromUserAndLang( $this->requestContext->getUser(), $wgContLang );

		$content = $content->preloadTransform( $this->requestContext->getTitle(), $options );

		$this->assertEquals( $expectedContent, $content );
	}

	public function testRedirectTarget() {
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$content = self::newContent( '', '#REDIRECT [[Test]]' );
		$this->assertTrue( $title->equals( $content->getRedirectTarget() ) );
	}

	public function testUpdateRedirect() {
		$title = Title::makeTitle( NS_MAIN, 'Someplace' );

		$content = self::newContent( '', 'RRRR' );
		$newContent = $content->updateRedirect( $title );
		$this->assertEquals( $content, $newContent ); //no update

		$content = self::newContent( '', '#REDIRECT [[Test]]' );
		$newContent = $content->updateRedirect( $title );
		$this->assertTrue( $title->equals( $newContent->getRedirectTarget() ) );
	}

	public function testGetSize() {
		$content = self::newContent( 'aa', 'Test', 'éè' );
		$this->assertEquals( 10, $content->getSize() );
	}
}