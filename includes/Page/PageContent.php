<?php

namespace ProofreadPage\Page;

use Content;
use MagicWord;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use TextContent;
use Title;
use WikitextContent;

/**
 * @license GPL-2.0-or-later
 *
 * Content of a Page: page
 */
class PageContent extends TextContent {

	/**
	 * @var WikitextContent header of the page
	 */
	protected $header;

	/**
	 * @var WikitextContent body of the page
	 */
	protected $body;

	/**
	 * @var WikitextContent footer of the page
	 */
	protected $footer;

	/**
	 * @var PageLevel proofreading level of the page
	 */
	protected $level;

	/**
	 * @param WikitextContent $header
	 * @param WikitextContent $body
	 * @param WikitextContent $footer
	 * @param PageLevel $level
	 */
	public function __construct(
		WikitextContent $header, WikitextContent $body, WikitextContent $footer, PageLevel $level
	) {
		$this->header = $header;
		$this->body = $body;
		$this->footer = $footer;
		$this->level = $level;

		parent::__construct( '', CONTENT_MODEL_PROOFREAD_PAGE );
	}

	/**
	 * returns the header of the page
	 * @return WikitextContent
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * returns the body of the page
	 * @return WikitextContent
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * returns the footer of the page
	 * @return WikitextContent
	 */
	public function getFooter() {
		return $this->footer;
	}

	/**
	 * returns the proofreading level of the page.
	 * @return PageLevel
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @inheritDoc
	 */
	public function isValid() {
		return $this->header->isValid() &&
			$this->body->isValid() &&
			$this->footer->isValid() &&
			$this->level->isValid();
	}

	/**
	 * @inheritDoc
	 */
	public function isEmpty() {
		return $this->body->isEmpty();
	}

	/**
	 * @inheritDoc
	 */
	public function equals( Content $that = null ) {
		if ( !( $that instanceof PageContent ) || $that->getModel() !== $this->getModel() ) {
			return false;
		}

		return $this->header->equals( $that->getHeader() ) &&
			$this->body->equals( $that->getBody() ) &&
			$this->footer->equals( $that->getFooter() ) &&
			$this->level->equals( $that->getLevel() );
	}

	/**
	 * @inheritDoc
	 */
	public function getWikitextForTransclusion() {
		return $this->body->getWikitextForTransclusion();
	}

	/**
	 * @inheritDoc
	 */
	public function getText() {
		return $this->serialize();
	}

	/**
	 * @inheritDoc
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return $this->body->getTextForSummary( $maxlength );
	}

	/**
	 * @param int $revId
	 * @return Content
	 */
	public static function getContentForRevId( $revId ) {
		if ( $revId !== -1 ) {
			$revision = MediaWikiServices::getInstance()->getRevisionStore()->getRevisionById( $revId );
			if ( $revision !== null ) {
				$content = $revision->getContent( SlotRecord::MAIN );
				if ( $content !== null ) {
					return $content;
				}
			}
		}
		$contentHandler = \ContentHandler::getForModelID( CONTENT_MODEL_PROOFREAD_PAGE );
		return $contentHandler->makeEmptyContent();
	}

	/**
	 * @inheritDoc
	 */
	public function getRedirectTarget() {
		return $this->body->getRedirectTarget();
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchArgument
	 */
	public function updateRedirect( Title $target ) {
		if ( !$this->isRedirect() ) {
			return $this;
		}

		return new self(
			$this->header,
			$this->body->updateRedirect( $target ),
			$this->footer,
			$this->level
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getSize() {
		return $this->header->getSize() +
			$this->body->getSize() +
			$this->footer->getSize();
	}

	/**
	 * @inheritDoc
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		return $this->header->isCountable( $hasLinks, $title ) ||
			$this->body->isCountable( $hasLinks, $title ) ||
			$this->footer->isCountable( $hasLinks, $title );
	}

	/**
	 * @inheritDoc
	 */
	public function matchMagicWord( MagicWord $word ) {
		return $this->header->matchMagicWord( $word ) ||
			$this->body->matchMagicWord( $word ) ||
			$this->footer->matchMagicWord( $word );
	}
}
