<?php

namespace ProofreadPage\Parser;

use DOMException;
use MediaWiki\MediaWikiServices;
use ProofreadPage\Page\PageLevel;
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

		$extArgs = $extApi->extArgsToArray( $extArgs );
		$level = $extArgs['level'] ?? null;

		$levelIsValid = is_numeric( $level ) &&
			$level >= PageLevel::WITHOUT_TEXT &&
			$level <= PageLevel::VALIDATED;

		if ( !$levelIsValid ) {
			return false;
		}

		$doc = $extApi->getTopLevelDoc();
		$elem = $doc->createElement( 'div' );
		$elem->setAttribute( 'class', 'prp-page-qualityheader quality' . $level );
		$msg = wfMessage( 'proofreadpage_quality' . $level . '_message' )->inContentLanguage()->text();
		$localizedFragment = $extApi->createPageContentI18nFragment( $msg, [] );
		$elem->appendChild( $localizedFragment );
		$dom = $doc->createDocumentFragment();
		$dom->appendChild( $elem );
		return $dom;
	}
}
