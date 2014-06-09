<?php

namespace ProofreadPage\Page;

use ContentHandler;
use ProofreadPageTestCase;
use Title;

/**
 * @group ProofreadPage
 * @covers ProofreadPageContentHandler
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

	public function pageProvider( ) {
		return array(
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, '1.2.3.4', '<noinclude>{{PageQuality|2|1.2.3.4}}<div class="pagetext">Experimental header' . "\n\n\n" . '</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>' ),
			array( 'Experimental header', 'Experimental body', '', 2, 'Woot', '<noinclude>{{PageQuality|2|Woot}}<div>Experimental header' . "\n\n\n" . '</noinclude>Experimental body</div>'),
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">Experimental header' . "\n\n\n" . '</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>' ),
			array( 'Experimental header', 'Experimental body', '', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div>Experimental header' . "\n\n\n" . '</noinclude>Experimental body</div>' ),
			array( 'Experimental header', 'Experimental <noinclude>body</noinclude>', 'Experimental footer', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">Experimental header' . "\n\n\n" . '</noinclude>Experimental <noinclude>body</noinclude><noinclude>Experimental footer</div></noinclude>' ),
			array( 'Experimental header', 'Experimental body', 'Experimental footer', 2, 'Woot', '<noinclude><pagequality level="2" user="Woot" /><div class="pagetext">Experimental header' . "\n" . '</noinclude>Experimental body<noinclude>Experimental footer</div></noinclude>' )
		);
	}

	/**
	 * @dataProvider pageProvider
	 */
	public function testSerializeContent( $header, $body, $footer, $level, $proofreader ) {
		$pageContent = PageContentTest::newContent( $header, $body, $footer, $level, $proofreader );

		$serializedString = '<noinclude><pagequality level="' . $level . '" user="';
		$serializedString .= $proofreader;
		$serializedString .= '" /><div class="pagetext">' . $header ."\n\n\n" . '</noinclude>';
		$serializedString .= $body;
		$serializedString .= '<noinclude>' . $footer . '</div></noinclude>';

		$this->assertEquals( $serializedString, $this->handler->serializeContent( $pageContent ) );
	}

	/**
	 * @dataProvider pageProvider
	 */
	public function testUnserializeContent( $header, $body, $footer, $level, $proofreader, $text ) {
		$this->assertEquals(
			PageContentTest::newContent( $header, $body, $footer, $level, $proofreader ),
			$this->handler->unserializeContent( $text )
		);
	}

	public function testMakeEmptyContent() {
		$content = $this->handler->makeEmptyContent();
		$this->assertTrue( $content->isEmpty() );
	}

	public static function merge3Provider() {
		return array(
			array(
				PageContentTest::newContent( '', "first paragraph\n\nsecond paragraph\n" ),
				PageContentTest::newContent( '', "FIRST paragraph\n\nsecond paragraph\n" ),
				PageContentTest::newContent( '', "first paragraph\n\nSECOND paragraph\n" ),
				PageContentTest::newContent( '', "FIRST paragraph\n\nSECOND paragraph\n" )
			),
			array(
				PageContentTest::newContent( '', "test\n" ),
				PageContentTest::newContent( '', "dddd\n" ),
				PageContentTest::newContent( '', "ffff\n" ),
				false
			),
			array(
				PageContentTest::newContent( '', "test\n", '', 1, 'John' ),
				PageContentTest::newContent( '', "test2\n", '', 2, 'Jack' ),
				PageContentTest::newContent( '', "test\n", '', 2, 'Bob' ),
				PageContentTest::newContent( '', "test2\n", '', 2, 'Bob' ),
			),
			array(
				PageContentTest::newContent( '', "test\n", '', 1, 'John' ),
				PageContentTest::newContent( '', "test\n", '', 2, 'Jack' ),
				PageContentTest::newContent( '', "test\n", '', 1, 'Bob' ),
				false
			),
		);
	}

	/**
	 * @dataProvider merge3Provider
	 */
	public function testMerge3( $oldContent, $myContent, $yourContent, $expected ) {
		$merged = $this->handler->merge3( $oldContent, $myContent, $yourContent );

		$this->assertEquals( $expected, $merged );
	}

	public static function getAutosummaryProvider() {
		return array(
			array(
				PageContentTest::newContent( '', '', '', 1 ),
				PageContentTest::newContent( 'aa', 'aa', 'aa', 1 ),
				''
			),
			array(
				null,
				PageContentTest::newContent( '', 'aaa', '', 1 ),
				'/* Not proofread */'
			),
			array(
				PageContentTest::newContent( '', '', '', 2 ),
				PageContentTest::newContent( '', '', '', 1 ),
				'/* Not proofread */'
			)
		);
	}

	/**
	 * @dataProvider getAutosummaryProvider
	 */
	public function testGetAutosummary( $oldContent, $newContent, $expected ) {
		$this->assertEquals( $expected, $this->handler->getAutosummary( $oldContent, $newContent, array() ) );
	}

	public function testMakeRedirectContent() {
		$title = Title::makeTitle( NS_MAIN, 'Test' );
		$this->assertTrue( $title->equals( $this->handler->makeRedirectContent( $title )->getRedirectTarget() ) );
	}
}
