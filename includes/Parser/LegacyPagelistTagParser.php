<?php

namespace ProofreadPage\Parser;

use MediaWiki\Html\Html;
use MediaWiki\Parser\Parser;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
 *
 * Parser for the <pagelist> tag
 */
class LegacyPagelistTagParser {

	use LegacyParserTrait;
	use PagelistTagRendererTrait;

	/**
	 * @param Parser $parser
	 * @param Context $context
	 */
	public function __construct( Parser $parser, Context $context ) {
		$this->parser = $parser;
		$this->context = $context;
	}

	/**
	 * @param string|array $output
	 * @return string
	 */
	private function renderOutput( $output ): string {
		return Html::rawElement(
			'div',
			[ 'class' => 'prp-index-pagelist' ],
			implode( ' ', $output )
		);
	}

	/**
	 * Render a <pagelist> tag
	 *
	 * @param array $args tags arguments
	 * @return string
	 */
	public function render( array $args ): string {
		try {
			// renderTag should always return string for Legacy parser
			return $this->renderTag( $this->context, $args );
		} catch ( ParserError $e ) {
			return $e->getHtml( 'prp-index-pagelist', true );
		}
	}

}
