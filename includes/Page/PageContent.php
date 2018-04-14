<?php

namespace ProofreadPage\Page;

use Content;
use Html;
use MagicWord;
use ParserOptions;
use Revision;
use Status;
use TextContent;
use Title;
use User;
use WikiPage;
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
	 * Constructor
	 *
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
	public function getNativeData() {
		return $this->serialize();
	}

	/**
	 * @inheritDoc
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return $this->body->getTextForSummary( $maxlength );
	}

	/**
	 * @inheritDoc
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return new self(
			$this->header->preSaveTransform( $title, $user, $popts ),
			$this->body->preSaveTransform( $title, $user, $popts ),
			$this->footer->preSaveTransform( $title, $user, $popts ),
			$this->level
		);
	}

	/**
	 * @inheritDoc
	 */
	public function preloadTransform( Title $title, ParserOptions $popts, $params = [] ) {
		return new self(
			$this->header->preloadTransform( $title, $popts, $params ),
			$this->body->preloadTransform( $title, $popts, $params ),
			$this->footer->preloadTransform( $title, $popts, $params ),
			$this->level
		);
	}

	/**
	 * @inheritDoc
	 */
	public function prepareSave( WikiPage $page, $flags, $parentRevId, User $user ) {
		if ( !$this->isValid() ) {
			return Status::newFatal( 'invalid-content-data' );
		}

		$oldContent = $this->getContentForRevId( $parentRevId );
		if ( $oldContent->getModel() !== CONTENT_MODEL_PROOFREAD_PAGE ) {
			// Let's convert it to Page: page content
			$oldContent = $oldContent->convert( CONTENT_MODEL_PROOFREAD_PAGE );
		}
		if ( !( $oldContent instanceof self ) ) {
			return Status::newFatal( 'invalid-content-data' );
		}
		if ( !$oldContent->getLevel()->isChangeAllowed( $this->getLevel() ) ) {
			return Status::newFatal( 'proofreadpage_notallowedtext' );
		}

		return Status::newGood();
	}

	private function getContentForRevId( $revId ) {
		if ( $revId !== -1 ) {
			$revision = Revision::newFromId( $revId );
			if ( $revision !== null ) {
				$content = $revision->getContent();
				if ( $content !== null ) {
					return $content;
				}
			}
		}
		return $this->getContentHandler()->makeEmptyContent();
	}

	/**
	 * @inheritDoc
	 */
	public function getRedirectTarget() {
		return $this->body->getRedirectTarget();
	}

	/**
	 * @inheritDoc
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

	/**
	 * @inheritDoc
	 */
	public function getParserOutput(
		Title $title, $revId = null, ParserOptions $options = null, $generateHtml = true
	) {
		if ( $this->isRedirect() ) {
			return $this->body->getParserOutput( $title, $revId, $options, $generateHtml );
		}
		if ( $options === null ) {
			$options = $this->getContentHandler()->makeParserOptions( 'canonical' );
		}

		// create content
		$wikitextContent = new WikitextContent(
			$this->header->getNativeData() . "\n\n" . $this->body->getNativeData() .
				$this->footer->getNativeData()
		);
		$parserOutput = $wikitextContent->getParserOutput( $title, $revId, $options, $generateHtml );
		$parserOutput->addCategory(
			Title::makeTitleSafe(
				NS_CATEGORY,
				$this->level->getLevelCategoryName()
			)->getDBkey(),
			$title->getText()
		);
		$parserOutput->setProperty( 'proofread_page_quality_level', $this->level->getLevel() );

		// html container
		$html = Html::openElement( 'div',
			[ 'class' => 'prp-page-qualityheader quality' . $this->level->getLevel() ] ) .
			wfMessage( 'proofreadpage_quality' . $this->level->getLevel() . '_message' )
				->title( $title )->inContentLanguage()->parse() .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', [ 'class' => 'pagetext' ] ) .
			$parserOutput->getText( [ 'enableSectionEditLinks' => false ] ) .
			Html::closeElement( 'div' );
		$parserOutput->setText( $html );

		// add modules
		$parserOutput->addModuleStyles( 'ext.proofreadpage.base' );

		// add scan image to dependencies
		$parserOutput->addImage( strtok( $title->getDBkey(), '/' ) );

		return $parserOutput;
	}
}
