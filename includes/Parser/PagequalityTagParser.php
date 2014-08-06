<?php

namespace ProofreadPage\Parser;

use Html;

/**
 * @licence GNU GPL v2+
 *
 * Parser for the <pagequality> tag
 */
class PagequalityTagParser extends TagParser {

	/**
	 * @see TagParser::render
	 */
	public function render( $input, array $args ) {
		if ( !array_key_exists( 'level', $args ) || !is_numeric( $args['level'] ) || 0 > $args['level'] || $args['level'] > 4 ) {
			return '';
		}

		return Html::openElement( 'div', array( 'class' => 'prp-page-qualityheader quality' . $args['level'] ) ) .
			wfMessage( 'proofreadpage_quality' . $args['level'] . '_message' )->inContentLanguage()->parse() .
			Html::closeElement( 'div' );
	}
}
