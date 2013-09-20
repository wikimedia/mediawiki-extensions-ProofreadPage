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
	 * @var ProofreadIndexPage stores the current revision of the page stored in the db.
	 */
	protected $currentPage;

	public function __construct( Article $article ) {
		parent::__construct( $article );
		$this->currentPage = ProofreadPagePage::newFromTitle( $this->mTitle );
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

		$content = ProofreadPageContent::newFromWikitext( $this->textbox1 );
		$page = new ProofreadPagePage( $this->mTitle, $content );
		$content = $page->getContentForEdition();

		$wgOut->addHTML(
			Html::openElement( 'div', array( 'class' => 'prp-page-container' )  ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-content' ) ) .
			Html::openElement( 'div', array( 'class' => 'wpHeader') ) .
			Html::element( 'label', array( 'for' => 'wpHeaderTextbox'), wfMessage( 'proofreadpage_header' ) ) .
			Html::textarea( 'wpHeaderTextbox', $content->getHeader(), $headerAttributes ) .
			Html::element( 'button', array( 'name' => 'hideHeader', 'type' => 'button' ), wfMessage( 'proofreadpage-toggle-headerfooter' )->plain() ) .
			Html::closeElement( 'div' ) .
			Html::element( 'label', array( 'for' => 'wpTextbox1'), wfMessage( 'proofreadpage_body' ) ) .
			Html::textarea( 'wpTextbox1', $content->getBody(), $bodyAttributes ) .
			Html::openElement( 'div', array( 'class' => 'wpFooter') ) .
			Html::element( 'label', array( 'for' => 'wpFooterTextbox'), wfMessage( 'proofreadpage_footer' ) ) .
			Html::closeElement( 'div' ) .
			Html::textarea( 'wpFooterTextbox', $content->getFooter(), $footerAttributes ) .
			Html::element( 'button', array( 'name' => 'hideFooter', 'type' => 'button' ), wfMessage( 'proofreadpage-toggle-headerfooter' )->plain() ) .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'class' => 'prp-page-image' ) ) .
			$page->getImageHtml( array( 'max-width' => 800 ) ) .
			Html::closeElement( 'div' ) .
			Html::closeElement( 'div' )
		);
	}

	/**
	 * Sets the checkboxes for the proofreading status of the page.
	 */
	function getCheckBoxes( &$tabindex, $checked ) {
		global $wgUser;
		$content = ProofreadPageContent::newFromWikitext( $this->textbox1 );
		$oldContent = $this->currentPage->getContent();
		$oldLevel = $oldContent->getProofreadingLevel();
		$oldUser = $oldContent->getProofreader();
		$currentLevel = $content->getProofreadingLevel();
		$qualityLevels = array( 0, 2, 1, 3, 4 );
		$html = '';
		$checkboxes = parent::getCheckBoxes( $tabindex, $checked );

		foreach( $qualityLevels as $level ) {

			if( !$this->isLevelChangeAllowed( $oldUser, $oldLevel, $wgUser, $level ) ) {
				continue;
			}

			$msg = 'proofreadpage_quality' . $level . '_category';
			$cls = 'quality' . $level;

			$attributes = array( 'tabindex' => ++$tabindex, 'title' => wfMessage( $msg )->plain() );
			if( $level == $currentLevel ) {
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


	function getSummaryInput( $summary = "", $labelText = null, $inputAttrs = null, $spanLabelAttrs = null ) {

		if ( !$this->mTitle->exists() ) {
			$summary = '/*' . wfMessage( 'proofreadpage_quality1_category' )->plain() . '*/';
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

		$value = ProofreadPageContent::newEmpty();
		$oldContent = $this->currentPage->getContent();

		try {
			$value->setHeader( $this->safeUnicodeInput( $request, 'wpHeaderTextbox' ) );
		} catch ( MWException $exception) {
			$exception->report();
		}
		try {
			$value->setBody( $this->safeUnicodeInput( $request, 'wpTextbox1') );
		} catch ( MWException $exception ) {
			$exception->report();
		}
		try {
			$value->setFooter( $this->safeUnicodeInput( $request, 'wpFooterTextbox' ) );
		} catch ( MWException $exception ) {
			$exception->report();
		}
		try {
			$value->setLevel( $request->getInt( 'wpQuality' ) );
		} catch ( MWException $exception ) {
			$exception->report();
		}
		$user = ($oldContent->getProofreadingLevel() === $value->getProofreadingLevel())
			? $oldContent->getProofreader()
			: $wgUser;
		if( $oldContent->getProofreader() === null ) {
			$user = $wgUser;
		}
		try {
			$value->setProofreader( $user );
		} catch ( MWException $exception ) {
			$exception->report();
		}

		return $value->serialize();
	}

	/**
	 * Check the validity of the page
	 */
	public function internalAttemptSave( &$result, $bot = false ) {
		global $wgOut;
		$index = $this->currentPage->getIndex();

		$oldContent = $this->currentPage->getContent();
		$oldQ = $oldContent->getProofreadingLevel();
		$oldUser = $oldContent->getProofreader();
		$newContent = ProofreadPageContent::newFromWikitext( $this->textbox1 );
		$newQ = $newContent->getProofreadingLevel();
		$newUser = $newContent->getProofreader();

		if( !$this->mTitle->inNamespace( ProofreadPage::getPageNamespaceId() ) && $index!== null && !$this->isLevelChangeAllowed( $oldUser, $oldQ, $newUser, $newQ ) ) {
			$wgOut->showErrorPage( 'proofreadpage_notallowed', 'proofreadpage_notallowedtext' );
			$status = Status::newGood();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}
		return parent::internalAttemptSave( $result, $bot );
	}

	protected function isLevelChangeAllowed( $oldUser, $oldLevel, $newUser, $newLevel ) {
		if( $oldLevel != -1 ) {
			// check usernames
			if( ( $oldLevel != $newLevel ) && !$newUser->isAllowed( 'pagequality' ) ) {
				return false;
			}

			if( $newLevel == 4 && ( $oldLevel < 3 || $oldLevel == 3 && $oldUser->getName() == $newUser->getName() ) ) {
				return false;
			}
		} else {
			if( $newLevel >= 3 ) {
				return false;
			}
		}
		return true;
	}

}
