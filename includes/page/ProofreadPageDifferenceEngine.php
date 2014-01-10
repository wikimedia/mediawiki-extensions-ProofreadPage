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
 * DifferenceEngine for Page: pages
 */
class ProofreadPageDifferenceEngine extends DifferenceEngine {

	/**
	 * @var ProofreadDiffFormatterUtils
	 */
	protected $diffFormatterUtils;

	/**
	 * @see DifferenceEngine::__construct
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0, $refreshCache = false, $unhide = false ) {
		parent::__construct( $context, $old, $new, $rcid, $refreshCache, $unhide );

		$this->diffFormatterUtils = new ProofreadDiffFormatterUtils();
	}

	/**
	 * @see DifferenceEngine::generateContentDiffBody
	 */
	public function generateContentDiffBody( Content $old, Content $new ) {
		if ( !( $old instanceof ProofreadPageContent ) || !( $new instanceof ProofreadPageContent ) ) {
			throw new MWException( "ProofreadPageDifferenceEngine works only for ProofreadPageContent." );
		}

		return $this->createLevelDiffs( $old->getLevel(), $new->getLevel() ) .
			$this->createTextDiffOutput( $old->getHeader(), $new->getHeader(), 'proofreadpage_header' ) .
			$this->createTextDiffOutput( $old->getBody(), $new->getBody(), 'proofreadpage_body' ) .
			$this->createTextDiffOutput( $old->getFooter(), $new->getFooter(), 'proofreadpage_footer' );
	}

	/**
	 * Create the diffs for a ProofreadPageLevel
	 *
	 * @param $old ProofreadPageLevel
	 * @param $new ProofreadPageLevel
	 * @return string
	 */
	protected function createLevelDiffs( ProofreadPageLevel $old, ProofreadPageLevel $new ) {
		if ( $old->equals( $new ) ) {
			return '';
		}

		return $this->diffFormatterUtils->createHeader( $this->msg( 'proofreadpage_page_status' )->text() ) .
			Html::openElement( 'tr' ) .
			$this->diffFormatterUtils->createDeletedLine(
				$this->msg( 'proofreadpage_quality' . $old->getLevel() . '_category' )->plain(),
				'diff-deletedline',
				'-'
			) .
			$this->diffFormatterUtils->createAddedLine(
				$this->msg( 'proofreadpage_quality' . $new->getLevel() . '_category' )->plain(),
				'diff-addedline',
				'+'
			) .
			Html::closeElement( 'tr' );
	}

	/**
	 * Create the diffs for a textual content
	 *
	 * @param $old TextContent $old
	 * @param $new TextContent $new
	 * @param $headerMsg string the message to use for header
	 * @return string
	 */
	protected function createTextDiffOutput( TextContent $old, TextContent $new, $headerMsg ) {
		$diff = parent::generateContentDiffBody( $old, $new );
		if ( $diff === '' ) {
			return '';
		}

		return $this->diffFormatterUtils->createHeader( $this->msg( $headerMsg )->text() ) . $diff;
	}
}