<?php

namespace ProofreadPage\Index;

use Article;
use Content;
use MWException;
use ParserOptions;
use ParserOutput;
use TextContent;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * A redirection in an Index: page
 */
class IndexRedirectContent extends TextContent {

	/**
	 * @var Title
	 */
	private $redirectionTarget;

	/**
	 * @param Title $redirectionTarget
	 * @throws MWException
	 */
	public function __construct( Title $redirectionTarget ) {
		if ( !$redirectionTarget->isValidRedirectTarget() ) {
			throw new MWException( $redirectionTarget . ' should be a valid redirection target' );
		}
		$this->redirectionTarget = $redirectionTarget;
		parent::__construct( '#REDIRECT [[' . $redirectionTarget->getFullText() . ']]', CONTENT_MODEL_PROOFREAD_INDEX );
	}

	/**
	 * @see Content::isValid
	 */
	public function isValid() {
		return true;
	}

	/**
	 * @see Content::getSize
	 */
	public function getSize() {
		return strlen( $this->redirectionTarget->getFullText() );
	}

	/**
	 * @see Content::equals
	 */
	public function equals( Content $that = null ) {
		return $that instanceof IndexRedirectContent && $this->redirectionTarget->equals( $that->getRedirectTarget() );
	}

	/**
	 * @see Content::getTextForSummary
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * @see Content::getRedirectTarget
	 * @return Title
	 */
	public function getRedirectTarget() {
		return $this->redirectionTarget;
	}

	/**
	 * @see Content::updateRedirect
	 */
	public function updateRedirect( Title $target ) {
		return new self( $target );
	}

	/**
	 * @see AbstractContent::fillParserOutput
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		$output->addLink( $this->getRedirectTarget() );
		if ( $generateHtml ) {
			$output->setText( Article::getRedirectHeaderHtml( $title->getPageLanguage(), $this->getRedirectChain() ) );
			$output->addModuleStyles( 'mediawiki.action.view.redirectPage' );
		}
	}
}
