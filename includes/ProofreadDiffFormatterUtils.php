<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup ProofreadPage
 */

/**
 * Utility functions to format diffs
 */
class ProofreadDiffFormatterUtils {

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