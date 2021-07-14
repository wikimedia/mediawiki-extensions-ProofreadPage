<?php

namespace ProofreadPage\Page;

use Html;
use OutOfBoundsException;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\PageNumberNotFoundException;
use Sanitizer;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Utility class to do operations related to Page: page display
 */
class PageDisplayHandler {

	/**
	 * @var integer default width for scan image
	 */
	public const DEFAULT_IMAGE_WIDTH = 1024;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @param Context $context
	 */
	public function __construct( Context $context ) {
		$this->context = $context;
	}

	/**
	 * Return the scan image width for display
	 * @param Title $pageTitle
	 * @return int
	 */
	public function getImageWidth( Title $pageTitle ) {
		$indexTitle = $this->context->getIndexForPageLookup()->getIndexForPageTitle( $pageTitle );
		if ( $indexTitle !== null ) {
			try {
				$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
				$width = $this->context->getCustomIndexFieldsParser()->parseCustomIndexField(
					$indexContent, 'width'
				)->getStringValue();
				if ( is_numeric( $width ) ) {
					return (int)$width;
				}
			} catch ( OutOfBoundsException $e ) {
				return self::DEFAULT_IMAGE_WIDTH;
			}
		}
		return self::DEFAULT_IMAGE_WIDTH;
	}

	/**
	 * Return custom CSS for the page
	 * Is protected against XSS
	 * @param Title $pageTitle
	 * @return string
	 */
	public function getCustomCss( Title $pageTitle ) {
		$indexTitle = $this->context->getIndexForPageLookup()->getIndexForPageTitle( $pageTitle );
		if ( $indexTitle === null ) {
			return '';
		}
		try {
			$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
			$css = $this->context->getCustomIndexFieldsParser()->parseCustomIndexField(
				$indexContent, 'css'
			);
			return Sanitizer::escapeHtmlAllowEntities(
				Sanitizer::checkCss( $css->getStringValue() )
			);
		} catch ( OutOfBoundsException $e ) {
			return '';
		}
	}

	/**
	 * Santizes and serializes the raw index fields of the associated index page so
	 * that they can be included inside mw.config in the Page namespace.
	 * @param Title $title
	 * @return array|null Santized and serialized fields or null if no index page is found
	 */
	public function getIndexFieldsForJS( Title $title ) {
		$indexTitle = $this->context->getIndexForPageLookup()->getIndexForPageTitle( $title );

		if ( $indexTitle === null ) {
			return null;
		}

		$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );

		$indexFields = $this->context->getCustomIndexFieldsParser()->parseCustomIndexFieldsForJs( $indexContent );

		$serializedFields = [];
		foreach ( $indexFields as $key => $val ) {
			// fields may contain raw XSS payloads, santize before putting it in
			if ( strtolower( $key ) === 'css' ) {
				$serializedFields[$key] = htmlspecialchars( Sanitizer::checkCss( $val->getStringValue() ), ENT_QUOTES );
			} else {
				$serializedFields[$key] = htmlspecialchars( $val->getStringValue() );
			}
		}
		return $serializedFields;
	}

	/**
	 * Return the part of the page container that is before page content
	 * @return string
	 */
	public function buildPageContainerBegin() {
		return Html::openElement( 'div', [ 'class' => 'prp-page-container' ] ) .
			Html::openElement( 'div', [ 'class' => 'prp-page-content' ] );
	}

	/**
	 * Return the part of the page container that after page content
	 * @param Title $pageTitle
	 * @return string
	 */
	public function buildPageContainerEnd( Title $pageTitle ) {
		return Html::closeElement( 'div' ) .
			Html::openElement( 'div', [ 'class' => 'prp-page-image' ] ) .
			$this->buildImageHtml( $pageTitle, [ 'max-width' => $this->getImageWidth( $pageTitle ) ] ) .
			Html::closeElement( 'div' ) .
			Html::closeElement( 'div' );
	}

	/**
	 * Return HTML for the image
	 * @param Title $pageTitle
	 * @param array $options
	 * @return null|string
	 */
	private function buildImageHtml( Title $pageTitle, array $options ) {
		$fileProvider = $this->context->getFileProvider();
		try {
			$image = $fileProvider->getFileForPageTitle( $pageTitle );
		} catch ( FileNotFoundException $e ) {
			return null;
		}
		if ( !$image->exists() ) {
			return null;
		}
		$width = $image->getWidth();
		if ( isset( $options['max-width'] ) && $width > $options['max-width'] ) {
			$width = $options['max-width'];
		}
		$transformAttributes = [
			'width' => $width
		];

		if ( $image->isMultipage() ) {
			try {
				$transformAttributes['page'] = $fileProvider->getPageNumberForPageTitle( $pageTitle );
			} catch ( PageNumberNotFoundException $e ) {
			}
		}
		$handler = $image->getHandler();
		if ( !$handler || !$handler->normaliseParams( $image, $transformAttributes ) ) {
			return null;
		}
		$thumbnail = $image->transform( $transformAttributes );
		if ( !$thumbnail ) {
			return null;
		}
		return $thumbnail->toHtml( $options );
	}
}
