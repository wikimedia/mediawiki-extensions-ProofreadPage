<?php

namespace ProofreadPage\Index;

use MediaWiki\Context\RequestContext;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\IndexRedirectContent
 */
class IndexRedirectContentTest extends ProofreadPageTestCase {

	/**
	 * @var RequestContext
	 */
	private $requestContext;

	protected function setUp(): void {
		parent::setUp();

		$this->requestContext = new RequestContext();
		$this->requestContext->setTitle( Title::makeTitle( self::getIndexNamespaceId(), 'Test.pdf' ) );
		$this->requestContext->setUser( new User() );
	}

	public function testGetModel() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame( CONTENT_MODEL_PROOFREAD_INDEX, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame(
			CONTENT_MODEL_PROOFREAD_INDEX, $content->getContentHandler()->getModelID()
		);
	}

	public function testCopy() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame( $content, $content->copy() );
	}

	public static function equalsProvider() {
		return [
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				true
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				new IndexRedirectContent( Title::newFromText( 'Bar' ) ),
				false
			],
			[
				new IndexRedirectContent( Title::newFromText( 'Foo' ) ),
				new IndexContent( [] ),
				false
			],
		];
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( IndexRedirectContent $a, $b, $equal ) {
		$this->assertSame( $equal, $a->equals( $b ) );
	}

	public function testGetWikitextForTransclusion() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame( '#REDIRECT [[Foo]]', $content->getWikitextForTransclusion() );
	}

	public function testGetTextForSummary() {
		$this->assertSame(
			'', ( new IndexRedirectContent( Title::newFromText( 'Foo' ) ) )->getTextForSummary( 16 )
		);
	}

	public function testPreSaveTransform() {
		$contentTransformer = $this->getServiceContainer()->getContentTransformer();
		$user = UserIdentityValue::newAnonymous( '123.123.123.123' );
		$options = ParserOptions::newFromUser( $user );
		$originalContent = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$content = $contentTransformer->preSaveTransform(
			$originalContent,
			PageReferenceValue::localReference( self::getIndexNamespaceId(), 'Test.pdf' ),
			$user,
			$options
		);

		$this->assertTrue( $content->equals( $originalContent ) );
	}

	public function testPreloadTransform() {
		$contentTransformer = $this->getServiceContainer()->getContentTransformer();
		$options = ParserOptions::newFromAnon();
		$originalContent = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$content = $contentTransformer->preloadTransform(
			$originalContent,
			PageReferenceValue::localReference( self::getIndexNamespaceId(), 'Test.pdf' ),
			$options
		);

		$this->assertSame( $originalContent, $content );
	}

	public function testGetSize() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame( 3, $content->getSize() );
	}

	public function testGetRedirectTarget() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertSame( Title::newFromText( 'Foo' ), $content->getRedirectTarget() );
	}

	public function testUpdateRedirect() {
		$content = new IndexRedirectContent( Title::newFromText( 'Foo' ) );
		$this->assertEquals(
			new IndexRedirectContent( Title::newFromText( 'Bar' ) ),
			$content->updateRedirect( Title::newFromText( 'Bar' ) )
		);
	}
}
