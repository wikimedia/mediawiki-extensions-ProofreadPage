<?php

namespace ProofreadPage\Page;

use IContextSource;
use MediaHandler;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Index\IndexContent;
use ProofreadPageTestCase;
use RequestContext;
use Title;
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
	public function testBuildDefaultContentForPageTitle(
		$pageTitle, $indexTitle = null,
		IndexContent $indexContent = null, PageContent $defaultContent
	) {
		$pageTitle = Title::makeTitle( $this->getPageNamespaceId(), $pageTitle );
		if ( $indexTitle !== null ) {
			$indexTitle = Title::makeTitle( $this->getIndexNamespaceId(), $indexTitle );
			$context = $this->getContext( [
				$pageTitle->getDBkey() => $indexTitle
			], [
				$indexTitle->getDBkey() => $indexContent
			] );
		} else {
			$context = $this->getContext();
		}
		try {
			$image = $context->getFileProvider()->getFileForPageTitle( $pageTitle );
		} catch ( FileNotFoundException $e ) {
			$image = false;
		}
		// Skip when the file exists but there is no support for DjVu files
		if ( $image && MediaHandler::getHandler( 'image/vnd.djvu' ) === false ) {
			$this->markTestSkipped( 'There is no support for DjVu files, please enable it.' );
		}
		$contentBuilder = new PageContentBuilder( $this->context, $context );
		$this->assertEquals(
			$defaultContent, $contentBuilder->buildDefaultContentForPageTitle( $pageTitle )
		);
	}

	public function buildDefaultContentForPageProvider() {
		return [
			[
				 'Test.djvu/1',
				 'Test.djvu',
				new IndexContent( [
					'Title' => new WikitextContent( 'Test book' ),
					'Header' => new WikitextContent( '{{{title}}}' )
				] ),
				self::newContent( 'Test book', '', '<references />', 1 ),
			],
			[
				'LoremIpsum.djvu/2',
				null,
				null,
				self::newContent( '', "Lorem ipsum \n2 \n", '<references/>', 1 ),
			],
			[
				'LoremIpsum.djvu/2' ,
				'LoremIpsum.djvu' ,
				new IndexContent( [
					'Title' => new WikitextContent( 'Test book' ),
					'Pages' => new WikitextContent( '<pagelist/>' ),
					'Header' => new WikitextContent( '{{{pagenum}}}' )
				] ),
				self::newContent( '2', "Lorem ipsum \n2 \n", '<references />', 1 ),
			],
			[
				'LoremIpsum.djvu/2',
				 'LoremIpsum.djvu',
				new IndexContent( [
					'Title' => new WikitextContent( 'Test book' ),
					'Pages' => new WikitextContent( '<pagelist 1to5=roman />' ),
					'Header' => new WikitextContent( '{{{pagenum}}}' )
				] ),
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
