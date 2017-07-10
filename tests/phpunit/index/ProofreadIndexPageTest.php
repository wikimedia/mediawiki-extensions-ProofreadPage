<?php
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Pagination\PageList;

/**
 * @group ProofreadPage
 * @covers ProofreadIndexPage
 */
class ProofreadIndexPageTest extends ProofreadPageTestCase {

	protected static $config = [
		'Title' => [
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Title',
			'values' => null,
			'header' => true,
			'data' => 'title'
		],
		'Author' => [
			'type' => 'page',
			'size' => 1,
			'default' => '',
			'label' => 'Author',
			'values' => null,
			'header' => true,
			'data' => 'author'
		],
		'Year' => [
			'type' => 'number',
			'size' => 1,
			'default' => '',
			'label' => 'Year of publication',
			'values' => null,
			'header' => false,
			'data' => 'year'
		],
		'Pages' => [
			'type' => 'string',
			'size' => 20,
			'default' => '',
			'label' => 'Pages',
			'values' => null,
			'header' => false
		],
		'Header' => [
			'type' => 'string',
			'size' => 10,
			'default' => 'head',
			'label' => 'Header',
			'values' => null,
			'header' => false
		],
		'Footer' => [
			'default' => '<references />',
			'header' => true,
			'hidden' => true
		],
		'TOC' => [
			'type' => 'string',
			'size' => 1,
			'default' => '',
			'label' => 'Table of content',
			'values' => null,
			'header' => false
		],
		'Comment' => [
			'header' => true,
			'hidden' => true
		],
		'width' => [
			'type' => 'number',
			'label' => 'Image width',
			'header' => false
		],
		'CSS' => [
			'type' => 'string',
			'label' => 'CSS',
			'header' => false
		],
	];

	/**
	 * Constructor of a new ProofreadIndexPage
	 * @param Title|string $title
	 * @param string|IndexContent|null $content
	 * @return ProofreadIndexPage
	 */
	public static function newIndexPage( $title = 'test.djvu', $content = null ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( 252, $title );
		}
		if ( is_string( $content ) ) {
			$content = ContentHandler::getForModelID( CONTENT_MODEL_PROOFREAD_INDEX )
				->unserializeContent( $content );
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
		$page = self::newIndexPage(
			'Test.djvu',
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Header={{{Title}}}" .
				"\n|Pages=<pagelist />\n|TOC=* [[Test/Chapter 1|Chapter 1]]" .
				"\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry(
				'Author', '[[Author:Me]]', self::$config['Author']
			),
			'Year' => new ProofreadIndexEntry( 'Year', '2012 or 2013', self::$config['Year'] ),
			'Pages' => new ProofreadIndexEntry( 'Pages', '<pagelist />', self::$config['Pages'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '{{{Title}}}', self::$config['Header'] ),
			'Footer' => new ProofreadIndexEntry( 'Footer', '', self::$config['Footer'] ),
			'TOC' => new ProofreadIndexEntry(
				'TOC',
				"* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]",
				self::$config['TOC']
			),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] ),
			'width' => new ProofreadIndexEntry( 'width', '', self::$config['width'] ),
			'CSS' => new ProofreadIndexEntry( 'CSS', '', self::$config['CSS'] )
		];
		$this->assertEquals( $entries, $page->getIndexEntries() );
	}

	public function mimeTypesProvider() {
		return [
			[ 'image/vnd.djvu', 'Test.djvu' ],
			[ 'application/pdf', 'Test.pdf' ],
			[ null, 'Test' ]
		];
	}

	/**
	 * @dataProvider mimeTypesProvider
	 */
	public function testGetMimeType( $mime, $name ) {
		$this->assertEquals( $mime, self::newIndexPage( $name )->getMimeType() );
	}

	public function testGetIndexEntriesForHeader() {
		$page = self::newIndexPage(
			'Test.djvu',
			"{{\n|Title=Test book\n|Author=[[Author:Me]]\n|Year=2012 or 2013\n|Pages=<pagelist />" .
				"\n|TOC=* [[Test/Chapter 1|Chapter 1]]\n* [[Test/Chapter 2|Chapter 2]]\n}}"
		);
		$entries = [
			'Title' => new ProofreadIndexEntry( 'Title', 'Test book', self::$config['Title'] ),
			'Author' => new ProofreadIndexEntry(
				'Author', '[[Author:Me]]', self::$config['Author']
			),
			'Comment' => new ProofreadIndexEntry( 'Comment', '', self::$config['Comment'] ),
			'Header' => new ProofreadIndexEntry( 'Header', '', self::$config['Header'] ),
			'Footer' => new ProofreadIndexEntry( 'Footer', '', self::$config['Footer'] ),
			'width' => new ProofreadIndexEntry( 'width', '', self::$config['width'] ),
			'CSS' => new ProofreadIndexEntry( 'CSS', '', self::$config['CSS'] )
		];
		$this->assertEquals( $entries, $page->getIndexEntriesForHeader() );
	}

	public function testGetIndexEntry() {
		$page = self::newIndexPage( 'Test.djvu', "{{\n|Year=2012 or 2013\n}}" );

		$entry = new ProofreadIndexEntry( 'Year', '2012 or 2013', self::$config['Year'] );
		$this->assertEquals( $entry, $page->getIndexEntry( 'year' ) );

		$this->assertNull( $page->getIndexEntry( 'years' ) );
	}

	public function testGetLinksToMainNamespace() {
		$page = self::newIndexPage(
			'Test.djvu',
			"{{\n|Pages=[[Page:Test.jpg]]\n|TOC=* [[Test/Chapter 1]]" .
				"\n* [[Azerty:Test/Chapter_2|Chapter 2]]\n}}"
		);
		$links = [
			[ Title::newFromText( 'Test/Chapter 1' ), 'Chapter 1' ],
			[ Title::newFromText( 'Azerty:Test/Chapter_2' ), 'Chapter 2' ]
		];
		$this->assertEquals( $links, $page->getLinksToMainNamespace() );
	}

	public function testGetLinksToPageNamespace() {
		$page = ProofreadIndexPageTest::newIndexPage(
			'Test',
			"{{\n|Pages=[[Page:Test 1.jpg|TOC]] [[Page:Test 2.tiff|1]] [[Page:Test:3.png|2]]" .
				"\n|Author=[[Author:Me]]\n}}"
		);
		$links = [
			[ Title::newFromText( 'Page:Test 1.jpg' ), 'TOC' ],
			[ Title::newFromText( 'Page:Test 2.tiff' ), '1' ],
			[ Title::newFromText( 'Page:Test:3.png' ), '2' ]
		];
		$this->assertEquals( $links, $page->getLinksToPageNamespace() );
	}

	/**
	 * @dataProvider getPagelistTagContentProvider
	 */
	public function testGetPagelistTagContent(
		ProofreadIndexPage $page, PageList $pageList = null
	) {
		$this->assertEquals( $pageList, $page->getPagelistTagContent() );
	}

	public function getPagelistTagContentProvider() {
		return [
			[
				self::newIndexPage(
					'Test.djvu',
					"{{\n|Pages=<pagelist to=24 1to4=- 5=1 5to24=roman /> " .
						"<pagelist from=25 25=1 1021to1024=- />\n|Author=[[Author:Me]]\n}}"
				),
				new PageList( [
					'1to4' => '-',
					'5' => '1',
					'5to24' => 'roman',
					'25' => '1',
					'1021to1024' => '-',
					'to' => 24,
					'from' => 25
				] )
			],
			[
				self::newIndexPage(
					'Test.djvu', "{{\n|Pages=<pagelist/>\n|Author=[[Author:Me]]\n}}"
				),
				new PageList( [] )
			],
			[
				self::newIndexPage( 'Test.djvu', "{{\n|Pages=\n|Author=[[Author:Me]]\n}}" ),
				null
			],
		];
	}

	public function replaceVariablesWithIndexEntriesProvider() {
		return [
			[
				"{{\n|Title=Test book\n|Header={{{title}}}\n}}",
				'Test book',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{{ Pagenum }}}\n}}",
				'22',
				'header',
				[ 'pagenum' => 22 ]
			],
			[
				"{{\n|Title=Test book\n|Header={{{authors}}}\n}}",
				'{{{authors}}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{{authors |a}}}\n}}",
				'a',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{template|a=b}}\n}}",
				'{{template|a=b}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{template|a={{{Title |}}}}}\n}}",
				'{{template|a=Test book}}',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header=<references/>\n}}",
				'<references/>',
				'header',
				[]
			],
			[
				"{{\n|Title=Test book\n|Header={{{Pagenum}}}\n}}",
				null,
				'headers',
				[]
			],
		];
	}

	/**
	 * @dataProvider replaceVariablesWithIndexEntriesProvider
	 */
	public function testReplaceVariablesWithIndexEntries(
		$pageContent, $result, $entry, $extraparams
	) {
		$page = self::newIndexPage( 'Test.djvu', $pageContent );
		$this->assertEquals(
			$result,
			$page->getIndexEntryWithVariablesReplacedWithIndexEntries( $entry, $extraparams )
		);
	}
}
