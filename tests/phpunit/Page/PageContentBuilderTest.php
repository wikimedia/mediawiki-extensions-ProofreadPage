<?php

namespace ProofreadPage\Page;

use MediaHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Index\IndexContent;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageContentBuilder
 */
class PageContentBuilderTest extends ProofreadPageTestCase {

	/**
	 * @var IContextSource
	 */
	private $context;

	protected function setUp(): void {
		parent::setUp();

		$this->context = new RequestContext();
		$this->context->setUser( User::newFromName( 'Test' ) );
		$this->context->setLanguage( 'en' );
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
		$pageTitle, $indexTitle,
		?IndexContent $indexContent, PageContent $defaultContent
	) {
		$pageTitle = $this->makeEnglishPagePageTitle( $pageTitle );
		if ( $indexTitle !== null ) {
			$indexTitle = Title::makeTitle( self::getIndexNamespaceId(), $indexTitle );
			$context = $this->getContext( [
				$pageTitle->getDBkey() => $indexTitle
			], [
				$indexTitle->getDBkey() => $indexContent
			] );
		} else {
			$context = self::getContext();
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

	public static function buildDefaultContentForPageProvider() {
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
				'LoremIpsum.djvu/2',
				'LoremIpsum.djvu',
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
			// Invalid page numbers - should not leak exceptions
			[
				'LoremIpsum.djvu/foo',
				'LoremIpsum.djvu',
				new IndexContent( [
					'Title' => new WikitextContent( 'Test book' ),
					'Pages' => new WikitextContent( '<pagelist/>' ),
					'Header' => new WikitextContent( '{{{pagenum}}}' )
				] ),
				self::newContent( '{{{pagenum}}}', "Lorem ipsum \n1 \n", '<references />', 1 ),
			],
		];
	}

	/**
	 * @dataProvider buildContentFromInputProvider
	 */
	public function testBuildContentFromInput(
		$header, $body, $footer, $level, PageLevel $oldLevel, PageContent $newContent
	) {
		$contentBuilder = new PageContentBuilder( $this->context, self::getContext() );
		$this->assertEquals(
			$newContent,
			$contentBuilder->buildContentFromInput( $header, $body, $footer, $level, $oldLevel )
		);
	}

	public static function buildContentFromInputProvider() {
		return [
			[
				'42',
				'42',
				'42',
				2,
				new PageLevel( 2, User::newFromName( 'Test2' ) ),
				self::newContent( '42', '42', '42', 2, 'Test2' ),
			],
			[
				'42',
				'42',
				'42',
				2,
				new PageLevel( 2 ),
				self::newContent( '42', '42', '42', 2, 'Test' ),
			],
			[
				'42',
				'42',
				'42',
				3,
				new PageLevel( 2 ),
				self::newContent( '42', '42', '42', 3, 'Test' ),
			],
		];
	}
}
