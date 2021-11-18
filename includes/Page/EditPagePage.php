<?php

namespace ProofreadPage\Page;

use Article;
use EditPage;
use Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
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
	 * @var PermissionManager
	 */
	private $permissionManager;

	/**
	 * @param Article $article
	 * @param Context $context
	 */
	public function __construct( Article $article, Context $context ) {
		parent::__construct( $article );

		$this->pageContentBuilder = new PageContentBuilder( $this->context, $context );
		$this->pageDisplayHandler = new PageDisplayHandler( $context );
		$this->permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
	}

	/**
	 * @inheritDoc
	 */
	protected function isSectionEditSupported() {
		// sections and forms don't mix
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function isSupportedContentModel( $modelId ) {
		return $modelId === CONTENT_MODEL_PROOFREAD_PAGE;
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
		if ( $this->context->getUser()->getOption( 'usebetatoolbar' ) ) {
			$out->addModules( 'ext.wikiEditor' );
		}
		$out->addModuleStyles( [ 'ext.proofreadpage.base', 'ext.proofreadpage.page' ] );

		$jsVars = $this->pageDisplayHandler->getPageJsConfigVars( $this->getTitle(), $content );
		$out->addJsConfigVars( $jsVars );
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
			[ 'for' => $textareaName, 'class' => 'ext-proofreadpage-label' ],
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

		$user = $this->context->getUser();
		$checkboxes['wpr-pageStatus'] = $this->buildQualityEditWidget( $user, $tabindex );

		return $checkboxes;
	}

	/**
	 * @param User $user
	 * @param int &$tabindex
	 * @return OOUI\Widget
	 * @suppress PhanUndeclaredMethod getLevel
	 */
	private function buildQualityEditWidget( User $user, &$tabindex ) {
		/** @var PageLevel $oldLevel */
		$oldLevel = $this->getCurrentContent()->getLevel();
		/** @var PageContent $content */
		$content = $this->toEditContent( $this->textbox1 );
		$currentLevel = $content->getLevel();
		/** @var boolean $enabled is the widget enabled? */
		$enabled = $this->permissionManager->userHasRight( $user, 'pagequality' );

		$html = '';
		for ( $level = 0; $level <= 4; $level++ ) {
			$newLevel = new PageLevel( $level, $user );
			$changeAllowed = $oldLevel->isChangeAllowed( $newLevel, $this->permissionManager );

			// Only show valid transitions for the user, unless the user is logged out
			// then show them all, but they'll be disabled.
			if ( !$changeAllowed && $enabled ) {
				continue;
			}

			$msg = 'proofreadpage_quality' . $level . '_category';

			$html .= new OOUI\RadioInputWidget( [
				'name' => 'wpQuality',
				'classes' => [ 'prp-quality-radio quality' . $level ],
				'value' => $level,
				'tabIndex' => ++$tabindex,
				'title' => $this->context->msg( $msg ),
				'selected' => $level === $currentLevel->getLevel(),
				'disabled' => !$enabled,
			] );
		}

		$msg = $enabled ? "proofreadpage_page_status" : "proofreadpage_page_status_logged_out";

		$content =
			Html::openElement( 'span', [ 'id' => 'wpQuality-container' ] ) .
			$html .
			Html::closeElement( 'span' ) .
			Html::openElement( 'label', [ 'for' => 'wpQuality-container' ] ) .
			$this->context->msg( $msg )
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
