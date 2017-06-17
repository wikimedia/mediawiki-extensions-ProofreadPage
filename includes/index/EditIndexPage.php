<?php

namespace ProofreadPage\Index;

use ContentHandler;
use EditPage;
use Html;
use OutputPage;
use ProofreadIndexEntry;
use ProofreadIndexPage;
use Status;
use Xml;
use XmlSelect;

/**
 * @licence GNU GPL v2+
 */
class EditIndexPage extends EditPage {

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
		return $modelId === CONTENT_MODEL_PROOFREAD_INDEX;
	}

	/**
	 * @see EditPage::showContentForm
	 */
	protected function showContentForm() {
		$pageLang = $this->mTitle->getPageLanguage();
		$out = $this->context->getOutput();
		$inputAttributes = [ 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir() ];
		if ( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		$entries = $this->getActualContent()->getIndexEntries();

		$out->addHTML( Html::openElement( 'table', [ 'id' => 'prp-formTable' ] ) );
		$i = 10;
		foreach ( $entries as $entry ) {
			$inputAttributes['tabindex'] = $i;
			$this->addEntry( $entry, $inputAttributes, $out );
			$i++;
		}
		$out->addHTML( Html::closeElement( 'table' ) );

		$out->addModules( 'ext.proofreadpage.index' );
	}

	/**
	 * Add an entry to the form
	 *
	 * @param ProofreadIndexEntry $entry
	 * @param array $inputAttributes
	 */
	protected function addEntry( ProofreadIndexEntry $entry, $inputAttributes, OutputPage $out ) {
		if ( $entry->isHidden() ) {
			return;
		}
		$key = $this->getFieldNameForEntry( $entry->getKey() );
		$val = $this->safeUnicodeOutput( $entry->getStringValue() );

		$out->addHTML( Html::openElement( 'tr' ) . Html::openElement( 'th', [ 'scope' => 'row' ] ) . Xml::label( $entry->getLabel(), $key ) );

		$help = $entry->getHelp();
		if ( $help !== '' ) {
			$out->addHTML( Html::element( 'span', [ 'title' => $help, 'class' => 'prp-help-field' ] ) );
		}

		$out->addHTML( Html::closeElement( 'th' ) . Html::openElement( 'td' ) );

		$values = $entry->getPossibleValues();
		if ( $values !== null ) {
			$select = new XmlSelect( $key, $key, $val );
			foreach ( $values as $value => $label ) {
				$select->addOption( $label, $value );
			}
			if ( !isset( $values[$val] ) && $val !== '' ) { // compatiblity with already set data that aren't in the list
				$select->addOption( $val, $val );
			}
			$out->addHTML( $select->getHtml() );
		} else {
			$type = $entry->getType();
			$inputType = ( $type === 'number' && ( $val === '' || is_numeric( $val ) ) ) ? 'number' : 'text';
			$size = $entry->getSize();
			$inputAttributes['class'] = 'prp-input-' . $type;

			if ( $size === 1 ) {
				$inputAttributes['type'] = $inputType;
				$inputAttributes['id'] = $key;
				$inputAttributes['size'] = 60;
				$out->addHTML( Html::input( $key, $val, $inputType, $inputAttributes ) );
			} else {
				$inputAttributes['cols'] = 60;
				$inputAttributes['rows'] = $size;
				$out->addHTML( Html::textarea( $key, $val, $inputAttributes ) );
			}
		}

		$out->addHTML( Html::closeElement( 'td' ) . Html::closeElement( 'tr' ) );
	}

	/**
	 * Return the name of the edit field for an entry
	 *
	 * @param string $key the entry key
	 * @return string
	 */
	protected function getFieldNameForEntry( $key ) {
		return 'wpprpindex-' . str_replace( ' ', '_', $key );
	}

	/**
	 * @see EditPage::importContentFormData
	 */
	protected function importContentFormData( &$request ) {
		if ( $this->textbox1 !== '' ) {
			return $this->textbox1;
		}

		$config = ProofreadIndexPage::getDataConfig();
		$text = "{{:MediaWiki:Proofreadpage_index_template";
		foreach ( $config as $key => $params ) {
			$field = $this->getFieldNameForEntry( $key );
			$value = $this->cleanInputtedContent( $this->safeUnicodeInput( $request, $field ) );
			$entry = new ProofreadIndexEntry( $key, $value, $params );
			if ( !$entry->isHidden() ) {
				$text .= "\n|" . $entry->getKey() . "=" . $entry->getStringValue();
			}
		}

		return $text . "\n}}";
	}

	/**
	 * Clean a text before inclusion into a template
	 *
	 * @param string $value
	 * @return string
	 */
	protected function cleanInputtedContent( $value ) {
		// remove trailing \n \t or \r
		$value = trim( $value );

		// replace pipe symbol everywhere...
		$value = preg_replace( '/\|/', '&!&', $value );

		// ...except in links...
		$prev = '';
		do {
			$prev = $value;
			$value = preg_replace( '/\[\[(.*?)&!&(.*?)\]\]/', '[[$1|$2]]', $value );
		} while ( $value != $prev );

		// ..and in templates
		do {
			$prev = $value;
			$value = preg_replace( '/\{\{(.*?)&!&(.*?)\}\}/s', '{{$1|$2}}', $value );
		} while ( $value != $prev );

		$value = preg_replace( '/&!&/', '{{!}}', $value );

		return $value;
	}

	/**
	 * Check the validity of the page
	 *
	 * @see EditPage::internalAttemptSave
	 */
	public function internalAttemptSave( &$result, $bot = false ) {
		$index = $this->getActualContent();

		// Get list of pages titles
		$links = $index->getLinksToPageNamespace();
		$linksTitle = [];
		foreach ( $links as $link ) {
			$linksTitle[] = $link[0];
		}

		if ( count( $linksTitle ) !== count( array_unique( $linksTitle ) ) ) {
			$this->context->getOutput()->showErrorPage( 'proofreadpage_indexdupe', 'proofreadpage_indexdupetext' );
			$status = Status::newGood();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;

			return $status;
		}

		return parent::internalAttemptSave( $result, $bot );
	}

	private function getActualContent() {
		return new ProofreadIndexPage(
			$this->mTitle,
			ProofreadIndexPage::getDataConfig(),
			ContentHandler::getForModelID( $this->contentModel )->unserializeContent( $this->textbox1, $this->contentFormat )
		);
	}
}
