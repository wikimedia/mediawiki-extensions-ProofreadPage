<?php

namespace ProofreadPage\Page;

use Article;
use ContentHandler;
use EditPage;
use Html;
use MWException;
use ProofreadPage\Context;
use ProofreadPagePage;
use Status;

/**
 * @licence GNU GPL v2+
 */
class EditPagePage extends EditPage {

	/**
	 * @var ProofreadPagePage
	 */
	protected $pagePage;

	/**
	 * @var PageContentBuilder
	 */
	protected $pageContentBuilder;

	/**
	 * @param Article $article
	 * @param ProofreadPagePage $pagePage
	 * @param Context $context
	 * @throws MWException
	 */
	public function __construct( Article $article, ProofreadPagePage $pagePage, Context $context ) {
		parent::__construct( $article );

		$this->pagePage = $pagePage;
		$this->pageContentBuilder = new PageContentBuilder( $this->mArticle->getContext(), $context );

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
	protected function getContentObject( $defContent = null ) {
		if ( !$this->mTitle->exists() ) {
			return $this->pageContentBuilder->buildDefaultContentForPage( $this->pagePage );
		}
		return parent::getContentObject( $defContent );
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
			$inputAttributes + array( 'rows' => '2', 'tabindex' => '1' )
		);
		$this->showEditArea(
			'wpTextbox1',
			'prp-page-edit-body',
			'proofreadpage_body',
			$content->getBody()->serialize(),
			$inputAttributes + array( 'tabindex' => '1' )
		);
		$this->showEditArea(
			'wpFooterTextbox',
			'prp-page-edit-footer',
			'proofreadpage_footer',
			$content->getFooter()->serialize(),
			$inputAttributes + array( 'rows' => '2', 'tabindex' => '1' )
		);
		//the 3 textarea tabindex are set to 1 because summary tabindex is 1 too
		$out->addHTML( $this->pagePage->getPageContainerEnd() );

		$out->addModules( 'ext.proofreadpage.page.edit' );
	}

	/**
	 * Outputs an edit area to edition
	 *
	 * @param string $textareaName the name of the textarea node (used also as id)
	 * @param string $areaClass the class of the div container
	 * @param string $labelMsg the label of the area
	 * @param string $content the text to edit
	 * @param string[] $textareaAttributes attributes to add to textarea node
	 */
	protected function showEditArea( $textareaName, $areaClass, $labelMsg, $content, array $textareaAttributes ) {
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

		foreach ( $qualityLevels as $level ) {

			$newLevel = new PageLevel( $level, $user );
			if ( !$oldLevel->isChangeAllowed( $newLevel ) ) {
				continue;
			}

			$msg = 'proofreadpage_quality' . $level . '_category';
			$cls = 'quality' . $level;

			$attributes = array( 'tabindex' => ++$tabindex, 'title' => wfMessage( $msg )->plain() );
			if ( $level == $currentLevel->getLevel() ) {
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
		$currentContent = $this->getCurrentContent();

		return $this->pageContentBuilder->buildContentFromInput(
			$this->safeUnicodeInput( $request, 'wpHeaderTextbox' ),
			$this->safeUnicodeInput( $request, 'wpTextbox1' ),
			$this->safeUnicodeInput( $request, 'wpFooterTextbox' ),
			$request->getInt( 'wpQuality', $currentContent->getLevel()->getLevel() ),
			$currentContent
		)->serialize();
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
