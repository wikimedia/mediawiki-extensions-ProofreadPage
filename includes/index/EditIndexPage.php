<?php

namespace ProofreadPage\Index;

use ContentHandler;
use EditPage;
use OOUI\ButtonWidget;
use OOUI\DropdownInputWidget;
use OOUI\FieldLayout;
use OOUI\FieldsetLayout;
use OOUI\HtmlSnippet;
use OOUI\TextInputWidget;
use ProofreadIndexEntry;
use ProofreadIndexPage;
use ProofreadPage\Context;
use Status;
use WikitextContent;

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
		$out->enableOOUI();
		$inputOptions = [ 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir() ];
		if ( wfReadOnly() ) {
			$inputOptions['readOnly'] = '';
		}

		$fields = [];
		$i = 10;
		/** @var ProofreadIndexEntry $entry */
		foreach ( $this->getActualContent()->getIndexEntries() as $entry ) {
			$inputOptions['tabIndex'] = $i;
			if ( !$entry->isHidden() ) {
				$fields[] = $this->buildEntry( $entry, $inputOptions );
			}
			$i++;
		}

		$out->addHTML( new FieldsetLayout( [
			'items' => $fields,
			'classes' => [ 'prp-index-fieldLayout' ]
		] ) );

		$out->addModules( 'ext.proofreadpage.index' );
	}

	private function buildEntry( ProofreadIndexEntry $entry, $inputOptions ) {
		$key = $this->getFieldNameForEntry( $entry->getKey() );
		$val = $this->safeUnicodeOutput( $entry->getStringValue() );

		$inputOptions['name'] = $key;
		$inputOptions['value'] = $val;
		$inputOptions['inputId'] = $key;

		$values = $entry->getPossibleValues();
		if ( $values !== null ) {
			$options = [];
			foreach ( $values as $data => $label ) {
				$options[] = [ 'data' => $data, 'label' => $label ];
			}
			if ( !array_key_exists( $val, $values ) && $val !== '' ) {
				$options[] = [ 'data' => $val, 'label' => $val ];
			}
			$input = new DropdownInputWidget( $inputOptions + [
				'options' => $options
			] );
		} else {
			$inputAttributes['classes'][] = 'prp-input-' . $entry->getType();
			$input = new TextInputWidget( $inputOptions + [
				'type' => $entry->getType() === 'number' && ( $val === '' || is_numeric( $val ) )
					? 'number'
					: 'text',
				'multiline' => $entry->getSize() > 1,
				'rows' => $entry->getSize()
			] );
		}

		$fieldLayoutArgs = [
			'label' => $entry->getLabel()
		];
		if ( $entry->getHelp() ) {
			$fieldLayoutArgs += [
				'help' => $entry->getHelp(),
				'infusable' => true,
				'classes' => [ 'prp-fieldLayout-help' ]
			];
		}

		return new FieldLayout( $input, $fieldLayoutArgs );
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
		$fields = [];
		foreach ( $config as $key => $params ) {
			$field = $this->getFieldNameForEntry( $key );
			$value = $this->cleanInputtedContent( $this->safeUnicodeInput( $request, $field ) );
			$entry = new ProofreadIndexEntry( $key, $value, $params );
			if ( !$entry->isHidden() ) {
				$fields[$entry->getKey()] = new WikitextContent( $entry->getStringValue() );
			}
		}
		return ( new IndexContent( $fields ) )->serialize( $this->contentFormat );
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
		$content = $this->toEditContent( $this->textbox1 );
		if ( $content instanceof IndexContent ) {
			// Get list of pages titles
			$links = $content->getLinksToNamespace(
				Context::getDefaultContext()->getPageNamespaceId(), $this->getTitle()
			);
			$linksTitle = [];
			foreach ( $links as $link ) {
				$linksTitle[] = $link->getTarget();
			}

			if ( count( $linksTitle ) !== count( array_unique( $linksTitle ) ) ) {
				$this->context->getOutput()->showErrorPage(
					'proofreadpage_indexdupe',
					'proofreadpage_indexdupetext'
				);
				$status = Status::newGood();
				$status->fatal( 'hookaborted' );
				$status->value = self::AS_HOOK_ERROR;

				return $status;
			}
		}

		return parent::internalAttemptSave( $result, $bot );
	}

	private function getActualContent() {
		return new ProofreadIndexPage(
			$this->mTitle,
			ProofreadIndexPage::getDataConfig(),
			$this->toEditContent( $this->textbox1 )
		);
	}
}
