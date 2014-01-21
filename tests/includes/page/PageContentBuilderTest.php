<?php

namespace ProofreadPage\Page;

use IContextSource;
use ProofreadIndexPageTest;
use ProofreadPageContent;
use ProofreadPageContentTest;
use ProofreadPagePage;
use ProofreadPagePageTest;
use ProofreadPageTestCase;
use RequestContext;
use User;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContentBuilder
 */
class ProofreadPageContentBuilderTest extends ProofreadPageTestCase {

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
	public function testBuildDefaultContentForPage( ProofreadPagePage $page, ProofreadPageContent $defaultContent ) {
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
				ProofreadPageContentTest::newContent( 'Test book', '', '<references />', 1 ),
			),
			array(
				ProofreadPagePageTest::newPagePage(
					'LoremIpsum.djvu/2'
				),
				ProofreadPageContentTest::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			),
			//TODO: test pagenum argument
		);
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput( $header, $body, $footer, $level, ProofreadPageContent $oldContent, ProofreadPageContent $newContent ) {
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
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 2, 'Test2' ),
			),
			array(
				'42',
				'42',
				'42',
				2,
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, null ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 2, 'Test' ),
			),
			array(
				'42',
				'42',
				'42',
				3,
				ProofreadPageContentTest::newContent( '22', '22', '22', 2, 'Test2' ),
				ProofreadPageContentTest::newContent( '42', '42', '42', 3, 'Test' ),
			),
		);
	}
}