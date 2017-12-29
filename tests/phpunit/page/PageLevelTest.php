<?php

namespace ProofreadPage\Page;

use ProofreadPageTestCase;
use User;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageLevel
 */
class PageLevelTest extends ProofreadPageTestCase {

	public function setUp() {
		global $wgGroupPermissions;
		parent::setUp();

		$wgGroupPermissions['*']['pagequality'] = false;
		$wgGroupPermissions['user']['pagequality'] = true;
		$wgGroupPermissions['*']['pagequality-admin'] = false;
		$wgGroupPermissions['sysop']['pagequality-admin'] = true;
	}

	public function testGetLevel() {
		$level = new PageLevel( 1, null );
		$this->assertEquals( 1, $level->getLevel() );
	}

	public function testGetUser() {
		$user = User::newFromName( 'aaa' );
		$level = new PageLevel( 1, $user );
		$this->assertEquals( $user, $level->getUser() );
	}

	public function equalsProvider() {
		return [
			[
				new PageLevel( 1, null ),
				new PageLevel( 2, null ),
				false
			],
			[
				new PageLevel( 1, User::newFromName( 'test' ) ),
				new PageLevel( 1, null ),
				false
			],
			[
				new PageLevel( 1, User::newFromName( 'ater_ir' ) ),
				new PageLevel( 1, User::newFromName( 'ater ir' ) ),
				true
			],
			[
				new PageLevel( 1, null ),
				null,
				false
			]
		];
	}

	/**
	 * @dataProvider equalsProvider
	 */
	public function testEquals( $a, $b, $equal ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public function isChangeAllowedProvider() {
		$testUser = User::newFromName( 'Test' );
		$testUser->addToDatabase();
		$testUser->addGroup( 'user' );
		$test2User = User::newFromName( 'Test2' );
		$test2User->addToDatabase();
		$test2User->addGroup( 'user' );
		$test3User = User::newFromName( 'Test3' );
		$test3User->addToDatabase();
		$test3User->addGroup( 'sysop' );
		$ipUser = User::newFromName( '172.16.254.7', false );

		return [
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $ipUser ),
				true
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $test2User ),
				true
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, null ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $ipUser ),
				true
			],
			[
				new PageLevel( PageLevel::PROOFREAD, null ),
				new PageLevel( PageLevel::PROOFREAD, $ipUser ),
				true
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				false
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test2User ),
				false
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, null ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				false
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $test3User ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true
			],
			[
				new PageLevel( PageLevel::PROOFREAD, null ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $test3User ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true
			],
			[
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				true
			],
		];
	}

	/**
	 * @dataProvider isChangeAllowedProvider
	 */
	public function testIsChangeAllowed( PageLevel $old, PageLevel $new, $result ) {
		$this->assertEquals( $result, $old->isChangeAllowed( $new ) );
	}

	public function nameProvider() {
		return [
			[
				'wikiUser',
				User::newFromName( 'WikiUser' )
			],
			[
				'',
				null
			],
			[
				'172.16.254.7',
				User::newFromName( '172.16.254.7', false )
			],
			[
				'2001:odb8:ac10:fe10:00:00:00:00',
				User::newFromName( '2001:odb8:ac10:fe10:00:00:00:00', false )
			]
		];
	}

	/**
	 * @dataProvider nameProvider
	 */
	public function testGetUserFromUserName( $name, $user ) {
		$this->assertEquals( $user, PageLevel::getUserFromUserName( $name ) );
	}

	/**
	 * @dataProvider nameProvider
	 */
	public function testGetLevelCategoryName() {
		$level = new PageLevel( 1, null );
		$this->assertEquals( 'Not proofread', $level->getLevelCategoryName() );
	}
}
