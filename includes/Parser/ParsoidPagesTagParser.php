<?php

namespace ProofreadPage\Parser;

use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use ProofreadPage\Context;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Text;
use Wikimedia\Parsoid\Ext\ExtensionTagHandler;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;
use Wikimedia\Parsoid\Ext\Utils;
use Wikimedia\Parsoid\Fragments\WikitextPFragment;
use Wikimedia\Parsoid\Utils\DOMCompat;

/**
 * Parsoid implementation of the <pages> tag handler
 */
class ParsoidPagesTagParser extends ExtensionTagHandler {
	use ParsoidTrait;
	use PagesTagRendererTrait;

	private ParsoidExtensionAPI $extApi;
	private Context $context;
	private \MediaWiki\Languages\LanguageFactory $languageFactory;
	private bool $useParsoid;

	public function __construct() {
		$this->languageFactory = MediaWikiServices::getInstance()->getLanguageFactory();
		$this->useParsoid = MediaWikiServices::getInstance()->getMainConfig()->get( 'ProofreadPageUseParsoid' );
	}

	/**
	 * Get the target language for the current page.
	 *
	 * @return Language The target language
	 */
	public function getTargetLanguage(): Language {
		$pageConfig = $this->extApi->getPageConfig();
		$language = $pageConfig->getPageLanguageBcp47();
		return $this->languageFactory->getLanguage( $language );
	}

	/**
	 * @param ParsoidExtensionAPI $extApi
	 * @param string $src
	 * @param array $extArgs
	 * @return DocumentFragment|false|null
	 * @throws \DOMException
	 */
	public function sourceToDom( ParsoidExtensionAPI $extApi, string $src, array $extArgs ) {
		if ( !$this->useParsoid ) {
			return false;
		}

		$this->context = Context::getDefaultContext( true );
		$args = $extApi->extArgsToArray( $extArgs );
		$this->extApi = $extApi;
		try {
			[ 'output' => $out, 'contentLang' => $contentLang ] = $this->renderTag( $this->context, $args );
		} catch ( ParserError $e ) {
			return $e->getDocumentFragment( $extApi );
		}

		$separator = $this->context->getConfig()->get( 'ProofreadPagePageSeparator' );
		$joiner = $this->context->getConfig()->get( 'ProofreadPagePageJoiner' );
		$placeholder = $this->context->getConfig()->get( 'ProofreadPagePageSeparatorPlaceholder' );

		$domFragment = $extApi->wikitextToDOM( $out, [
			'processInNewFrame' => true,
			'parseOpts' => [],
			'clearDSROffsets' => true
		], true );

		$separator = Utils::decodeWtEntities( $separator );
		self::replacePlaceholder( $domFragment, $separator, $joiner, $placeholder );

		$doc = $domFragment->ownerDocument;
		$wrapper = $doc->createElement( 'div' );
		$wrapper->setAttribute( 'class', 'prp-pages-output' );

		if ( $contentLang !== null ) {
			$wrapper->setAttribute( 'lang', $contentLang );
		}

		DOMCompat::appendChild( $wrapper, $domFragment );
		$domFragment->appendChild( $wrapper );

		return $domFragment;
	}

	/**
	 * Attempts to replicate:
	 * $out = str_replace( $joiner . $placeholder, '', $out );
	 * $out = str_replace( $placeholder, $separator, $out );
	 */
	public static function replacePlaceholder(
		DocumentFragment|Element $node, string $separator, string $joiner, string $placeholder
	) {
		$c = $node->firstChild;
		while ( $c ) {
			$next = $c->nextSibling;
			if ( $c instanceof Text && $c->nodeValue === $placeholder ) {
				$pageLastNode = $c->previousSibling->lastChild ?? null;
				$lastCharOfPage = substr( $pageLastNode->nodeValue ?? '', -1 );
				if ( $lastCharOfPage === $joiner ) {
					$pageLastNode->nodeValue = substr( $pageLastNode->nodeValue, 0, -1 );
					$c->parentNode->removeChild( $c );
				} else {
					$c->nodeValue = $separator;
				}
			} elseif ( $c instanceof Element ) {
				self::replacePlaceholder( $c, $separator, $joiner, $placeholder );
			}
			$c = $next;
		}
	}

	/**
	 * @param string $wikitext
	 * @param Title $title
	 * @return string
	 */
	public function preprocessWikitext( string $wikitext, Title $title ): string {
		$error = false;
		$fragment = $this->extApi->preprocessFragment(
			WikitextPFragment::newFromWt( $wikitext, null ),
			$error
		);
		if ( !$error ) {
			return $fragment->killMarkers();
		}
		return $wikitext;
	}
}
