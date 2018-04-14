<?php

namespace ProofreadPage;

use Html;

/**
 * @license GPL-2.0-or-later
 *
 * Utility functions to format diffs
 */
class DiffFormatterUtils {

	/**
	 * Create an header in the two columns
	 *
	 * @param string $text the header HTML
	 * @return string
	 */
	public function createHeader( $text ) {
		return Html::openElement( 'tr' ) .
			Html::rawElement( 'td', [ 'colspan' => '2', 'class' => 'diff-lineno' ], $text ) .
			Html::rawElement( 'td', [ 'colspan' => '2', 'class' => 'diff-lineno' ], $text ) .
			Html::closeElement( 'tr' );
	}

	/**
	 * Output an added line
	 *
	 * @param string $content the content of the line
	 * @return string
	 */
	public function createAddedLine( $content ) {
		return $this->createLineWrapper(
			Html::element( 'ins',  [ 'class' => 'diffchange diffchange-inline' ], $content ),
			'diff-addedline',
			'+'
		);
	}

	/**
	 * Output a deleted line
	 *
	 * @param string $content the content of the line
	 * @return string
	 */
	public function createDeletedLine( $content ) {
		return $this->createLineWrapper(
			Html::element( 'del',  [ 'class' => 'diffchange diffchange-inline' ], $content ),
			'diff-deletedline',
			'-'
		);
	}

	/**
	 * Create the container for a line
	 *
	 * @param string $line the line
	 * @param string $class the container class (diff-deletedline or diff-addedline)
	 * @param string $marker the diff marker (+ or -)
	 * @return string
	 */
	protected function createLineWrapper( $line, $class, $marker ) {
		return Html::element( 'td', [ 'class' => 'diff-marker' ], $marker ) .
			Html::openElement( 'td', [ 'class' => $class ] ).
			Html::rawelement( 'div', [], $line ) .
			Html::closeElement( 'td' );
	}
}
