<?php

namespace ProofreadPage\Page;

use Content;
use DifferenceEngine;
use Html;
use MWException;
use ProofreadPage\DiffFormatterUtils;
use TextContent;

/**
 * DifferenceEngine for Page: pages
 */
class PageDifferenceEngine extends DifferenceEngine {

	/**
	 * @var DiffFormatterUtils
	 */
	protected $diffFormatterUtils;

	/**
	 * @see DifferenceEngine::__construct
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0, $refreshCache = false, $unhide = false ) {
		parent::__construct( $context, $old, $new, $rcid, $refreshCache, $unhide );

		$this->diffFormatterUtils = new DiffFormatterUtils();
	}

	/**
	 * @see DifferenceEngine::generateContentDiffBody
	 */
	public function generateContentDiffBody( Content $old, Content $new ) {
		if ( !( $old instanceof PageContent ) || !( $new instanceof PageContent ) ) {
			throw new MWException( "PageDifferenceEngine works only for PageContent." );
		}

		return $this->createLevelDiffs( $old->getLevel(), $new->getLevel() ) . $this->createTextDiffOutput( $old->getHeader(), $new->getHeader(), 'proofreadpage_header' ) . $this->createTextDiffOutput( $old->getBody(), $new->getBody(), 'proofreadpage_body' ) . $this->createTextDiffOutput( $old->getFooter(), $new->getFooter(), 'proofreadpage_footer' );
	}

	/**
	 * Create the diffs for a PageLevel
	 *
	 * @param $old PageLevel
	 * @param $new PageLevel
	 * @return string
	 */
	protected function createLevelDiffs( PageLevel $old, PageLevel $new ) {
		if ( $old->equals( $new ) ) {
			return '';
		}

		return $this->diffFormatterUtils->createHeader( $this->msg( 'proofreadpage_page_status' )->text() ) . Html::openElement( 'tr' ) . $this->diffFormatterUtils->createDeletedLine( $this->msg( 'proofreadpage_quality' . $old->getLevel() . '_category' )->plain(), 'diff-deletedline', '-' ) . $this->diffFormatterUtils->createAddedLine( $this->msg( 'proofreadpage_quality' . $new->getLevel() . '_category' )->plain(), 'diff-addedline', '+' ) . Html::closeElement( 'tr' );
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