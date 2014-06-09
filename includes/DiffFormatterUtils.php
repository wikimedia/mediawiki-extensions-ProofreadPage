<?php

namespace ProofreadPage;

use Html;

/**
 * @licence GNU GPL v2+
 *
 * Utility functions to format diffs
 */
class DiffFormatterUtils {

	/**
	 * Create an header in the two columns
	 *
	 * @param $text string the header text
	 * @return string
	 */
	public function createHeader( $text ) {
		return Html::openElement( 'tr' ) .
			Html::element( 'td', array( 'colspan' => '2', 'class' => 'diff-lineno' ), $text ) .
			Html::element( 'td', array( 'colspan' => '2', 'class' => 'diff-lineno' ), $text ) .
			Html::closeElement( 'tr' );
	}

	/**
	 * Output an added line
	 *
	 * @param $content string the content of the line
	 * @return string
	 */
	public function createAddedLine( $content ) {
		return $this->createLineWrapper(
			Html::element( 'ins',  array( 'class' => 'diffchange diffchange-inline' ), $content ),
			'diff-addedline',
			'+'
		);
	}

	/**
	 * Output a deleted line
	 *
	 * @param $content string the content of the line
	 * @return string
	 */
	public function createDeletedLine( $content ) {
		return $this->createLineWrapper(
			Html::element( 'del',  array( 'class' => 'diffchange diffchange-inline' ), $content ),
			'diff-deletedline',
			'-'
		);
	}

	/**
	 * Create the container for a line
	 *
	 * @param $line string the line
	 * @param $class string the container class (diff-deletedline or diff-addedline)
	 * @param $marker string the diff marker (+ or -)
	 * @return string
	 */
	protected function createLineWrapper( $line, $class, $marker ) {
		return Html::element( 'td', array( 'class' => 'diff-marker' ), $marker ) .
			Html::openElement( 'td', array( 'class' => $class ) ).
			Html::rawelement( 'div', array(), $line ) .
			Html::closeElement( 'td' );
	}
}