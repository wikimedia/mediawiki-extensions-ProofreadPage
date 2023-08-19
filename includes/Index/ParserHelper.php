<?php

namespace ProofreadPage\Index;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use Parser;
use ParserOptions;

/**
 * @license GPL-2.0-or-later
 *
 * Content of a Index: page
 */
class ParserHelper {

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @param Title|null $title
	 * @param ParserOptions $options
	 */
	public function __construct( ?Title $title, ParserOptions $options ) {
		$this->parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$this->parser->startExternalParse( $title, $options, Parser::OT_PREPROCESS );
	}

	/**
	 * @param string $text the wikitext that should see its template arguments expanded
	 * @param string[] $args the arguments to use during the expansion
	 * @return string
	 */
	public function expandTemplateArgs( $text, array $args ) {
		// We build a frame with the arguments for the template
		$frame = $this->parser->getPreprocessor()->newCustomFrame( $args );

		// We replace the arguments calls by their values
		$dom = $this->parser->preprocessToDom( $text, Parser::PTD_FOR_INCLUSION );
		$text = $frame->expand( $dom );

		// We take care of removing the tags placeholders
		return $this->parser->getStripState()->unstripBoth( $text );
	}

	/**
	 * Fetches unparsed text of a template
	 * @param Title $title
	 * @see \Parser::fetchTemplateAndTitle()
	 * @return array (string|bool, Title)
	 */
	public function fetchTemplateTextAndTitle( Title $title ) {
		return $this->parser->fetchTemplateAndTitle( $title );
	}
}
