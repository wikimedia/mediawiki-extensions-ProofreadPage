<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup ProofreadPage
 */

/**
 * Proofreading level of a Page: page
 */
class ProofreadPageLevel {

	/**
	 * @var integer proofreading level of the page
	 */
	protected $level = 1;

	/**
	 * @var User|null last user of the page
	 */
	protected $user = null;

	/**
	 * Constructor
	 * @param $level integer
	 * @param $user User|null
	 */
	public function __construct( $level = 1, User $user = null ) {
		$this->level = $level;
		$this->user = $user;
	}

	/**
	 * returns the proofreading level
	 * @return integer
	 */
	public function getLevel() {
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
	 * @returns boolean
	 */
	public function isValid() {
		return is_integer( $this->level ) && 0 <= $this->level && $this->level <= 4;
	}

	/**
	 * Returns if the level is the same as the level $that
	 *
	 * @params $that ProofreadPageLevel
	 * @returns boolean
	 */
	public function equals( ProofreadPageLevel $that = null ) {
		if ( $that === null ) {
			return false;
		}

		return
			$this->level === $that->getLevel() &&
			( $this->user === null && $that->getUser() === null ||
				$this->user instanceof User && $that->getUser() instanceof User &&
				$this->user->getName() === $that->getUser()->getName() );
	}

	/**
	 * Returns if the change of level to level $to is allowed
	 *
	 * @params $to ProofreadPageLevel
	 * @returns boolean
	 */
	public function isChangeAllowed( ProofreadPageLevel $to ) {
		if ( $this->level !== $to->getLevel() && ( $to->getUser() === null || !$to->getUser()->isAllowed( 'pagequality' ) ) ) {
			return false;
		}

		$fromUser = ( $this->user instanceof User ) ? $this->user : $to->getUser();
		if ( $to->getLevel() === 4 && ( $this->level < 3 || $this->level === 3 && $fromUser->getName() === $to->getUser()->getName() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Parse an user name
	 *
	 * @param $name string
	 * @return User|null
	 */
	public static function getUserFromUserName( $name = '' ) {
		if ( $name === '' ) {
			return null;
		} elseif ( IP::isValid( $name ) ) {
			return User::newFromName( IP::sanitizeIP( $name ), false );
		} else {
			$user = User::newFromName( $name );
			return ( $user === false ) ? null : $user;
		}
	}

	/**
	 * @return string
	 */
	public function getLevelCategoryName() {
		return wfMessage( 'proofreadpage_quality' . $this->level . '_category' )->inContentLanguage()->plain();
	}
}