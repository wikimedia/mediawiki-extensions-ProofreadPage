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
 * An index page
 */
class ProofreadIndexPage {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @param Title $title Reference to a Title object.
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * Create a new ProofreadIndexPage from a Title object
	 * @param Title $title
	 * @return ProofreadIndexPage
	 */
	public static function newFromTitle( Title $title ) {
		return new self( $title );
	}

	/**
	 * Return Title of the index page
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Check if two ProofreadIndexPage are equals
	 *
	 * @param ProofreadIndexPage $that
	 * @return bool
	 */
	public function equals( ProofreadIndexPage $that ) {
		return $this->title->equals( $that->getTitle() );
	}
}
