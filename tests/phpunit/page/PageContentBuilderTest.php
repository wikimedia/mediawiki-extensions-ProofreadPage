<?php

namespace ProofreadPage\Page;

use IContextSource;
use MediaHandler;
use ProofreadIndexPageTest;
use ProofreadPagePage;
use ProofreadPagePageTest;
use ProofreadPageTestCase;
use ProofreadPage\FileNotFoundException;
use RequestContext;
use User;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContentBuilder
 */
class PageContentBuilderTest extends ProofreadPageTestCase {

	/**
	 * @var IContextSource
	 */
	private $context;

	protected function setUp() {
		parent::setUp();

		$this->context = new RequestContext();
		$this->context->setUser( User::newFromName( 'Test' ) );
	}

	/**
	 * @dataProvider buildDefaultContentForPageProvider
	 */
	public function testBuildDefaultContentForPage( ProofreadPagePage $page, PageContent $defaultContent ) {
		try {
			$image = $this->getContext()->getFileProvider()->getForPagePage( $page );
		} catch ( FileNotFoundException $e ) {
			$image = false;
		}
		// Skip when the file exists but there is no support for DjVu files
		if ( $image && MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$contentBuilder = new PageContentBuilder( $this->context, $this->getContext() );
		$this->assertEquals( $defaultContent, $contentBuilder->buildDefaultContentForPage( $page ) );
	}

	public function buildDefaultContentForPageProvider() {
		return [
			[
				ProofreadPagePageTest::newPagePage(
					'Test.djvu/1',
					ProofreadIndexPageTest::newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Header={{{title}}}\n}}" )
				),
				PageContentTest::newContent( 'Test book', '', '<references />', 1 ),
			],
			[
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2'
				),
				PageContentTest::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			],
			[
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2',
					ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu', "{{\n|Title=Test book\n|Pages=<pagelist/>\n|Header={{{pagenum}}}\n}}" )
				),
				PageContentTest::newContent( '2', "Lorem ipsum \n2 \n", '<references />', 1 ),
			],
			[
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2',
					ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu', "{{\n|Title=Test book\n|Pages=<pagelist 1to5=roman />\n|Header={{{pagenum}}}\n}}" )
				),
				PageContentTest::newContent( 'ii', "Lorem ipsum \n2 \n", '<references />', 1 ),
			],
		];
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput( $header, $body, $footer, $level, PageContent $oldContent, PageContent $newContent ) {
		$contentBuilder = new PageContentBuilder( $this->context, $this->getContext() );
		$this->assertEquals( $newContent, $contentBuilder->buildContentFromInput( $header, $body, $footer, $level, $oldContent ) );
	}

	public function buildContentFromInputProvider() {
		return [
			[
				'42',
				'42',
				'42',
				2,
				PageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				PageContentTest::newContent( '42', '42', '42', 2, 'Test2' ),
			],
			[
				'42',
				'42',
				'42',
				2,
				PageContentTest::newContent( '22', '22', '22', 2, null ),
				PageContentTest::newContent( '42', '42', '42', 2, 'Test' ),
			],
			[
				'42',
				'42',
				'42',
				3,
				PageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				PageContentTest::newContent( '42', '42', '42', 3, 'Test' ),
			],
		];
	}
}
