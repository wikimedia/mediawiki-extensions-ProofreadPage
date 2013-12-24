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
  * Content of a Page: page
  */
class ProofreadPageContent extends TextContent {

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
	 * @var ProofreadPageLevel proofreading level of the page
	 */
	protected $level;

	/**
	 * Constructor
	 *
	 * @param $header WikitextContent
	 * @param $body WikitextContent
	 * @param $footer WikitextContent
	 * @param $level ProofreadPageLevel
	 */
	public function __construct( WikitextContent $header, WikitextContent $body, WikitextContent $footer, ProofreadPageLevel $level ) {
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
	public function getBody(){
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
	 * @return ProofreadPageLevel
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @see Content:isValid
	 */
	public function isValid() {
		return $this->header->isValid() &&
			$this->body->isValid() &&
			$this->footer->isValid() &&
			$this->level->isValid();
	}

	/**
	 * @see Content:isEmpty
	 */
	public function isEmpty() {
		return $this->body->isEmpty();
	}

	/**
	 * @see Content::equals
	 */
	public function equals( Content $that = null ) {
		if ( !( $that instanceof ProofreadPageContent ) || $that->getModel() !== $this->getModel() ) {
			return false;
		}

		return
			$this->header->equals( $that->getHeader() ) &&
			$this->body->equals( $that->getBody() ) &&
			$this->footer->equals( $that->getFooter() ) &&
			$this->level->equals( $that->getLevel() );
	}

	/**
	 * @see Content::getWikitextForTransclusion
	 */
	public function getWikitextForTransclusion() {
		return $this->body->getWikitextForTransclusion();
	}

	/**
	 * @see Content::getNativeData
	 */
	public function getNativeData() {
		return $this->serialize();
	}

	/**
	 * @see Content::getTextForSummary
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return $this->body->getTextForSummary( $maxlength );
	}

	/**
	 * @see Content::preSaveTransform
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
	 * @see Content::preloadTransform
	 */
	public function preloadTransform( Title $title, ParserOptions $popts ) {
		return new self(
			$this->header->preloadTransform( $title, $popts ),
			$this->body->preloadTransform( $title, $popts ),
			$this->footer->preloadTransform( $title, $popts ),
			$this->level
		);
	}

	/**
	 * @see Content::getRedirectTarget
	 */
	public function getRedirectTarget() {
		return $this->body->getRedirectTarget();
	}

	/**
	 * @see Content::updateRedirect
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
	 * @see Content::getSize
	 */
	public function getSize() {
		return $this->header->getSize() +
			$this->body->getSize() +
			$this->footer->getSize();
	}

	/**
	 * @see Content::isCountable
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		return $this->header->isCountable( $hasLinks, $title ) ||
			$this->body->isCountable( $hasLinks, $title ) ||
			$this->footer->isCountable( $hasLinks, $title );
	}

	/**
	 * @see Content::matchMagicWord
	 */
	public function matchMagicWord( MagicWord $word ) {
		return $this->header->matchMagicWord( $word ) ||
			$this->body->matchMagicWord( $word ) ||
			$this->footer->matchMagicWord( $word );
	}

	/**
	 * @see Content::getParserOutput
	 */
	public function getParserOutput( Title $title, $revId = null, ParserOptions $options = null, $generateHtml = true ) {
		if( $this->isRedirect() ) {
			return $this->body->getParserOutput( $title, $revId, $options, $generateHtml );
		}
		if ( $options === null ) {
			$options = $this->getContentHandler()->makeParserOptions( 'canonical' );
		}

		//create content
		$wikitextContent = new WikitextContent(
			$this->header->getNativeData() . "\n\n" . $this->body->getNativeData() . $this->footer->getNativeData()
		);
		$parserOutput = $wikitextContent->getParserOutput( $title, $revId, $options, $generateHtml );
		$parserOutput->addCategory(
			Title::makeTitleSafe(
				NS_CATEGORY,
				$this->level->getLevelCategoryName()
			)->getDBkey(),
			$title->getText()
		);


		//html container
		$html = Html::openElement( 'div', array( 'class' => 'prp-page-qualityheader quality' . $this->level->getLevel() ) ) .
			wfMessage( 'proofreadpage_quality' . $this->level->getLevel() . '_message' )->inContentLanguage()->parse() .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'class' => 'pagetext' ) ) .
			$parserOutput->getText() .
			Html::closeElement( 'div' );
		$parserOutput->setText( $html );

		//add modules
		$parserOutput->addModuleStyles( 'ext.proofreadpage.base' );

		return $parserOutput;
	}
}
