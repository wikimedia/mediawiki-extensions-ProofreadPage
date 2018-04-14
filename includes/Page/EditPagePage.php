<?php

namespace ProofreadPage\Page;

use Article;
use EditPage;
use Html;
use MWException;
use OOUI;
use ProofreadPage\Context;
use User;

/**
 * @license GPL-2.0-or-later
 */
class EditPagePage extends EditPage {

	/**
	 * @var PageContentBuilder
	 */
	private $pageContentBuilder;

	/**
	 * @var PageDisplayHandler
	 */
	private $pageDisplayHandler;

	/**
	 * @param Article $article
	 * @param Context $context
	 * @throws MWException
	 */
	public function __construct( Article $article, Context $context ) {
		parent::__construct( $article );

		$this->pageContentBuilder = new PageContentBuilder( $this->context, $context );
		$this->pageDisplayHandler = new PageDisplayHandler( $context );
	}

	/**
	 * @inheritDoc
	 */
	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	/**
	 * @inheritDoc
	 */
	public function isSupportedContentModel( $modelId ) {
		return $modelId === CONTENT_MODEL_PROOFREAD_PAGE;
	}

	/**
	 * Load the content before edit
	 *
	 * @inheritDoc
	 */
	protected function getContentObject( $defContent = null ) {
		if ( !$this->mTitle->exists() ) {
			return $this->pageContentBuilder->buildDefaultContentForPageTitle( $this->getTitle() );
		}
		return parent::getContentObject( $defContent );
	}

	/**
	 * @inheritDoc
	 */
	protected function showContentForm() {
		$out = $this->context->getOutput();

		// custom CSS for preview
		$css = $this->pageDisplayHandler->getCustomCss( $this->getTitle() );
		if ( $css !== '' ) {
			$out->addInlineStyle( $css );
		}

		$inputAttributes = [];
		if ( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		/** @var PageContent $content */
		$content = $this->toEditContent( $this->textbox1 );

		$out->addHTML( $this->pageDisplayHandler->buildPageContainerBegin() );
		$this->showEditArea(
			'wpHeaderTextbox',
			'prp-page-edit-header',
			'proofreadpage_header',
			$content->getHeader()->serialize(),
			$inputAttributes + [ 'rows' => '2', 'tabindex' => '1' ]
		);
		$this->showEditArea(
			'wpTextbox1',
			'prp-page-edit-body',
			'proofreadpage_body',
			$content->getBody()->serialize(),
			$inputAttributes + [ 'tabindex' => '1' ]
		);
		$this->showEditArea(
			'wpFooterTextbox',
			'prp-page-edit-footer',
			'proofreadpage_footer',
			$content->getFooter()->serialize(),
			$inputAttributes + [ 'rows' => '2', 'tabindex' => '1' ]
		);
		// the 3 textarea tabindex are set to 1 because summary tabindex is 1 too
		$out->addHTML( $this->pageDisplayHandler->buildPageContainerEnd( $this->getTitle() ) );

		$out->addModules( 'ext.proofreadpage.page.edit' );
		$out->addModuleStyles( [ 'ext.proofreadpage.base', 'ext.proofreadpage.page' ] );
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
	protected function showEditArea(
		$textareaName, $areaClass, $labelMsg, $content, array $textareaAttributes
	) {
		$out = $this->context->getOutput();
		$out->addHTML(
			Html::openElement( 'div', [ 'class' => $areaClass ] ) .
			Html::element( 'label', [ 'for' => $textareaName ],
				$this->context->msg( $labelMsg )->text() )
		);
		$this->showTextbox( $content, $textareaName, $textareaAttributes );
		$out->addHTML( Html::closeElement( 'div' ) );
	}

	/**
	 * Sets the checkboxes for the proofreading status of the page.
	 *
	 * @inheritDoc
	 */
	public function getCheckboxesWidget( &$tabindex, $checked ) {
		$checkboxes = parent::getCheckboxesWidget( $tabindex, $checked );
		$user = $this->context->getUser();

		if ( $user->isAllowed( 'pagequality' ) ) {
			$checkboxes['wpr-pageStatus'] = $this->buildQualityEditWidget( $user, $tabindex );
		}

		return $checkboxes;
	}

	private function buildQualityEditWidget( User $user, &$tabindex ) {
		$oldLevel = $this->getCurrentContent()->getLevel();
		$content = $this->toEditContent( $this->textbox1 );
		$currentLevel = $content->getLevel();

		$html = '';
		for ( $level = 0; $level <= 4; $level++ ) {
			$newLevel = new PageLevel( $level, $user );
			if ( !$oldLevel->isChangeAllowed( $newLevel ) ) {
				continue;
			}

			$msg = 'proofreadpage_quality' . $level . '_category';

			$html .= new OOUI\RadioInputWidget( [
				'name' => 'wpQuality',
				'classes' => [ 'prp-quality-radio quality' . $level ],
				'value' => $level,
				'tabIndex' => ++$tabindex,
				'title' => $this->context->msg( $msg )->plain(),
				'selected' => $level === $currentLevel->getLevel(),
			] );
		}

		$content =
			Html::openElement( 'span', [ 'id' => 'wpQuality-container' ] ) .
			$html .
			Html::closeElement( 'span' ) .
			Html::OpenElement( 'label', [ 'for' => 'wpQuality-container' ] ) .
			$this->context->msg( 'proofreadpage_page_status' )
				->title( $this->getTitle() )->parse() .
			Html::closeElement( 'label' );
		return new OOUI\Widget(
			[
				'classes' => [ 'prp-quality-widget' ],
				'content' => new OOUI\HtmlSnippet( $content )
			]
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function importContentFormData( &$request ) {
		/** @var PageContent $currentContent */
		$currentContent = $this->getCurrentContent();

		return $this->pageContentBuilder->buildContentFromInput(
			$request->getText( 'wpHeaderTextbox' ),
			$request->getText( 'wpTextbox1' ),
			$request->getText( 'wpFooterTextbox' ),
			$request->getInt( 'wpQuality', $currentContent->getLevel()->getLevel() ),
			$currentContent
		)->serialize();
	}
}
