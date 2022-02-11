<?php

namespace ProofreadPage\Index;

use Article;
use EditPage;
use MediaWiki\MediaWikiServices;
use MWException;
use OOUI\DropdownInputWidget;
use OOUI\FieldLayout;
use OOUI\FieldsetLayout;
use OOUI\MultilineTextInputWidget;
use OOUI\TextInputWidget;
use ProofreadPage\Context;
use ProofreadPage\OOUI\PagelistInputWidget;
use ProofreadPage\Pagination\PageNumber;
use Title;
use WebRequest;
use WikitextContent;

/**
 * @license GPL-2.0-or-later
 */
class EditIndexPage extends EditPage {

	/**
	 * @var Context
	 */
	private $extContext;

	/**
	 * @param Article $article Article of page to be edited
	 */
	public function __construct( Article $article ) {
		parent::__construct( $article );

		$this->extContext = Context::getDefaultContext();
	}

	/**
	 * @inheritDoc
	 */
	protected function showContentForm() {
		$pageLang = $this->getTitle()->getPageLanguage();
		$out = $this->context->getOutput();
		$out->enableOOUI();
		$inputOptions = [ 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir() ];
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			$inputOptions['readOnly'] = '';
		}

		$content = $this->toEditContent( $this->textbox1 );
		if ( !( $content instanceof IndexContent ) ) {
			throw new MWException( 'EditIndexPage is only able to display a form for IndexContent' );
		}

		$fields = [];
		$i = 10;
		$entries = $this->extContext->getCustomIndexFieldsParser()->parseCustomIndexFields( $content );
		foreach ( $entries as $entry ) {
			$inputOptions['tabIndex'] = $i;
			if ( !$entry->isHidden() ) {
				$fields[] = $this->buildField( $entry, $inputOptions );
			}
			$i++;
		}

		$inputOptions['tabIndex'] = $i;
		$fields[] = $this->buildCategoriesField( $content->getCategories(), $inputOptions );

		$out->addHTML( new FieldsetLayout( [
			'items' => $fields,
			'classes' => [ 'prp-index-fieldLayout' ]
		] ) );

		$out->addModules( 'ext.proofreadpage.index' );
	}

	/**
	 * @param CustomIndexField $field
	 * @param array $inputOptions
	 * @return FieldLayout
	 */
	private function buildField( CustomIndexField $field, $inputOptions ) {
		$key = $this->getFieldNameForEntry( $field->getKey() );
		$val = $field->getStringValue();
		$out = $this->context->getOutput();

		$out->addJsConfigVars( 'prpPagelistBuiltinLabels', PageNumber::getDisplayModes() );

		$inputOptions['name'] = $key;
		$inputOptions['value'] = $val;
		$inputOptions['inputId'] = $key;

		$values = $field->getPossibleValues();
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
		} elseif ( $field->isPagelist() ) {
			$input = new PagelistInputWidget( array_merge( [
				'templateParameter' => $field->getKey(),
				'rows' => $field->getSize()
			], $inputOptions ) );
			$out->addModules( 'ext.proofreadpage.index.pagelist' );
		} else {
			if ( $field->getSize() > 1 ) {
				$input = new MultilineTextInputWidget( $inputOptions + [
					'rows' => $field->getSize()
				] );
			} else {
				$input = new TextInputWidget( $inputOptions + [
					'type' => $field->getType() === 'number' && ( $val === '' || is_numeric( $val ) )
						? 'number'
						: 'text',
				] );
			}
		}

		$fieldLayoutArgs = [
			'label' => $field->getLabel()
		];
		if ( $field->getHelp() ) {
			$fieldLayoutArgs += [
				'help' => $field->getHelp(),
				'infusable' => true,
				'classes' => [ 'prp-fieldLayout-help' ]
			];
		}

		return new FieldLayout( $input, $fieldLayoutArgs );
	}

	/**
	 * @param array $categories
	 * @param array $inputOptions
	 * @return FieldLayout
	 */
	private function buildCategoriesField( array $categories, $inputOptions ) {
		$input = new TextInputWidget( $inputOptions + [
			'type' => 'text',
			'name' => 'wpPrpCategories',
			'value' => implode( '|', array_map( static function ( Title $title ) {
				return $title->getText();
			}, $categories ) ),
			'inputId' => 'wpPrpCategories'
		] );
		return new FieldLayout( $input, [
			'label' => $this->context->msg( 'proofreadpage-index-field-category-label' )->text(),
			'help' => $this->context->msg( 'proofreadpage-index-edit-category-help' )->text(),
			'infusable' => true,
			'classes' => [ 'prp-fieldLayout-help' ]
		] );
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
	 * @inheritDoc
	 */
	protected function importContentFormData( &$request ) {
		if ( $this->textbox1 !== '' ) {
			return $this->textbox1;
		}

		$config = $this->extContext->getCustomIndexFieldsParser()->getCustomIndexFieldsConfiguration();
		$fields = [];
		foreach ( $config as $key => $params ) {
			$field = $this->getFieldNameForEntry( $key );
			$value = $this->cleanInputtedContent( $request->getText( $field ) );
			$entry = new CustomIndexField( $key, $value, $params );
			if ( !$entry->isHidden() ) {
				$fields[$entry->getKey()] = new WikitextContent( $entry->getStringValue() );
			}
		}
		$content = new IndexContent( $fields, $this->importCategoryList( $request ) );
		return $content->serialize( $this->contentFormat );
	}

	/**
	 * Returns filtered list of categories from a web request.
	 * @param WebRequest $request
	 * @return Title[]
	 */
	private function importCategoryList( WebRequest $request ) {
		return array_filter( array_map( static function ( $text ) {
			return Title::makeTitleSafe( NS_CATEGORY, trim( $text ) );
		}, explode( '|', $request->getText( 'wpPrpCategories' ) ) ), static function ( $title ) {
			return $title !== null;
		} );
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
		do {
			$prev = $value;
			$value = preg_replace( '/\[\[(.*?)&!&(.*?)\]\]/', '[[$1|$2]]', $value );
		} while ( $value !== $prev );

		// ..and in templates
		do {
			$prev = $value;
			$value = preg_replace( '/\{\{(.*?)&!&(.*?)\}\}/s', '{{$1|$2}}', $value );
		} while ( $value !== $prev );

		$value = preg_replace( '/&!&/', '{{!}}', $value );

		return $value;
	}
}
