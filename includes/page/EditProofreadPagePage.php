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

	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	protected function showContentForm() {
		global $wgOut;

		$pageLang = $this->mTitle->getPageLanguage();
		$inputAttributes = array(
			'lang' => $pageLang->getCode(),
			'dir' => $pageLang->getDir() );

		if( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		$textareaAttributes = array(
			'tabindex' => '1',
			'accesskey' =>',',
			'id' => 'wpTextbox1',
			'cols' => '80',
			'rows' => '25',
			'style' =>'' );
		$textareaAttributes += $inputAttributes;
		$imageAttributes = array( 'id' => 'ProofReadImage' );

		$content = ProofreadPageContent::newFromWikitext( $this->textbox1 );
		$page = new ProofreadPagePage( $this->mTitle, $content );

		$content = $page->getContentForEdition();
		$text = $content->getBody();

		$wgOut->addHTML( Html::textarea( 'wpTextbox1', $text, $textareaAttributes ) );
	}

	/**
	 * Extract the page content data from the posted form
	 *
	 * @param $request WebRequest
	 * @todo Support edition by bots.
	 */
	protected function importContentFormData( &$request ) {

		$value = ProofreadPageContent::newEmpty();
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
		try {
			$value->setProofreaderFromName( $this->safeUnicodeInput( $request, 'wpProofreader' ) );
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
		$page = ProofreadPagePage::newFromTitle( $this->mTitle );
		$index = $page->getIndex();

		$oldContent = $page->getContent();
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
			if( ( $oldLevel != $newLevel ) && !$wgUser->isAllowed( 'pagequality' ) ) {
				return false;
			}
			if ( ( $oldUser != $newUser ) || ( $oldLevel != $newLevel ) ) {
				return false;
			}
			if( ( ( $newLevel == 4 ) && ( $oldLevel < 3 ) ) || ( ( $newLevel == 4 ) && ( $oldLevel == 3 ) && ( $oldUser == $newUser ) ) ) {
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
