<?php

namespace ProofreadPage\Parser;

use Parser;

/**
 * @licence GNU GPL v2+
 *
 * Entry point for parser hooks
 */
class ParserEntryPoint {

	/**
	 * Parser hook for <pagelist> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @param Parser $parser the current parser
	 * @return string
	 */
	public static function renderPagelistTag( $input, array $args, Parser $parser ) {
		$tagParser = new PagelistTagParser( $parser );
		return $tagParser->render( $input, $args );
	}

	/**
	 * Parser hook for <pages> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @param Parser $parser the current parser
	 * @return string
	 */
	public static function renderPagesTag( $input, array $args, Parser $parser ) {
		$tagParser = new PagesTagParser( $parser );
		return $tagParser->render( $input, $args );
	}
}
