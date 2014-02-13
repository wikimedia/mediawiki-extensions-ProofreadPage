<?php

namespace ProofreadPage\Parser;

use Parser;
use ProofreadPage\Context;

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
	 * @var Context
	 */
	protected $context;

	/**
	 * @param Parser $parser the current parser
	 */
	public function __construct( Parser $parser, Context $context ) {
		$this->parser = $parser;
		$this->context = $context;
	}

	/**
	 * Render a <pagelist> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @return string
	 */
	public abstract function render( $input, array $args );

	/**
	 * @param string $errorMsg
	 * @return string
	 */
	protected function formatError( $errorMsg ) {
		return '<strong class="error">' . wfMessage( $errorMsg )->inContentLanguage()->escaped() . '</strong>';
	}
}
