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

use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;

/**
 * The content of a page page
 */
class ProofreadPagePage {

	/**
	 * @var integer default width for scan image
	 */
	const DEFAULT_IMAGE_WIDTH = 1024;

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var ProofreadIndexPage|null index related to the page
	 */
	protected $index;

	/**
	 * @param $title Title Reference to a Title object
	 * @param $index ProofreadIndexPage index related to the page
	 */
	public function __construct( Title $title, ProofreadIndexPage $index = null ) {
		$this->title = $title;
		$this->index = $index;
	}

	/**
	 * Create a new ProofreadPagePage from a Title object
	 * @param $title Title
	 * @return ProofreadPagePage
	 */
	public static function newFromTitle( Title $title ) {
		return new self( $title );
	}

	/**
	 * Check if two ProofreadPagePage are equals
	 *
	 * @param ProofreadPagePage $that
	 * @return boolean
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
	 * @return integer|null
	 */
	public function getPageNumber() {
		$parts = explode( '/', $this->title->getText() );
		if ( count( $parts ) === 1 ) {
			return null;
		}
		return (int) $this->title->getPageLanguage()->parseFormattedNumber( $parts[count( $parts ) - 1] );
	}

	/**
	 * Return index of the page if it exist or false.
	 * @return ProofreadIndexPage|false
	 */
	public function getIndex() {
		if( $this->index !== null ) {
			return $this->index;
		}

		$result = ProofreadIndexDbConnector::getRowsFromTitle( $this->title );

		foreach ( $result as $x ) {
			$refTitle = Title::makeTitle( $x->page_namespace, $x->page_title );
			if ( $refTitle !== null && $refTitle->inNamespace( ProofreadPage::getIndexNamespaceId() ) ) {
				$this->index = ProofreadIndexPage::newFromTitle( $refTitle );
				return $this->index;
			}
		}

		$m = explode( '/', $this->title->getText(), 2 );
		if ( isset( $m[1] ) ) {
			$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[0] );
			if ( $imageTitle !== null ) {
				$image = wfFindFile( $imageTitle );
				// if it is multipage, we use the page order of the file
				if ( $image && $image->exists() && $image->isMultipage() ) {
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
	 * @depreciated use FileProvider::getForPagePage
	 *
	 * Return image of the page if it exist or false.
	 * @return File|false
	 */
	public function getImage() {
		try {
			return Context::getDefaultContext()->getFileProvider()->getForPagePage( $this );
		} catch( FileNotFoundException $e ) {
			return false;
		}
	}

	/**
	 * Return the scan image width for display
	 * @return integer
	 */
	public function getImageWidth() {
		$index = $this->getIndex();
		if ( $index ) {
			$width = $index->getIndexEntry( 'width' );
			if ( $width !== null ) {
				$width = $width->getStringValue();
				if ( is_numeric( $width ) ) {
					return $width;
				}
			}
		}

		return self::DEFAULT_IMAGE_WIDTH;
	}

	/**
	 * Return custom CSS for the page
	 * Is protected against XSS
	 * @return string
	 */
	public function getCustomCss() {
		$index = $this->getIndex();
		if ( $index ) {
			$css = $index->getIndexEntry( 'css' );
			if ( $css !== null ) {
				return Sanitizer::escapeHtmlAllowEntities( Sanitizer::checkCss( $css->getStringValue() ) );
			}
		}

		return '';
	}

	/**
	 * Return HTML for the image
	 * @param $options array
	 * @return string|null
	 */
	public function getImageHtml( $options ) {
		$image = $this->getImage();
		if ( !$image || !$image->exists() ) {
			return null;
		}
		$width = $image->getWidth();
		if ( isset( $options['max-width'] ) && $width > $options['max-width'] ) {
			$width = $options['max-width'];
		}
		$transformAttributes = array(
			'width' => $width
		);

		if ( $image->isMultipage() ) {
			$pageNumber = $this->getPageNumber();
			if ( $pageNumber !== null ) {
				$transformAttributes['page'] = $pageNumber;
			}
		}
		$handler = $image->getHandler();
		if( !$handler || !$handler->normaliseParams( $image, $transformAttributes ) ) {
			return null;
		}
		$thumbnail = $image->transform( $transformAttributes );
		return $thumbnail->toHtml( $options );
	}

	/**
	 * Return the part of the page container that is before page content
	 * @return string
	 */
	public function getPageContainerBegin() {
		return
			Html::openElement( 'div', array( 'class' => 'prp-page-container' )  ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-content' ) );
	}

	/**
	 * Return the part of the page container that after page cnotent
	 * @return string
	 */
	public function getPageContainerEnd() {
		return
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-image' ) ) .
			$this->getImageHtml( array( 'max-width' => $this->getImageWidth() ) ) .
			Html::closeElement( 'div' ) .
			Html::closeElement( 'div' );
	}
}
