<?php

namespace ProofreadPage\Parser;

use Parser;

/**
 * @licence GNU GPL v2+
 *
 * Abstract structure for a tag parser
 */
abstract class TagParser {

	/**
	 * @var Parser
	 */
	protected $parser;

	/**
	 * @param Parser $parser the current parser
	 */
	public function __construct( Parser $parser ) {
		$this->parser = $parser;
	}

	/**
	 * Render a <pagelist> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @return string
	 */
	public abstract function render( $input, array $args );
}
