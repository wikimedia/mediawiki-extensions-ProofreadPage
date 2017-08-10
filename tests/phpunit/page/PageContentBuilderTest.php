<?php

namespace ProofreadPage\Page;

use IContextSource;
use MediaHandler;
use ProofreadIndexPage;
use ProofreadPage\FileNotFoundException;
use ProofreadPagePage;
use ProofreadPageTestCase;
use RequestContext;
use User;
use WikitextContent;

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

	private static function newContent(
		$header = '', $body = '', $footer = '', $level = 1, $proofreader = null
	) {
		return new PageContent(
			new WikitextContent( $header ), new WikitextContent( $body ), new WikitextContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	/**
	 * @dataProvider buildDefaultContentForPageProvider
	 */
	public function testBuildDefaultContentForPage(
		ProofreadPagePage $page, ProofreadIndexPage $index = null, PageContent $defaultContent
	) {
		$context = $this->getContext( [
			$page->getTitle()->getDBkey() => $index
		] );
		try {
			$image = $context->getFileProvider()->getForPagePage( $page );
		} catch ( FileNotFoundException $e ) {
			$image = false;
		}
		// Skip when the file exists but there is no support for DjVu files
		if ( $image && MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$contentBuilder = new PageContentBuilder( $this->context, $context );
		$this->assertEquals(
			$defaultContent, $contentBuilder->buildDefaultContentForPage( $page )
		);
	}

	public function buildDefaultContentForPageProvider() {
		return [
			[
				$this->newPagePage( 'Test.djvu/1' ),
				$this->newIndexPage( 'Test.djvu', "{{\n|Title=Test book\n|Header={{{title}}}\n}}" ),
				self::newContent( 'Test book', '', '<references />', 1 ),
			],
			[
				$this->newPagePage( 'LoremIpsum.djvu/2' ),
				null,
				self::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			],
			[
				$this->newPagePage( 'LoremIpsum.djvu/2' ),
				$this->newIndexPage( 'LoremIpsum.djvu',
					"{{\n|Title=Test book\n|Pages=<pagelist/>\n|Header={{{pagenum}}}\n}}"
				),
				self::newContent( '2', "Lorem ipsum \n2 \n", '<references />', 1 ),
			],
			[
				$this->newPagePage( 'LoremIpsum.djvu/2' ),
				$this->newIndexPage(
					'LoremIpsum.djvu',
					"{{\n|Title=Test book\n|Pages=<pagelist 1to5=roman />\n" .
					"|Header={{{pagenum}}}\n}}"
				),
				self::newContent( 'ii', "Lorem ipsum \n2 \n", '<references />', 1 ),
			],
		];
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput(
		$header, $body, $footer, $level, PageContent $oldContent, PageContent $newContent
	) {
		$contentBuilder = new PageContentBuilder( $this->context, $this->getContext() );
		$this->assertEquals(
			$newContent,
			$contentBuilder->buildContentFromInput( $header, $body, $footer, $level, $oldContent )
		);
	}

	public function buildContentFromInputProvider() {
		return [
			[
				'42',
				'42',
				'42',
				2,
				self::newContent( '22', '22', '22', 2, 'Test2' ),
				self::newContent( '42', '42', '42', 2, 'Test2' ),
			],
			[
				'42',
				'42',
				'42',
				2,
				self::newContent( '22', '22', '22', 2, null ),
				self::newContent( '42', '42', '42', 2, 'Test' ),
			],
			[
				'42',
				'42',
				'42',
				3,
				self::newContent( '22', '22', '22', 2, 'Test2' ),
				self::newContent( '42', '42', '42', 3, 'Test' ),
			],
		];
	}
}
