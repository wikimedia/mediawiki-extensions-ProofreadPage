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

use ProofreadPage\Index\IndexContent;

/**
 * An index page
 */
class ProofreadIndexPage {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var IndexContent|null content of the page
	 */
	protected $content;

	/**
	 * @param Title $title Reference to a Title object.
	 * @param IndexContent|null $content content of the page. Warning: only done for
	 *   EditProofreadIndexPage use.
	 */
	public function __construct( Title $title, IndexContent $content = null ) {
		$this->title = $title;
		$this->content = $content;
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

	/**
	 * Return content of the page
	 * @return IndexContent
	 */
	public function getContent() {
		if ( $this->content === null ) {
			$rev = Revision::newFromTitle( $this->title );
			if ( $rev === null ) {
				$this->content = new IndexContent( [] );
			} else {
				$content = $rev->getContent();
				if ( $content instanceof IndexContent ) {
					$this->content = $content;
				} else {
					$this->content = new IndexContent( [] );
				}
			}
		}
		return $this->content;
	}

	/**
	 * Return mime type of the file linked to the index page
	 * @return string|null
	 */
	public function getMimeType() {
		if ( preg_match( "/^.*\.(.{2,5})$/", $this->title->getText(), $m ) ) {
			$mimeMagic = MimeMagic::singleton();
			return $mimeMagic->guessTypesForExtension( $m[1] );
		} else {
			return null;
		}
	}
}
