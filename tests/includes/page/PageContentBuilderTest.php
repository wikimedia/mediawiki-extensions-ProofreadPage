<?php

namespace ProofreadPage\Page;

use IContextSource;
use ProofreadIndexPageTest;
use ProofreadPagePage;
use ProofreadPagePageTest;
use ProofreadPageTestCase;
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
		$contentBuilder = new PageContentBuilder( $this->context, $this->getContext() );
		$this->assertEquals( $defaultContent, $contentBuilder->buildDefaultContentForPage( $page ) );
	}

	public function buildDefaultContentForPageProvider() {
		return array(
			array(
				ProofreadPagePageTest::newPagePage(
					'Test.djvu/1',
					ProofreadIndexPageTest::newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Header={{{title}}}\n}}" )
				),
				PageContentTest::newContent( 'Test book', '', '<references />', 1 ),
			),
			array(
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2'
				),
				PageContentTest::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			),
			array(
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2',
					ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu', "{{\n|Title=Test book\n|Pages=<pagelist/>\n|Header={{{pagenum}}}\n}}" )
				),
				PageContentTest::newContent( '2', "Lorem ipsum \n2 \n", '<references />', 1 ),
			),
			array(
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2',
					ProofreadIndexPageTest::newIndexPage( 'LoremIpsum.djvu', "{{\n|Title=Test book\n|Pages=<pagelist 1to5=roman />\n|Header={{{pagenum}}}\n}}" )
				),
				PageContentTest::newContent( 'ii', "Lorem ipsum \n2 \n", '<references />', 1 ),
			),
		);
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput( $header, $body, $footer, $level, PageContent $oldContent, PageContent $newContent ) {
		$contentBuilder = new PageContentBuilder( $this->context, $this->getContext() );
		$this->assertEquals( $newContent, $contentBuilder->buildContentFromInput( $header, $body, $footer, $level, $oldContent ) );
	}

	public function buildContentFromInputProvider() {
		return array(
			array(
				'42',
				'42',
				'42',
				2,
				PageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				PageContentTest::newContent( '42', '42', '42', 2, 'Test2' ),
			),
			array(
				'42',
				'42',
				'42',
				2,
				PageContentTest::newContent( '22', '22', '22', 2, null ),
				PageContentTest::newContent( '42', '42', '42', 2, 'Test' ),
			),
			array(
				'42',
				'42',
				'42',
				3,
				PageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				PageContentTest::newContent( '42', '42', '42', 3, 'Test' ),
			),
		);
	}
}