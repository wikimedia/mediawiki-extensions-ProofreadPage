<?php

namespace ProofreadPage\Page;

use MediaWiki\User\UserFactory;
use ProofreadPage\Index\IndexContent;
use ProofreadPageTestCase;
use Title;
use User;
use WikitextContent;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageDisplayHandler
 */
class PageDisplayHandlerTest extends ProofreadPageTestCase {

	public function testGetImageWidth() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [ 'width' => new WikitextContent( '500' ) ] )
		] ) );
		$this->assertSame(
			500,
			$handler->getImageWidth( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )
		);
	}

	public function testGetImageWidthWithDefault() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [ 'title' => new WikitextContent( '500' ) ] )
		] ) );
		$this->assertSame(
			PageDisplayHandler::DEFAULT_IMAGE_WIDTH,
			$handler->getImageWidth( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )
		);
	}

	public function testGetCustomCss() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:300px;' )
			] )
		] ) );
		$this->assertSame(
			'width:300px;',
			$handler->getCustomCss( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )
		);
	}

	public function testGetCustomCssWithInsecureInput() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'background: url(\'/my-bad-url.jpg\');' )
			] )
		] ) );
		$this->assertSame(
			'/* insecure input */',
			$handler->getCustomCss( Title::makeTitle( $this->getPageNamespaceId(),  'Test.jpg' ) )
		);
	}

	public function testGetCustomCssWithEscaping() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:300px;<style>' )
			] )
		] ) );
		$this->assertSame(
			'width:300px;&lt;style&gt;',
			$handler->getCustomCss( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )
		);
	}

	public function testGetIndexFieldsForJS() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'width' => new WikitextContent( '300' ),
				'Author' => new WikitextContent( '[[Lorem Ipsum]]' )
			] )
		] ) );

		$indexFields = $handler->getIndexFieldsForJS( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) );

		$this->assertSame(
			'300',
			$indexFields['width']
		);

		$this->assertSame(
			'[[Lorem Ipsum]]',
			$indexFields['Author']
		);
	}

	public function testGetIndexFieldsForJSWithEscaping() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'Author' => new WikitextContent( '[[Lorem Ipsum]]<script>alert(1)</script>"&' )
			] )
		] ) );
		$this->assertSame(
			'[[Lorem Ipsum]]&lt;script&gt;alert(1)&lt;/script&gt;&quot;&amp;',
			$handler->getIndexFieldsForJS( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )['Author']
		);
	}

	public function testGetIndexFieldsForJSForCSSWithEscaping() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:300px;<style>' )
			] )
		] ) );
		$this->assertSame(
			'width:300px;&lt;style&gt;',
			$handler->getIndexFieldsForJS( Title::makeTitle( $this->getPageNamespaceId(), 'Test.jpg' ) )['CSS']
		);
	}

	public function testGetIndexFieldsForJSForCSSWithQuotes() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'width:100%;\'"Escaping quotes' )
			] )
		] ) );
		$this->assertSame(
			'width:100%;&#039;&quot;Escaping quotes',
			$handler->getIndexFieldsForJS( Title::makeTitle( $this->getIndexNamespaceId(), 'Test.jpg' ) )['CSS']
		);
	}

	public function testGetIndexFieldsForJSForCSSWithInsecureInput() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'CSS' => new WikitextContent( 'background: url(\'/my-bad-url.jpg\');' )
			] )
		] ) );
		$this->assertSame(
			'/* insecure input */',
			$handler->getIndexFieldsForJS( Title::makeTitle( $this->getIndexNamespaceId(), 'Test.jpg' ) )['CSS']
		);
	}

	public function testGetJsQualityVarsForPage() {
		$handler = new PageDisplayHandler( $this->getContext( [
			'Test.jpg' => Title::makeTitle( $this->getIndexNamespaceId(), 'Test' )
		], [
			'Test' => new IndexContent( [
				'Pages' => new WikitextContent( '[[Page:Test.jpg|42]]' )
			] )
		] ) );

		$username = 'AUserName';
		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( $username );
		$user->method( 'isHidden' )->willReturn( false );
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromName' )->with( $username )->willReturn( $user );
		$this->setService( 'UserFactory', $userFactory );
		$pageTitle = $this->makeEnglishPagePageTitle( 'Test.jpg' );
		$pageContent = self::buildPageContent(
			'', '', '',
			PageLevel::PROOFREAD, $username );

		$jsVars = $handler->getPageJsConfigVars( $pageTitle, $pageContent );

		$this->assertEqualsCanonicalizing( $jsVars, [
			'prpPageQualityUser' => $username,
			'prpPageQuality' => PageLevel::PROOFREAD,
			'prpFormattedPageNumber' => 42,
			'prpIndexTitle' => 'Index:Test',
			// It is difficult to get file info for URLs here without a lot of
			// dependency injection
			// 'prpImageThumbnail' =>
			// 'prpImageFullSize' =>
			'prpIndexFields' => [
				'Author' => '',
				'width' => '',
				'CSS' => '',
			]
		] );
	}
}
