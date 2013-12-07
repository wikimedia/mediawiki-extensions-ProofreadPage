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

class EditProofreadPagePage extends EditPage {

	/**
	 * @var ProofreadPagePage
	 */
	protected $pagePage;

	/**
	 * @var Article $article
	 * @var ProofreadPagePage $pagePage
	 * @throw MWException
	 */
	public function __construct( Article $article, ProofreadPagePage $pagePage ) {
		parent::__construct( $article );

		$this->pagePage = $pagePage;

		if ( !$this->isSupportedContentModel( $this->contentModel ) ) {
			throw new MWException(
				'The content model ' . ContentHandler::getLocalizedName( $this->contentModel ) . ' is not supported'
			);
		}
	}

	/**
	 * @see EditPage::isSectionEditSupported
	 */
	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	/**
	 * @see EditPage::isSupportedContentModel
	 */
	public function isSupportedContentModel( $modelId ) {
		return $modelId === CONTENT_MODEL_PROOFREAD_PAGE;
	}

	/**
	 * Load the content before edit
	 *
	 * @see EditPage::showContentForm
	 */
	protected function getContentObject( $def_content = null ) {
		if ( $this->mTitle->exists() ) {
			return parent::getContentObject( $def_content );
		}

		//preload content
		$index = $this->pagePage->getIndex();
		$body = '';

		//default header and footer
		if ( $index ) {
			$params = array(
				'pagenum' => $index->getDisplayedPageNumber( $this->getTitle() )
			);
			$header = $index->replaceVariablesWithIndexEntries( 'header', $params );
			$footer = $index->replaceVariablesWithIndexEntries( 'footer', $params );
		} else {
			$header = wfMessage( 'proofreadpage_default_header' )->inContentLanguage()->plain();
			$footer = wfMessage( 'proofreadpage_default_footer' )->inContentLanguage()->plain();
		}

		//Extract text layer
		$image = $this->pagePage->getImage();
		$pageNumber = $this->pagePage->getPageNumber();
		if ( $image && $image->exists() ) {
			if ( $pageNumber !== null && $image->isMultipage() ) {
				$text = $image->getHandler()->getPageText( $image, $pageNumber );
			} else {
				$text = $image->getHandler()->getPageText( $image, 1 );
			}
			if ( $text ) {
				$text = preg_replace( "/(\\\\n)/", "\n", $text );
				$body = preg_replace( "/(\\\\\d*)/", '', $text );
			}
		}

		return new ProofreadPageContent(
			new WikitextContent( $header ),
			new WikitextContent( $body ),
			new WikitextContent( $footer ),
			new ProofreadPageLevel()
		);
	}

	/**
	 * @see EditPage::showContentForm
	 */
	protected function showContentForm() {
		$out = $this->mArticle->getContext()->getOutput();

		//custom CSS for preview
		$css = $this->pagePage->getCustomCss();
		if ( $css !== '' ) {
			$out->addInlineStyle( $css );
		}

		$inputAttributes = array();
		if ( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		$content = $this->toEditContent( $this->textbox1 );

		$out->addHTML( $this->pagePage->getPageContainerBegin() );
		$this->showEditArea(
			'wpHeaderTextbox',
			'prp-page-edit-header',
			'proofreadpage_header',
			$content->getHeader()->serialize(),
			$inputAttributes + array( 'rows' => '2' )
		);
		$this->showEditArea(
			'wpTextbox1',
			'prp-page-edit-body',
			'proofreadpage_body',
			$content->getBody()->serialize(),
			$inputAttributes
		);
		$this->showEditArea(
			'wpFooterTextbox',
			'prp-page-edit-footer',
			'proofreadpage_footer',
			$content->getFooter()->serialize(),
			$inputAttributes + array( 'rows' => '2' )
		);
		$out->addHTML( $this->pagePage->getPageContainerEnd() );

		$out->addModules( 'ext.proofreadpage.page.edit' );
	}

	/**
	 * Outputs an edit area to edition
	 *
	 * @param $textareaName string the name of the textarea node (used also as id)
	 * @param $areaClass string the class of the div container
	 * @param $labelMsg string the label of the area
	 * @param $content string the text to edit
	 * @param $textareaAttributes array attributes to add to textarea node
	 */
	protected function showEditArea( $textareaName, $areaClass, $labelMsg, $content, $textareaAttributes ) {
		$out = $this->mArticle->getContext()->getOutput();
		$out->addHTML(
			Html::openElement( 'div', array( 'class' => $areaClass ) ) .
			Html::element( 'label', array( 'for' => $textareaName ), wfMessage( $labelMsg )->text() )
		);
		$this->showTextbox( $content, $textareaName, $textareaAttributes );
		$out->addHTML( Html::closeElement( 'div' ) );
	}

	/**
	 * Sets the checkboxes for the proofreading status of the page.
	 *
	 * @see EditPage::getCheckBoxes
	 */
	function getCheckBoxes( &$tabindex, $checked ) {

		$oldLevel = $this->getCurrentContent()->getLevel();

		$content = $this->toEditContent( $this->textbox1 );
		$currentLevel = $content->getLevel();

		$qualityLevels = array( 0, 2, 1, 3, 4 );
		$html = '';
		$checkboxes = parent::getCheckBoxes( $tabindex, $checked );
		$user = $this->mArticle->getContext()->getUser();

		foreach( $qualityLevels as $level ) {

			$newLevel = new ProofreadPageLevel( $level, $user );
			if( !$oldLevel->isChangeAllowed( $newLevel ) ) {
				continue;
			}

			$msg = 'proofreadpage_quality' . $level . '_category';
			$cls = 'quality' . $level;

			$attributes = array( 'tabindex' => ++$tabindex, 'title' => wfMessage( $msg )->plain() );
			if( $level == $currentLevel->getLevel() ) {
				$attributes[] = 'checked';
			}

			$html .= Html::openElement( 'span', array( 'class' => $cls ) ) .
				Html::input( 'wpQuality', $level, 'radio', $attributes ) .
				Html::closeElement( 'span' );
		}

		$checkboxes['wpr-pageStatus'] = '';
		if ( $user->isAllowed( 'pagequality' ) ) {
			$checkboxes['wpr-pageStatus'] =
				Html::openElement( 'span', array( 'id' => 'wpQuality-container' ) ) .
				$html .
				Html::closeElement( 'span' ) .
				Html::OpenElement( 'label', array( 'for' => 'wpQuality-container' ) ) .
				wfMessage( 'proofreadpage_page_status' )->parse() .
				Html::closeElement( 'label' );
		}

		return $checkboxes;
	}

	/**
	 * @see EditPage::importContentFormData
	 */
	protected function importContentFormData( &$request ) {
		$proofreadingLevel = $request->getInt( 'wpQuality' );
		$oldLevel = $this->getCurrentContent()->getLevel();
		$user = ( $oldLevel->getLevel() === $proofreadingLevel )
			? $oldLevel->getUser()
			: $this->mArticle->getContext()->getUser();
		if ( $oldLevel->getUser() === null ) {
			$user = $this->mArticle->getContext()->getUser();
		}

		$content = new ProofreadPageContent(
			new WikitextContent( $this->safeUnicodeInput( $request, 'wpHeaderTextbox' ) ),
			new WikitextContent( $this->safeUnicodeInput( $request, 'wpTextbox1') ),
			new WikitextContent( $this->safeUnicodeInput( $request, 'wpFooterTextbox' ) ),
			new ProofreadPageLevel( $proofreadingLevel, $user )
		);

		return $content->serialize();
	}

	/**
	 * Check the validity of the page
	 *
	 * @see EditPage::internalAttemptSave
	 */
	public function internalAttemptSave( &$result, $bot = false ) {
		$error = '';
		$oldContent = $this->getCurrentContent();
		$newContent = $this->toEditContent( $this->textbox1 );

		if ( !$newContent->isValid() ) {
			$error = 'badpage';
		} elseif ( !$oldContent->getLevel()->isChangeAllowed( $newContent->getLevel() ) ) {
			$error = 'notallowed';
		}

		if ( $error !== '' ) {
			$this->mArticle->getContext()->getOutput()->showErrorPage( 'proofreadpage_' . $error, 'proofreadpage_' . $error . 'text' );
			$status = Status::newFatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}

		return parent::internalAttemptSave( $result, $bot );
	}
}
