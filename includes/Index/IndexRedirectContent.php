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
 * @license GPL-2.0-or-later
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
		parent::__construct(
			'#REDIRECT [[' . $redirectionTarget->getFullText() . ']]',
			CONTENT_MODEL_PROOFREAD_INDEX
		);
	}

	/**
	 * @inheritDoc
	 */
	public function isValid() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getSize() {
		return strlen( $this->redirectionTarget->getFullText() );
	}

	/**
	 * @inheritDoc
	 */
	public function equals( Content $that = null ) {
		return $that instanceof IndexRedirectContent &&
			$this->redirectionTarget->equals( $that->getRedirectTarget() );
	}

	/**
	 * @inheritDoc
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * @inheritDoc
	 * @return Title
	 */
	public function getRedirectTarget() {
		return $this->redirectionTarget;
	}

	/**
	 * @inheritDoc
	 */
	public function updateRedirect( Title $target ) {
		return new self( $target );
	}

	/**
	 * @inheritDoc
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		$output->addLink( $this->getRedirectTarget() );
		if ( $generateHtml ) {
			$output->setText( Article::getRedirectHeaderHtml(
				$title->getPageLanguage(), $this->getRedirectChain()
			) );
			$output->addModuleStyles( 'mediawiki.action.view.redirectPage' );
		}
	}
}
