<?php

/**
 * @group ProofreadPage
 */
class ProofreadIndexPageTest extends ProofreadPageTestCase {

	protected static $config = array(
		'Title' => array(
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Title',
			'values' => null,
			'header' => true,
			'data' => 'title'
		),
		'Author' => array(
			'type' => 'page',
			'size' => 1,
			'default' => '',
			'label' => 'Author',
			'values' => null,
			'header' => true,
			'data' => 'author'
		),
		'Year' => array(
			'type' => 'number',
			'size' => 1,
			'default' => '',
			'label' => 'Year of publication',
			'values' => null,
			'header' => false,
			'data' => 'year'
		),
		'Pages' => array(
			'type' => 'string',
			'size' => 20,
			'default' => '',
			'label' => 'Pages',
			'values' => null,
			'header' => false
		),
		'Header' => array(
			'type' => 'string',
			'size' => 10,
			'default' => 'head',
			'label' => 'Header',
			'values' => null,
			'header' => false
		),
		'TOC' => array(
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Table of content',
			'values' => null,
			'header' => false
		),
		'Comment' => array(
			'header' => true,
			'hidden' => true
		),
	);

	public function testNewFromTitle() {
		$this->assertInstanceOf( 'ProofreadIndexPage', ProofreadIndexPage::newFromTitle( Title::makeTitle( 252, 'Test.djvu' ) ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 252, 'Test.djvu' );
		$page = new ProofreadIndexPage( $title, array(), '' );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetIndexEntries() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), self::$config, "{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}" );
		$entries = array(
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry( 'Author', '[[Author:Me]]', self::$config['Author'] ),
			'Year' => new ProofreadIndexEntry( 'Year', '2012 or 2013', self::$config['Year'] ),
			'Pages' => new ProofreadIndexEntry( 'Pages', '<pagelist />', self::$config['Pages'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '', self::$config['Header'] ),
			'TOC' => new ProofreadIndexEntry( 'TOC', "* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]", self::$config['TOC'] ),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] )
		);
		$this->assertEquals( $entries, $page->getIndexEntries() );
	}

	public function testGetMimeType() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), array(), null );
		$this->assertEquals( 'image/vnd.djvu', $page->getMimeType() );

		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.pdf' ), array(), null );
		$this->assertEquals( 'application/pdf', $page->getMimeType() );

		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test' ), array(), null );
		$this->assertNull( $page->getMimeType() );
	}

	public function testGetIndexEntriesForHeader() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), self::$config, "{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}" );
		$entries = array(
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry( 'Author', '[[Author:Me]]', self::$config['Author'] ),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '', self::$config['Header'] )
		);
		$this->assertEquals( $entries, $page->getIndexEntriesForHeader() );
	}

	public function testGetLinksToMainNamespace() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), self::$config, "{{\n|Pages=[[Page:Test.jpg]]\n|TOC=* [[Test/Chapter 1]]\n* [[Azerty:Test/Chapter_2|Chapter 2]]\n}}" );
		$links = array(
			array( Title::newFromText( 'Test/Chapter 1' ), 'Chapter 1' ),
			array( Title::newFromText( 'Azerty:Test/Chapter_2' ), 'Chapter 2' )
        );
		$this->assertEquals( $links, $page->getLinksToMainNamespace() );
	}

	public function testGetPagesWithPagelist() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), self::$config, "{{\n|Pages=<pagelist 1to4=- 5=1 5to24=roman 25=1 1021to1024=- />\n|Author=[[Author:Me]]\n}}" );
		$links = array( null, array( '1to4' => '-', '5' => '1', '5to24' => 'roman', '25' => '1', '1021to1024' => '-' ) );
		$this->assertEquals( $links, $page->getPages() );
	}

	public function testGetPagesWithoutPagelist() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test' ), self::$config, "{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test:3.png|2]]\n|Author=[[Author:Me]]\n}}" );
		$links = array( array(
			array( Title::newFromText( 'Page:Test 1.jpg' ), 'TOC' ),
			array( Title::newFromText( 'Page:Test 2.tiff' ), '1' ),
			array( Title::newFromText( 'Page:Test:3.png' ), '2' )
        ), null );
		$this->assertEquals( $links, $page->getPages() );
	}

	public function testGetPreviousAndNextPagesWithoutPagelist() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test' ), self::$config, "{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test 3.png|2]]\n|Author=[[Author:Me]]\n}}" );
		$links = array(
			Title::newFromText( 'Page:Test 1.jpg' ),
			Title::newFromText( 'Page:Test 3.png' )
        );
		$this->assertEquals( $links, $page->getPreviousAndNextPages( Title::newFromText( 'Page:Test 2.tiff' ) ) );
	}

	public function testGetIndexDataForPage() {
		$page = new ProofreadIndexPage( Title::makeTitle( 252, 'Test.djvu' ), self::$config, "{{\n|Title=Test book\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test 3.png|2]]\n|Header=Head {{{pagenum}}}\n}}" );
		$result = array( 'Head TOC', '<references/>', '', '' );
		$this->assertEquals( $result, $page->getIndexDataForPage( Title::newFromText( 'Page:Test 1.jpg' ) ) );
	}
}
