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

		if ( $this->contentModel !== CONTENT_MODEL_PROOFREAD_PAGE ) {
			throw MWException( 'EditProofreadPagePage should only be called on ProofreadPageContent' );
		}
	}

	/**
	 * @see EditPage::isSectionEditSupported
	 */
	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	/**
	 * Load the content before edit
	 *
	 * @see EditPage::showContentForm
	 */
	protected function getContentObject( $def_content = null ) {
		//preload content
		if ( !$this->mTitle->exists() ) {
			$index = $this->pagePage->getIndex();
			if ( $index ) {
				$params = array(
					'pagenum' => $index->getDisplayedPageNumber( $this->getTitle() )
				);
				$header = $index->replaceVariablesWithIndexEntries( 'header', $params );
				$body = '';
				$footer = $index->replaceVariablesWithIndexEntries( 'footer', $params );

				//Extract text layer
				$image = $index->getImage();
				$pageNumber = $this->pagePage->getPageNumber();
				if ( $image && $pageNumber !== null && $image->exists() ) {
					$text = $image->getHandler()->getPageText( $image, $pageNumber );
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
		}

		return parent::getContentObject( $def_content );
	}

	/**
	 * @see EditPage::showContentForm
	 */
	protected function showContentForm() {
		$out = $this->mArticle->getContext()->getOutput();
		$pageLang = $this->mTitle->getPageLanguage();

		//custom CSS for preview
		$css = $this->pagePage->getCustomCss();
		if ( $css !== '' ) {
			$out->addInlineStyle( $css );
		}

		$inputAttributes = array(
			'lang' => $pageLang->getCode(),
			'dir' => $pageLang->getDir(),
			'cols' => '70'
		);

		if ( wfReadOnly() ) {
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

		$content = $this->toEditContent( $this->textbox1 );
		$out->addHTML(
			$this->pagePage->getPageContainerBegin() .
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
			$this->pagePage->getPageContainerEnd()
		);
		$out->addModules( 'ext.proofreadpage.page.edit' );
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
	 * @see EditPage::getSummaryInput
	 */
	function getSummaryInput( $summary = '', $labelText = null, $inputAttrs = null, $spanLabelAttrs = null ) {

		if ( !$this->mTitle->exists() ) {
			$summary = '/*' . wfMessage( 'proofreadpage_quality1_category' )->plain() . '*/ ' . $summary;
		}

		return parent::getSummaryInput( $summary, $labelText, $inputAttrs, $spanLabelAttrs );
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
