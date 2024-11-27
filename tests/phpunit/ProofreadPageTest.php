<?php

namespace ProofreadPage;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;
use ProofreadPage\Page\PageContent;
use ProofreadPage\Page\PageLevel;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\ProofreadPage
 */
class ProofreadPageTest extends ProofreadPageTestCase {
	public static function provideOnMultiContentSave() {
		return [
			[
				Status::newGood(),
				self::buildPageContent()
			],
			[
				Status::newFatal( 'invalid-content-data' ),
				self::buildPageContent( '', '', '', 5 )
			],
			[
				Status::newFatal( 'proofreadpage_notallowedtext' ),
				self::buildPageContent( '', '', '', PageLevel::VALIDATED )
			]
		];
	}

	/**
	 * @dataProvider provideOnMultiContentSave
	 */
	public function testOnMultiContentSave( Status $expectedResult, PageContent $content ) {
		$pageIdentity = new PageIdentityValue( 5, NS_MAIN, 'Test', PageIdentityValue::LOCAL );

		$revRecord = $this->getMockBuilder( MutableRevisionRecord::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getContent', 'getParentId', 'getPage' ] )
			->getMock();
		$revRecord->method( 'getContent' )
			->willReturn( $content );
		$revRecord->method( 'getParentId' )
			->willReturn( -1 );
		$revRecord->method( 'getPage' )
			->willReturn( $pageIdentity );

		$renderedRev = $this->getMockBuilder( RenderedRevision::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'getRevision' ] )
			->getMock();
		$renderedRev->method( 'getRevision' )
			->willReturn( $revRecord );

		$userIdentity = $this->getMockBuilder( UserIdentity::class )
			->disableOriginalConstructor()
			->getMock();

		$summary = $this->getMockBuilder( CommentStoreComment::class )
			->disableOriginalConstructor()
			->getMock();

		$hookContainer = $this->getServiceContainer()->getHookContainer();
		$hookRunner = new HookRunner( $hookContainer );
		$hookStatus = Status::newGood();
		$hookRunner->onMultiContentSave(
			$renderedRev,
			$userIdentity,
			$summary,
			0,
			$hookStatus
		);

		$this->assertEquals( $expectedResult, $hookStatus );
	}
}
