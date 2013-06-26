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
	 * @var ProofreadPageContent content of the page
	 */
	protected $content;

	/**
	 * @var ProofreadIndexPage|null index related to the page
	 */
	protected $index;

	/**
	 * Constructor
	 * @param $title Title Reference to a Title object.
	 * @param $content ProofreadPageContent content of the page. Warning: only done for EditProofreadPagePage use.
	 * @param $index ProofreadIndexPage index related to the page.
	 */
	public function __construct( Title $title, ProofreadPageContent $content = null, ProofreadIndexPage $index = null ) {
		$this->title = $title;
		$this->content = $content;
		$this->index = $index;
	}

	/**
	 * Create a new ProofreadPagePage from a Title object
	 * @param $title Title
	 * @return ProofreadPagePage
	 */
	public static function newFromTitle( Title $title ) {
		return new self( $title, null, null );
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
	 * @return integer|null
	 */
	protected function getPageNumber() {
		$parts = explode( '/', $this->title->getText() );
		if ( count( $parts ) === 1 ) {
			return null;
		}
		$val = $wgContLang->parseFormattedNumber( $parts[count( $parts ) - 1] );
		if ( !is_integer( $val ) ) {
			return null;
		}
		return (int) $val;
	}

	/**
	 * Return index of the page if it exist or false.
	 * @return ProofreadIndexPage|false
	 */
	public function getIndex() {
		if( $this->index !== null ) {
			return $this->index;
		}

		$result = ProofreadIndexDbConnector::getRowsFromTitle( $this->title() );

		foreach ( $result as $x ) {
			$refTitle = Title::makeTitle( $x->page_namespace, $x->page_title );
			if ( $refTitle !== null && $refTitle->inNamespace( ProofreadPage::getIndexNamespaceId() ) ) {
				$this->index = ProofreadIndexPage::newFromTitle( $refTitle );
				return $this->index;
			}
		}

		$m = explode( '/', $title->getText(), 2 );
		if ( isset( $m[1] ) ) {
			$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[0] );
			if ( $imageTitle !== null ) {
				$image = wfFindFile( $imageTitle );
				// if it is multipage, we use the page order of the file
				if ( $image !== null && $image->exists() && $image->isMultipage() ) {
					$indexTitle = Title::makeTitle( ProofreadPage::getIndexNamespaceId(), $image->getTitle()->getText());
					if ( $indexTitle !== null ) {
						$this->index = ProofreadIndexPage::newFromTitle( $indexTitle );
						return $this->index;
					}
				}
			}
		}
		$this->index = false;
		return false;
	}

	/**
	 * Return content of the page
	 * @return ProofreadPageValue
	 */
	protected function getContent() {
		if ( $this->content === null ) {
			$rev = Revision::newFromTitle( $this->title );
			if ( $rev === null ) {
				$this->content = ProofreadPageValue::newEmpty();
			} else {
				$this->content = ProofreadPageValue::newFromWikitext( $rev->getText() );
			}
		}
		return $this->content;
	}

	/**
	 * Return content of the page initialised for edition
	 * @return ProofreadPageValue
	 */
	public function getContentForEdition() {
		global $wgContLang;
		$content = $this->getContent();
		if ( $content->isEmpty() ) {
			$index = $this->getIndex();
			if ( $index ) {
				list( $header, $footer, $css, $editWidth ) = $index->getIndexDataForPage();
				$content->setHeader( $header );
				$content->setFooter( $footer );

				//Extract text layer
				$image = $index->getImage();
				$pageNumber = $this->getPageNumber();
				if ( $image && $pageNumber !== null && $image->exists() ) {
					$text = $image->getHandler()->getPageText( $image, $pageNumber );
					if ( $text ) {
						$text = preg_replace( "/(\\\\n)/", "\n", $text );
						$text = preg_replace( "/(\\\\\d*)/", '', $text );
						$content->setBody( $text );
					}
				}
			}
		}
		return $content;
	}

}