<?php

namespace ProofreadPage\Page;

use Content;
use Html;
use IContextSource;
use ProofreadPage\DiffFormatterUtils;
use SlotDiffRenderer;

/**
 * SlotDiffRenderer for Page: pages content model
 */
class PageSlotDiffRenderer extends SlotDiffRenderer {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @var DiffFormatterUtils
	 */
	private $diffFormatterUtils;

	/**
	 * @param IContextSource $context
	 */
	public function __construct( IContextSource $context ) {
		$this->context = $context;
		$this->diffFormatterUtils = new DiffFormatterUtils();
	}

	/**
	 * @inheritDoc
	 */
	public function getDiff( Content $oldContent = null, Content $newContent = null ) {
		$this->normalizeContents( $oldContent, $newContent, PageContent::class );

		return $this->createLevelDiffs(
				$oldContent->getLevel(), $newContent->getLevel()
			) . $this->createTextDiffOutput(
				$oldContent->getHeader(), $newContent->getHeader(), 'proofreadpage_header'
			) . $this->createTextDiffOutput(
				$oldContent->getBody(), $newContent->getBody(), 'proofreadpage_body'
			) . $this->createTextDiffOutput(
				$oldContent->getFooter(), $newContent->getFooter(), 'proofreadpage_footer'
			);
	}

	/**
	 * Create the diffs for a PageLevel
	 *
	 * @param PageLevel $old
	 * @param PageLevel $new
	 * @return string
	 */
	private function createLevelDiffs( PageLevel $old, PageLevel $new ) {
		if ( $old->equals( $new ) ) {
			return '';
		}

		return $this->diffFormatterUtils->createHeader(
				$this->context->msg( 'proofreadpage_page_status' )->parse()
			) . Html::openElement( 'tr' ) . $this->diffFormatterUtils->createDeletedLine(
				$this->context->msg( 'proofreadpage_quality' . $old->getLevel() . '_category' )->plain()
			) . $this->diffFormatterUtils->createAddedLine(
				$this->context->msg( 'proofreadpage_quality' . $new->getLevel() . '_category' )->plain()
			) . Html::closeElement( 'tr' );
	}

	/**
	 * Create the diffs for a textual content
	 *
	 * @param Content $oldContent
	 * @param Content $newContent
	 * @param string $headerMsg the message to use for header
	 * @return string
	 */
	private function createTextDiffOutput( Content $oldContent, Content $newContent, $headerMsg ) {
		$diffRenderer = $newContent->getContentHandler()->getSlotDiffRenderer( $this->context );
		$diff = $diffRenderer->getDiff( $oldContent, $newContent );
		if ( $diff === '' ) {
			return '';
		}

		return $this->diffFormatterUtils->createHeader( $this->context->msg( $headerMsg )->escaped() ) .
			$diff;
	}
}
