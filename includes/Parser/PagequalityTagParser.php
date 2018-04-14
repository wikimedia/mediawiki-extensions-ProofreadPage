<?php

namespace ProofreadPage\Parser;

use Html;

/**
 * @license GPL-2.0-or-later
 *
 * Parser for the <pagequality> tag
 */
class PagequalityTagParser {

	/**
	 * Render a <pagequality> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @return string
	 */
	public function render( $input, array $args ) {
		if ( !array_key_exists( 'level', $args ) || !is_numeric( $args['level'] ) ||
			0 > $args['level'] || $args['level'] > 4
		) {
			return '';
		}

		return Html::openElement( 'div',
			[ 'class' => 'prp-page-qualityheader quality' . $args['level'] ] ) .
			wfMessage( 'proofreadpage_quality' . $args['level'] . '_message' )
				->inContentLanguage()->parse() .
			Html::closeElement( 'div' );
	}
}
