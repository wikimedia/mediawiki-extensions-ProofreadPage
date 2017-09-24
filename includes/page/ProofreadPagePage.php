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
	 * @var ProofreadIndexPage|null index related to the page
	 */
	protected $index;

	/**
	 * @param Title $title Reference to a Title object
	 * @param ProofreadIndexPage $index index related to the page
	 */
	public function __construct( Title $title, ProofreadIndexPage $index = null ) {
		$this->title = $title;
		$this->index = $index;
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

	/**
	 * Return index of the page if it exist or false.
	 * @return ProofreadIndexPage|false
	 */
	public function getIndex() {
		if ( $this->index !== null ) {
			return $this->index;
		}

		$indexTitle = $this->findIndexTitle();
		if ( $indexTitle === null ) {
			$this->index = false;
			return false;
		} else {
			$this->index = ProofreadIndexPage::newFromTitle( $indexTitle );
			return $this->index;
		}
	}

	private function findIndexTitle() {
		$possibleIndexTitle = $this->findPossibleIndexTitleBasedOnName();

		// Try to find links from Index: pages
		$result = ProofreadIndexDbConnector::getRowsFromTitle( $this->title );
		$indexesThatLinksHere = [];
		foreach ( $result as $x ) {
			$refTitle = Title::makeTitle( $x->page_namespace, $x->page_title );
			if ( $refTitle !== null &&
				$refTitle->inNamespace( ProofreadPage::getIndexNamespaceId() )
			) {
				if ( $possibleIndexTitle !== null &&
					// It is the same as the linked file, we know it's this Index:
					$refTitle->equals( $possibleIndexTitle )
				) {
					return $refTitle;
				}
				$indexesThatLinksHere[] = $refTitle;
			}
		}
		if ( !empty( $indexesThatLinksHere ) ) {
			// TODO: what should we do if there are more than 1 possible index?
			return reset( $indexesThatLinksHere );
		}

		return $possibleIndexTitle;
	}

	/**
	 * @return Title|null the index page based on the name of the Page: page and the existence
	 *   of a file with the same name
	 */
	private function findPossibleIndexTitleBasedOnName() {
		$m = explode( '/', $this->title->getText(), 2 );
		if ( isset( $m[1] ) ) {
			$imageTitle = Title::makeTitleSafe( NS_FILE, $m[0] );
			if ( $imageTitle !== null ) {
				$image = wfFindFile( $imageTitle );
				// if it is multipage, we use the page order of the file
				if ( $image && $image->exists() && $image->isMultipage() ) {
					return Title::makeTitle(
						ProofreadPage::getIndexNamespaceId(), $image->getTitle()->getText()
					);
				}
			}
		}
		return null;
	}
}
