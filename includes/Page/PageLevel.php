<?php

namespace ProofreadPage\Page;

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\User;
use Wikimedia\IPUtils;

/**
 * @license GPL-2.0-or-later
 *
 * Proofreading level of a Page: page
 */
class PageLevel {

	public const WITHOUT_TEXT = 0;
	public const NOT_PROOFREAD = 1;
	public const PROBLEMATIC = 2;
	public const PROOFREAD = 3;
	public const VALIDATED = 4;

	/**
	 * @var int proofreading level of the page
	 */
	protected $level = self::NOT_PROOFREAD;

	/**
	 * @var User|null last user of the page
	 */
	protected $user = null;

	/**
	 * @param int $level
	 * @param User|null $user
	 */
	public function __construct( $level = self::NOT_PROOFREAD, User $user = null ) {
		$this->level = $level;
		$this->user = $user;
	}

	/**
	 * returns the proofreading level
	 * @return int
	 */
	public function getLevel(): int {
		return $this->level;
	}

	/**
	 * returns the user
	 * @return User|null
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Returns if the level is valid
	 *
	 * @return bool
	 */
	public function isValid() {
		return is_int( $this->level ) && $this->level >= 0 && $this->level <= 4;
	}

	/**
	 * Returns if the level is the same as the level $that
	 *
	 * @param PageLevel|null $that
	 * @return bool
	 */
	public function equals( PageLevel $that = null ) {
		if ( $that === null ) {
			return false;
		}

		return $this->level === $that->getLevel() &&
			(
				( !$this->user && !$that->getUser() ) ||
				(
					$this->user && $that->getUser() &&
					$this->user->getName() === $that->getUser()->getName()
				)
			);
	}

	/**
	 * Returns if the validation of the level $to is allowed.
	 * Helper function for isChangeAllowed()
	 *
	 * @see isChangeAllowed()
	 * @param PageLevel $to
	 * @param PermissionManager $permissionManager
	 * @return bool
	 */
	public function isValidationAllowed( PageLevel $to, PermissionManager $permissionManager ) {
		if ( $this->getLevel() === self::VALIDATED ) {
			return true;
		}

		if ( $permissionManager->userHasRight( $to->getUser(), 'pagequality-admin' ) ) {
			return true;
		}

		$fromUser = $this->user ?: $to->getUser();

		$isSameUser = $fromUser !== null
			&& $fromUser->equals( $to->getUser() );

		if ( $permissionManager->userHasRight( $to->getUser(), 'pagequality-validate' ) ) {
			if ( $this->level === self::PROOFREAD && !$isSameUser ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns if the change of level to level $to is allowed
	 *
	 * @param PageLevel $to
	 * @param PermissionManager $permissionManager
	 * @return bool
	 */
	public function isChangeAllowed( PageLevel $to, PermissionManager $permissionManager ) {
		if ( $this->level !== $to->getLevel() && ( $to->getUser() === null ||
			!$permissionManager->userHasRight( $to->getUser(), 'pagequality' ) )
		) {
			return false;
		}

		if ( $to->getLevel() === self::VALIDATED ) {
			return $this->isValidationAllowed( $to, $permissionManager );
		}

		return true;
	}

	/**
	 * Parse an user name
	 *
	 * @param string|null $name
	 * @return User|null
	 */
	public static function getUserFromUserName( $name = '' ) {
		if ( $name === '' || $name === null ) {
			return null;
		} elseif ( IPUtils::isValid( $name ) ) {
			return User::newFromName( IPUtils::sanitizeIP( $name ), false );
		} else {
			$user = User::newFromName( $name );
			return ( $user === false ) ? null : $user;
		}
	}

	/**
	 * @return string
	 */
	public function getLevelCategoryName() {
		return wfMessage( 'proofreadpage_quality' . $this->level . '_category' )
			->inContentLanguage()->plain();
	}
}
