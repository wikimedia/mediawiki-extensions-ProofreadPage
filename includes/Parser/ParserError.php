<?php

namespace ProofreadPage\Parser;

use DOMException;
use MediaWiki\Html\Html;
use RuntimeException;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;

/**
 * Exception thrown when there is an error while parsing tags
 */
class ParserError extends RuntimeException {

	private string $messageKey;

	/**
	 * @param string $messageKey The message key for the error
	 */
	public function __construct( string $messageKey ) {
		$this->messageKey = $messageKey;
		parent::__construct( $messageKey );
	}

	/**
	 * Get the message key for this error.
	 *
	 * @return string
	 */
	public function getMessageKey(): string {
		return $this->messageKey;
	}

	/**
	 * Get the error formatted as HTML.
	 *
	 * @param string $class
	 * @param bool $wrapSpan
	 * @return string
	 */
	public function getHtml( string $class = "", bool $wrapSpan = false ): string {
		$error = Html::element( 'strong',
			[ 'class' => 'error' ],
			wfMessage( $this->messageKey )->inContentLanguage()->text()
		);
		if ( !$wrapSpan ) {
			return $error;
		}
		return Html::rawElement( 'span',
			[ 'class' => $class ],
			$error
		);
	}

	/**
	 * Get the error formatted as a DocumentFragment.
	 * Used on Parsoid implementation
	 *
	 * @param ParsoidExtensionAPI $extApi
	 * @param string $class
	 * @param bool $wrapSpan
	 * @return DocumentFragment
	 * @throws DOMException
	 */
	public function getDocumentFragment(
		ParsoidExtensionAPI $extApi,
		string $class = "",
		bool $wrapSpan = false ): DocumentFragment {
		$doc = $extApi->getTopLevelDoc();
		$dom = $doc->createDocumentFragment();
		$localizedFragment = $extApi->createPageContentI18nFragment( $this->messageKey, [] );
		$error = $doc->createElement( 'strong' );
		$error->setAttribute( 'class', 'error' );
		$error->appendChild( $localizedFragment );
		if ( !$wrapSpan ) {
			$dom->appendChild( $error );
			return $dom;
		}
		$outer = $doc->createElement( 'span' );
		$outer->setAttribute( 'class', $class );
		$outer->appendChild( $error );
		$dom->appendChild( $outer );
		return $dom;
	}
}
