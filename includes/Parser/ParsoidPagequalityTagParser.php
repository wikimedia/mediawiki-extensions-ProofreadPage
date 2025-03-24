<?php

namespace ProofreadPage\Parser;

use DOMException;
use MediaWiki\MediaWikiServices;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Ext\ExtensionTagHandler;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;

/**
 * Parsoid implementation of the <pagequality> tag handler
 */
class ParsoidPagequalityTagParser extends ExtensionTagHandler {
	private bool $useParsoid;

	public function __construct() {
		$this->useParsoid = MediaWikiServices::getInstance()->getMainConfig()->get( 'ProofreadPageUseParsoid' );
	}

	/**
	 * @param ParsoidExtensionAPI $extApi
	 * @param string $src
	 * @param array $extArgs
	 * @return DocumentFragment|false|null
	 * @throws DOMException
	 */
	public function sourceToDom( ParsoidExtensionAPI $extApi, string $src, array $extArgs ) {
		if ( !$this->useParsoid ) {
			return false;
		}

		$extArgs = array_column( $extArgs, 'v', 'k' );

		$levelExists = array_key_exists( 'level', $extArgs );
		$levelIsNumeric = $levelExists && is_numeric( $extArgs['level'] );
		$levelIsValid = $levelIsNumeric && intval( $extArgs['level'] ) >= 0 && intval( $extArgs['level'] ) <= 4;

		if ( !$levelIsValid ) {
			return false;
		}

		$doc = $extApi->getTopLevelDoc();
		$elem = $doc->createElement( 'div' );
		$elem->setAttribute( 'class', 'prp-page-qualityheader quality' . $extArgs['level'] );
		$msg = wfMessage( 'proofreadpage_quality' . $extArgs['level'] . '_message' )->inContentLanguage()->text();
		$localizedFragment = $extApi->createPageContentI18nFragment( $msg, [] );
		$elem->appendChild( $localizedFragment );
		$dom = $doc->createDocumentFragment();
		$dom->appendChild( $elem );
		return $dom;
	}
}
