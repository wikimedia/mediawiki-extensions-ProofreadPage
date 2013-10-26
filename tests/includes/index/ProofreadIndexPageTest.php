<?php
use ProofreadPage\Pagination\PageNumber;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\PagePagination;

/**
 * @group ProofreadPage
 * @covers ProofreadIndexPage
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
		'Footer' => array(
			'default' => '<references />',
			'header' => true,
			'hidden' => true
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
		'width' => array(
			'type' => 'number',
			'label' => 'Image width',
			'header' => false
		),
		'CSS' => array(
			'type' => 'string',
			'label' => 'CSS',
			'header' => false
		),
	);

	/**
	 * Constructor of a new ProofreadIndexPage
	 * @param $title Title|string
	 * @param $content string|null
	 * @return ProofreadIndexPage
	 */
	public static function newIndexPage( $title = 'test.djvu', $content = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 252, $title );
		}
		return new ProofreadIndexPage( $title, self::$config, $content );
	}

	public function testEquals() {
		$page = self::newIndexPage( 'Test.djvu' );
		$page2 = self::newIndexPage( 'Test.djvu' );
		$page3 = self::newIndexPage( 'Test2.djvu' );
		$this->assertTrue( $page->equals( $page2 ) );
		$this->assertTrue( $page2->equals( $page ) );
		$this->assertFalse( $page->equals( $page3 ) );
		$this->assertFalse( $page3->equals( $page ) );
	}

	public function testGetTitle() {
		$title = Title::makeTitle( 252, 'Test.djvu' );
		$page = ProofreadIndexPage::newFromTitle( $title );
		$this->assertEquals( $title, $page->getTitle() );
	}

	public function testGetIndexEntries() {
		$page = self::newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Header={{{Title}}}\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}" );
		$entries = array(
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry( 'Author', '[[Author:Me]]', self::$config['Author'] ),
			'Year' => new ProofreadIndexEntry( 'Year', '2012 or 2013', self::$config['Year'] ),
			'Pages' => new ProofreadIndexEntry( 'Pages', '<pagelist />', self::$config['Pages'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '{{{Title}}}', self::$config['Header'] ),
			'Footer' => new ProofreadIndexEntry( 'Footer', '', self::$config['Footer'] ),
			'TOC' => new ProofreadIndexEntry( 'TOC', "* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]", self::$config['TOC'] ),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] ),
			'width' => new ProofreadIndexEntry( 'width', '', self::$config['width'] ),
			'CSS' => new ProofreadIndexEntry( 'CSS', '', self::$config['CSS'] )
		);
		$this->assertEquals( $entries, $page->getIndexEntries() );
	}

	public function mimeTypesProvider( ) {
		return array(
			array( 'image/vnd.djvu', 'Test.djvu' ),
			array( 'application/pdf', 'Test.pdf' ),
			array( null, 'Test' )
		);
	}

	/**
	 * @dataProvider mimeTypesProvider
	 */
	public function testGetMimeType( $mime, $name ) {
		$this->assertEquals( $mime, self::newIndexPage( $name )->getMimeType() );
	}

	public function testGetIndexEntriesForHeader() {
		$page = self::newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}" );
		$entries = array(
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry( 'Author', '[[Author:Me]]', self::$config['Author'] ),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '', self::$config['Header'] ),
			'Footer' => new ProofreadIndexEntry( 'Footer', '', self::$config['Footer'] ),
			'width' => new ProofreadIndexEntry( 'width', '', self::$config['width'] ),
			'CSS' => new ProofreadIndexEntry( 'CSS', '', self::$config['CSS'] )
		);
		$this->assertEquals( $entries, $page->getIndexEntriesForHeader() );
	}

	public function testGetIndexEntry() {
		$page = self::newIndexPage( 'Test.djvu', "{{\n|Year=2012 or 2013\n}}" );

		$entry = new ProofreadIndexEntry( 'Year', '2012 or 2013', self::$config['Year'] );
		$this->assertEquals( $entry, $page->getIndexEntry( 'year' ) );

		$this->assertNull( $page->getIndexEntry( 'years' ) );
	}

	public function testGetLinksToMainNamespace() {
		$page = self::newIndexPage( 'Test.djvu', "{{\n|Pages=[[Page:Test.jpg]]\n|TOC=* [[Test/Chapter 1]]\n* [[Azerty:Test/Chapter_2|Chapter 2]]\n}}" );
		$links = array(
			array( Title::newFromText( 'Test/Chapter 1' ), 'Chapter 1' ),
			array( Title::newFromText( 'Azerty:Test/Chapter_2' ), 'Chapter 2' )
        );
		$this->assertEquals( $links, $page->getLinksToMainNamespace() );
	}

	public function testGetLinksToPageNamespace() {
		$page = ProofreadIndexPageTest::newIndexPage( 'Test', "{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test:3.png|2]]\n|Author=[[Author:Me]]\n}}" );
		$links = array(
			array( Title::newFromText( 'Page:Test 1.jpg' ), 'TOC' ),
			array( Title::newFromText( 'Page:Test 2.tiff' ), '1' ),
			array( Title::newFromText( 'Page:Test:3.png' ), '2' )
		);
		$this->assertEquals( $links, $page->getLinksToPageNamespace() );
	}

	/**
	 * @dataProvider getPagelistTagContentProvider
	 */
	public function testGetPagelistTagContent( ProofreadIndexPage $page, PageList $pageList = null ) {
		$this->assertEquals( $pageList, $page->getPagelistTagContent() );
	}

	public function getPagelistTagContentProvider() {
		return array(
			array(
				self::newIndexPage( 'Test.djvu', "{{\n|Pages=<pagelist to=24 1to4=- 5=1 5to24=roman /> <pagelist from=25 25=1 1021to1024=- />\n|Author=[[Author:Me]]\n}}" ),
				new PageList( array( '1to4' => '-', '5' => '1', '5to24' => 'roman', '25' => '1', '1021to1024' => '-', 'to' => 24, 'from' => 25 ) )
			),
			array(
				self::newIndexPage( 'Test.djvu', "{{\n|Pages=<pagelist/>\n|Author=[[Author:Me]]\n}}" ),
				new PageList( array() )
			),
			array(
				self::newIndexPage( 'Test.djvu', "{{\n|Pages=\n|Author=[[Author:Me]]\n}}" ),
				null
			),
		);
	}

	public function replaceVariablesWithIndexEntriesProvider() {
		return array(
			array(
				"{{\n|Title=Test book\n|Header={{{title}}}\n}}",
				'Test book',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header={{{ Pagenum }}}\n}}",
				'22',
				'header',
				array( 'pagenum' => 22 )
			),
			array(
				"{{\n|Title=Test book\n|Header={{{authors}}}\n}}",
				'{{{authors}}}',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header={{{authors |a}}}\n}}",
				'a',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header={{template|a=b}}\n}}",
				'{{template|a=b}}',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header={{template|a={{{Title |}}}}}\n}}",
				'{{template|a=Test book}}',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header=<references/>\n}}",
				'<references/>',
				'header',
				array()
			),
			array(
				"{{\n|Title=Test book\n|Header={{{Pagenum}}}\n}}",
				null,
				'headers',
				array()
			),
		);
	}

	/**
	 * @dataProvider replaceVariablesWithIndexEntriesProvider
	 */
	public function testReplaceVariablesWithIndexEntries( $pageContent, $result, $entry, $extraparams ) {
		$page = self::newIndexPage( 'Test.djvu', $pageContent );
		$this->assertEquals( $result, $page->replaceVariablesWithIndexEntries( $entry, $extraparams ) );
	}
}
