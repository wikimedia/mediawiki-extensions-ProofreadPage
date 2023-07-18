<?php

namespace ProofreadPage\Page;

use MediaWiki\Permissions\PermissionManager;
use ProofreadPageTestCase;
use User;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Page\PageLevel
 */
class PageLevelTest extends ProofreadPageTestCase {

	public function testGetLevel() {
		$level = new PageLevel( 1, null );
		$this->assertSame( 1, $level->getLevel() );
	}

	public function testGetUser() {
		$user = User::newFromName( 'aaa' );
		$level = new PageLevel( 1, $user );
		$this->assertSame( $user, $level->getUser() );
	}

	public static function equalsProvider() {
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
		$this->assertSame( $equal, $a->equals( $b ) );
	}

	public static function isChangeAllowedProvider() {
		$testUser = User::newFromName( 'Test' );
		$test2User = User::newFromName( 'Test2' );
		$test3User = User::newFromName( 'Test3' );
		$test4User = User::newFromName( 'Test4' );
		$ipUser = User::newFromName( '172.16.254.7', false );

		$rights = [
			'Test' => [ 'pagequality' ],
			'Test2' => [ 'pagequality' ],
			'Test3' => [ 'pagequality', 'pagequality-admin' ],
			'Test4' => [ 'pagequality', 'pagequality-validate' ]
		];

		return [
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $ipUser ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $test2User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, null ),
				new PageLevel( PageLevel::NOT_PROOFREAD, $ipUser ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, null ),
				new PageLevel( PageLevel::PROOFREAD, $ipUser ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				false,
				$rights
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test2User ),
				false,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test2User ),
				false,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test4User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test2User ),
				false,
				$rights
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, null ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				false,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, $test3User ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::PROOFREAD, null ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::NOT_PROOFREAD, $test3User ),
				new PageLevel( PageLevel::VALIDATED, $test3User ),
				true,
				$rights
			],
			[
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				new PageLevel( PageLevel::VALIDATED, $testUser ),
				true,
				$rights
			],
		];
	}

	/**
	 * @dataProvider isChangeAllowedProvider
	 */
	public function testIsChangeAllowed( PageLevel $old, PageLevel $new, $result, array $rights ) {
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager
			->expects( $this->any() )
			->method( 'userHasRight' )
			->willReturnCallback( static function ( User $user, string $right ) use ( $rights ) {
				return in_array( $right, $rights[ $user->getName() ], true );
			} );

		$this->assertSame( $result, $old->isChangeAllowed( $new, $permissionManager ) );
	}

	public static function nameProvider() {
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
		$this->assertSame( 'Not proofread', $level->getLevelCategoryName() );
	}
}
