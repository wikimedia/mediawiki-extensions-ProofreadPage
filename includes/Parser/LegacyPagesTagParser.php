<?php

namespace ProofreadPage\Parser;

use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use ProofreadPage\Context;
use ProofreadPage\Index\IndexContentHandler;
use UnexpectedValueException;

/**
 * @license GPL-2.0-or-later
 *
 * Parser for the <pages> tag
 */
class LegacyPagesTagParser {

	use LegacyParserTrait;
	use PagesTagRendererTrait;

	/**
	 * @param Parser $parser
	 * @param Context $context
	 */
	public function __construct( Parser $parser, Context $context ) {
		$this->parser = $parser;
		$this->context = $context;
	}

	public function getTargetLanguage(): \MediaWiki\Language\Language {
		return $this->parser->getTargetLanguage();
	}

	/**
	 * Render a <pages> tag
	 *
	 * @param array $args tags arguments
	 * @return string
	 */
	public function render( array $args ) {
		// abort if this is nested <pages> call
		// FIXME: remove this: T362664
		if ( $this->parser->proofreadRenderingPages ?? false ) {
			return '';
		}
		try {
			$out = $this->renderTag( $this->context, $args );
		} catch ( ParserError $e ) {
			$out = $e->getHtml();
		}
		$this->parser->proofreadRenderingPages = true;
		$out = $this->parser->recursiveTagParse( $out );
		$separator = $this->context->getConfig()->get( 'ProofreadPagePageSeparator' );
		$joiner = $this->context->getConfig()->get( 'ProofreadPagePageJoiner' );
		$placeholder = $this->context->getConfig()->get( 'ProofreadPagePageSeparatorPlaceholder' );
		$out = str_replace( $joiner . $placeholder, '', $out );
		$out = str_replace( $placeholder, $separator, $out );
		$this->parser->proofreadRenderingPages = false;
		return $out;
	}

	/**
	 * @param string $wikitext
	 * @param Title $title
	 * @return string
	 */
	public function preprocessWikitext( string $wikitext, Title $title ): string {
		$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $title );
		$contentHandler = $indexContent->getContentHandler();
		if ( !( $contentHandler instanceof IndexContentHandler ) ) {
			throw new UnexpectedValueException( 'Expected IndexContentHandler' );
		}
		$parser = $contentHandler->getParser();
		$parserOptions = ParserOptions::newFromAnon();
		return $parser->preprocess( $wikitext, $title, $parserOptions );
	}
}
