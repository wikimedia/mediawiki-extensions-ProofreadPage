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
	 * @var ProofreadIndexPage stores the current revision of the page stored in the db
	 */
	protected $currentPage;

	/**
	 * @var ProofreadPageContentHander ContentHandler for Page: pages
	 */
	protected $contentHandler;

	public function __construct( Article $article ) {
		parent::__construct( $article );

		$this->currentPage = ProofreadPagePage::newFromTitle( $this->mTitle );

		$this->contentModel = CONTENT_MODEL_PROOFREAD_PAGE;
		$this->contentHandler = ContentHandler::getForModelID( $this->contentModel );
		$this->contentFormat = $this->contentHandler->getDefaultFormat();
	}

	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	protected function showContentForm() {
		global $wgOut;

		$pageLang = $this->mTitle->getPageLanguage();
		$inputAttributes = array(
			'lang' => $pageLang->getCode(),
			'dir' => $pageLang->getDir(),
			'cols' => '70'
		);

		if( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		$headerAttributes = $inputAttributes + array(
			'id' => 'wpHeaderTextbox',
			'rows' => '2',
			'tabindex' => '1'
		);
		$bodyAttributes = $inputAttributes + array(
			'tabindex' => '1',
			'accesskey' =>',',
			'id' => 'wpTextbox1',
			'rows' => '51',
			'style' =>''
		);
		$footerAttributes = $inputAttributes + array(
			'id' => 'wpFooterTextbox',
			'rows' => '2',
			'tabindex' => '1'
		);

		$content = $this->contentHandler->unserializeContent( $this->textbox1 );
		$page = new ProofreadPagePage( $this->mTitle, $content );
		$content = $page->getContentForEdition();

		$wgOut->addHTML(
			$page->getPageContainerBegin() .
			Html::openElement( 'div', array( 'class' => 'prp-page-edit-header' ) ) .
			Html::element( 'label', array( 'for' => 'wpHeaderTextbox' ), wfMessage( 'proofreadpage_header' )->text() ) .
			Html::textarea( 'wpHeaderTextbox', $content->getHeader()->serialize(), $headerAttributes ) .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-edit-body' ) ) .
			Html::element( 'label', array( 'for' => 'wpTextbox1' ), wfMessage( 'proofreadpage_body' )->text() ) .
			Html::textarea( 'wpTextbox1', $content->getBody()->serialize(), $bodyAttributes ) .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-edit-footer' ) ) .
			Html::element( 'label', array( 'for' => 'wpFooterTextbox' ), wfMessage( 'proofreadpage_footer' )->text() ) .
			Html::textarea( 'wpFooterTextbox', $content->getFooter()->serialize(), $footerAttributes ) .
			Html::closeElement( 'div' ) .
			$page->getPageContainerEnd()
		);
		$wgOut->addModules( 'ext.proofreadpage.page.edit' );
	}

	/**
	 * Sets the checkboxes for the proofreading status of the page.
	 */
	function getCheckBoxes( &$tabindex, $checked ) {
		global $wgUser;

		$oldLevel = $this->currentPage->getContent()->getLevel();

		$content = $this->contentHandler->unserializeContent( $this->textbox1 );
		$currentLevel = $content->getLevel();

		$qualityLevels = array( 0, 2, 1, 3, 4 );
		$html = '';
		$checkboxes = parent::getCheckBoxes( $tabindex, $checked );

		foreach( $qualityLevels as $level ) {

			$newLevel = new ProofreadPageLevel( $level, $wgUser );
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
		if ( $wgUser->isAllowed( 'pagequality' ) ) {
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


	function getSummaryInput( $summary = '', $labelText = null, $inputAttrs = null, $spanLabelAttrs = null ) {

		if ( !$this->mTitle->exists() ) {
			$summary = '/*' . wfMessage( 'proofreadpage_quality1_category' )->plain() . '*/ ' . $summary;
		}

		return parent::getSummaryInput( $summary, $labelText, $inputAttrs, $spanLabelAttrs );
	}

	/**
	 * Extract the page content data from the posted form
	 *
	 * @param $request WebRequest
	 * @todo Support edition by bots.
	 */
	protected function importContentFormData( &$request ) {
		global $wgUser;

		$proofreadingLevel = $request->getInt( 'wpQuality' );
		$oldLevel = $this->currentPage->getContent()->getLevel();
		$user = ( $oldLevel->getLevel() === $proofreadingLevel )
			? $oldLevel->getUser()
			: $wgUser;
		if( $oldLevel->getUser() === null ) {
			$user = $wgUser;
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
	 */
	public function internalAttemptSave( &$result, $bot = false ) {
		global $wgOut;

		$error = '';
		$oldContent = $this->currentPage->getContent();
		$newContent = $this->contentHandler->unserializeContent( $this->textbox1 );

		if ( !$newContent->isValid() ) {
			$error = 'badpage';
		} elseif ( !$oldContent->getLevel()->isChangeAllowed( $newContent->getLevel() ) ) {
			$error = 'notallowed';
		}

		if ( $error !== '' ) {
			$wgOut->showErrorPage( 'proofreadpage_notallowed', 'proofreadpage_notallowedtext' );
			$status = Status::newGood();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}

		return parent::internalAttemptSave( $result, $bot );
	}
}
