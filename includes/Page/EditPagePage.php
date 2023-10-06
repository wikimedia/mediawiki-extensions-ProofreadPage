<?php

namespace ProofreadPage\Page;

use Article;
use Html;
use MediaWiki\EditPage\EditPage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserOptionsLookup;
use OOUI\FieldLayout;
use OOUI\HtmlSnippet;
use ProofreadPage\Context;
use ProofreadPage\EditInSequence;
use ProofreadPage\OOUI\PageQualityInputWidget;
use ReadOnlyMode;

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
	 * @var PermissionManager
	 */
	private $permissionManager;

	/**
	 * @var UserOptionsLookup
	 */
	private $userOptionsLookup;

	/**
	 * @var ReadOnlyMode
	 */
	private $readOnlyMode;

	/**
	 * @param Article $article
	 * @param Context $context
	 */
	public function __construct( Article $article, Context $context ) {
		parent::__construct( $article );

		$this->pageContentBuilder = new PageContentBuilder( $this->context, $context );
		$this->pageDisplayHandler = new PageDisplayHandler( $context );
		$this->permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		$this->userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		$this->readOnlyMode = MediaWikiServices::getInstance()->getReadOnlyMode();
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
		if ( $this->readOnlyMode->isReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		/** @var PageContent $content */
		$content = $this->toEditContent( $this->textbox1 );
		'@phan-var PageContent $content';

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
		if ( $this->userOptionsLookup->getOption( $this->context->getUser(), 'usebetatoolbar' ) ) {
			$out->addModules( 'ext.wikiEditor' );
		}
		$out->addModuleStyles( [ 'ext.proofreadpage.page.edit.styles' ] );

		$jsVars = $this->pageDisplayHandler->getPageJsConfigVars( $this->getTitle(), $content );
		$out->addJsConfigVars( $jsVars );

		$this->addEditInSequenceModule();
	}

	public function addEditInSequenceModule() {
		if ( EditInSequence::shouldLoadEditInSequence( $this->context ) ) {
			$this->context->getOutput()->addModules( 'ext.proofreadpage.page.editinsequence' );
		}
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
		$label = Html::element(
			'label',
			[ 'for' => $textareaName, 'class' => 'prp-page-edit-area-label' ],
			$this->context->msg( $labelMsg )->text()
		);
		$out->addHTML( Html::openElement( 'div', [ 'class' => "prp-edit-area $areaClass" ] ) . $label );
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
		if ( $this->isConflict ) {
			// EditPage::showEditForm() does not call showContentForm() in case of a conflict
			// because "conflicts can't be resolved […] using the custom edit ui." Don't show the
			// rest of the custom UI (the quality radio buttons) as well.
			return $checkboxes;
		}

		// @phan-suppress-next-line PhanUndeclaredMethod
		$oldLevel = $this->getCurrentContent()->getLevel();
		$hasRight = $this->permissionManager->userHasRight( $this->context->getUser(), 'pagequality' );
		$levels = [];
		for ( $level = 0; $level <= 4; $level++ ) {
			$newLevel = new PageLevel( $level, $this->context->getUser() );
			$changeAllowed = $oldLevel->isChangeAllowed( $newLevel, $this->permissionManager );

			// Only show valid transitions for the user, unless the user is logged out
			// then show them all, but they'll be disabled.
			if ( !$changeAllowed && $hasRight ) {
				continue;
			}
			$levels[] = $level;
		}

		$pageQualityInputWidget = new PageQualityInputWidget( [
			'tabindex' => $tabindex,
			'disabled' => !$hasRight,
			'levels' => $levels,
			// @phan-suppress-next-line PhanUndeclaredMethod
			'value' => $this->toEditContent( $this->textbox1 )->getLevel()->getLevel(),
		] );

		$labelMsg = $pageQualityInputWidget->isDisabled()
			? 'proofreadpage_page_status_logged_out'
			: 'proofreadpage_page_status';
		$fieldLayout = new FieldLayout( $pageQualityInputWidget, [
			'label' => new HtmlSnippet( $this->context->msg( $labelMsg )->parse() ),
			// This ID and the .prp-quality-widget class are deprecated, but still used in various user scripts etc.
			'id' => 'wpQuality-container',
			'classes' => [ 'prp-page-edit-QualityInputWidget-field', 'prp-quality-widget' ],
		] );

		$checkboxes['wpr-pageStatus'] = $fieldLayout;
		return $checkboxes;
	}

	/**
	 * @inheritDoc
	 */
	protected function importContentFormData( &$request ) {
		/** @var PageContent $content */
		$content = $this->getCurrentContent();
		// @phan-suppress-next-line PhanUndeclaredMethod
		$oldLevel = $content->getLevel();

		return $this->pageContentBuilder->buildContentFromInput(
			$request->getText( 'wpHeaderTextbox' ),
			$request->getText( 'wpTextbox1' ),
			$request->getText( 'wpFooterTextbox' ),
			$request->getInt( 'wpQuality', $oldLevel->getLevel() ),
			$oldLevel
		)->serialize();
	}
}
