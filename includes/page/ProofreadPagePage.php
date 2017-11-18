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
 * The content of a page page
 */
class ProofreadPagePage {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @param Title $title Reference to a Title object
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * Create a new ProofreadPagePage from a Title object
	 * @param Title $title
	 * @return ProofreadPagePage
	 */
	public static function newFromTitle( Title $title ) {
		return new self( $title );
	}

	/**
	 * Check if two ProofreadPagePage are equals
	 *
	 * @param ProofreadPagePage $that
	 * @return bool
	 */
	public function equals( ProofreadPagePage $that ) {
		return $this->title->equals( $that->getTitle() );
	}

	/**
	 * Returns Title of the index page
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Returns number of the page in the file if it's a multi-page file or null
	 * @return int|null
	 */
	public function getPageNumber() {
		$parts = explode( '/', $this->title->getText() );
		if ( count( $parts ) === 1 ) {
			return null;
		}
		return (int)$this->title->getPageLanguage()
			->parseFormattedNumber( $parts[count( $parts ) - 1] );
	}
}
