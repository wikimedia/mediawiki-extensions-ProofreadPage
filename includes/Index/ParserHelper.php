<?php

namespace ProofreadPage\Index;

use Parser;
use ParserOptions;
use Title;

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

	public function __construct( Title $title = null, ParserOptions $options ) {
		$this->parser = new Parser();
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
		return $this->parser->mStripState->unstripBoth( $text );
	}

	public function fetchTemplateTextAndTitle( Title $title ) {
		return $this->parser->fetchTemplateAndTitle( $title );
	}
}
