<?php

namespace ProofreadPage\Index;

use FormatJson;
use OutOfBoundsException;

/**
 * @license GPL-2.0-or-later
 *
 * Returns the custom index entries from an IndexContent
 */
class CustomIndexFieldsParser {

	private $configuration;

	public function __construct( array $customIndexFieldsConfiguration = null ) {
		$this->configuration = ( $customIndexFieldsConfiguration === null )
			? $this->loadCustomIndexFieldsConfiguration()
			: $customIndexFieldsConfiguration;
	}

	/**
	 * @return array the configuration
	 * The configuration is a list of properties like this :
	 * array(
	 * 	'ID' => array( //the property id
	 * 		'type' => 'string', //the property type (for compatibility reasons the values have not
	 *               //to be of this type). Possible values: string, number, page
	 * 		'size' => 1, //for type = string : the size of the form input
	 * 		'default' => '', //the default value
	 * 		'label' => 'ID', //the label of the property
	 * 		'help' => '', //a short help text
	 * 		'values' => null, //an array value => label that list the possible values
	 *               //(for compatibility reasons the stored values have not to be one of these)
	 * 		'header' => false, //give the content of this property to
	 *               //Mediawiki:Proofreadpage_header_template as template parameter
	 * 		'hidden' => false //don't show the property in the index pages form. Useful for data
	 *               //that have always the same value (as language=en for en Wikisource) or are
	 *               //only set at the <pages> tag level.
	 * 		)
	 * );
	 *  NB: The values set are the default values
	 */
	public function getCustomIndexFieldsConfiguration() {
		return $this->configuration;
	}

	private function loadCustomIndexFieldsConfiguration() {
		$data = wfMessage( 'proofreadpage_index_data_config' )->inContentLanguage();
		if ( $data->exists() &&	$data->plain() != '' ) {
			$config = FormatJson::decode( $data->plain(), true );
			if ( $config === null ) {
				global $wgOut;
				$wgOut->showErrorPage(
					'proofreadpage_dataconfig_badformatted',
					'proofreadpage_dataconfig_badformattedtext'
				);
				$config = [];
			}
		} else {
			$attributes = explode( "\n", wfMessage( 'proofreadpage_index_attributes' )
				->inContentLanguage()->text() );
			$headerAttributes = explode( ' ', wfMessage( 'proofreadpage_js_attributes' )
				->inContentLanguage()->text() );
			$config = [];
			foreach ( $attributes as $attribute ) {
				$m = explode( '|', $attribute );
				$params = [
					'type' => 'string',
					'size' => 1,
					'default' => '',
					'label' => $m[0],
					'help' => '',
					'values' => null,
					'header' => false
				];

				if ( $m[0] == 'Header' ) {
					$params['default'] = wfMessage( 'proofreadpage_default_header' )
						->inContentLanguage()->plain();
				}
				if ( $m[0] == 'Footer' ) {
					$params['default'] = wfMessage( 'proofreadpage_default_footer' )
						->inContentLanguage()->plain();
				}
				if ( isset( $m[1] ) && $m[1] !== '' ) {
					$params['label'] = $m[1];
				}
				if ( isset( $m[2] ) ) {
					$params['size'] = intval( $m[2] );
				}
				$config[$m[0]] = $params;
			}

			foreach ( $headerAttributes as $attribute ) {
				if ( isset( $config[$attribute] ) ) {
					$config[$attribute]['header'] = true;
				} else {
					$config[$attribute] = [
						'type' => 'string',
						'size' => 1,
						'default' => '',
						'label' => $attribute,
						'values' => null,
						'header' => true,
						'hidden' => true
					];
				}
			}
		}

		if ( !array_key_exists( 'Header', $config ) ) {
			$config['Header'] = [
				'default' => wfMessage( 'proofreadpage_default_header' )
					->inContentLanguage()->plain(),
				'header' => true,
				'hidden' => true
			];
		}
		if ( !array_key_exists( 'Footer', $config ) ) {
			$config['Footer'] = [
				'default' => wfMessage( 'proofreadpage_default_footer' )
					->inContentLanguage()->plain(),
				'header' => true,
				'hidden' => true
			];
		}

		return $config;
	}

	/**
	 * @param IndexContent $content
	 * @return CustomIndexField[]
	 */
	public function parseCustomIndexFields( IndexContent $content ) {
		$contentFields = [];
		foreach ( $content->getFields() as $key => $value ) {
			$contentFields[strtolower( $key )] = $value;
		}

		$values = [];
		foreach ( $this->configuration as $varName => $property ) {
			$key = strtolower( $varName );
			if ( array_key_exists( $key, $contentFields ) ) {
				$values[$varName] = new CustomIndexField(
					$varName, $contentFields[$key]->getNativeData(), $property
				);
			} else {
				$values[$varName] = new CustomIndexField( $varName, '', $property );
			}
		}
		return $values;
	}

	/**
	 * Return metadata from the index page that have to be given to header template.
	 * @param IndexContent $content
	 * @return CustomIndexField[]
	 */
	public function parseCustomIndexFieldsForHeader( IndexContent $content ) {
		$entries = $this->parseCustomIndexFields( $content );
		$headerEntries = [];
		foreach ( $entries as $entry ) {
			if ( $entry->isHeader() ) {
				$headerEntries[$entry->getKey()] = $entry;
			}
		}
		return $headerEntries;
	}

	/**
	 * Return the index entry with the same name or null if it's not found
	 * Note: the comparison is case insensitive
	 * @param IndexContent $content
	 * @param string $fieldName
	 * @return CustomIndexField
	 * @throws OutOfBoundsException
	 */
	public function parseCustomIndexField( IndexContent $content, $fieldName ) {
		$fieldName = strtolower( $fieldName );
		$entries = $this->parseCustomIndexFields( $content );
		foreach ( $entries as $entry ) {
			if ( strtolower( $entry->getKey() ) === $fieldName ) {
				return $entry;
			}
		}
		throw new OutOfBoundsException( 'Custom index entry ' . $fieldName . ' does not exist.' );
	}

	/**
	 * Return the value of an entry as wikitext with variable replaced with index entries and
	 * $otherParams
	 * Example: if 'header' entry is 'Page of {{title}} number {{pagenum}}' with
	 * $otherParams = array( 'pagenum' => 23 )
	 * the function called for 'header' will returns 'Page page my book number 23'
	 * @param IndexContent $content
	 * @param string $fieldName
	 * @param array $otherParams associative array other possible values to replace
	 * @return string the value with variables replaced
	 * @throws OutOfBoundsException
	 */
	public function parseCustomIndexFieldWithVariablesReplacedWithIndexEntries(
		IndexContent $content, $fieldName, $otherParams
	) {
		$entry = $this->parseCustomIndexField( $content, $fieldName );

		// we can't use the parser here because it replace tags like <references /> by strange UIDs
		$params = $this->parseCustomIndexFieldsAsTemplateParams( $content ) + $otherParams;
		return preg_replace_callback(
			'/{\{\{(.*)(\|(.*))?\}\}\}/U',
			function ( $matches ) use ( $params ) {
				$paramKey = trim( strtolower( $matches[1] ) );
				if ( array_key_exists( $paramKey, $params ) ) {
					return $params[$paramKey];
				} elseif ( array_key_exists( 3, $matches ) ) {
					return trim( $matches[3] );
				} else {
					return $matches[0];
				}
			},
			$entry->getStringValue()
		);
	}

	/**
	 * Returns the index entries formatted in order to be transcluded in templates
	 * @return string[]
	 */
	private function parseCustomIndexFieldsAsTemplateParams( IndexContent $content ) {
		$indexEntries = $this->parseCustomIndexFieldsForHeader( $content );
		$params = [];
		foreach ( $indexEntries as $entry ) {
			$params[strtolower( $entry->getKey() )] = $entry->getStringValue();
		}
		return $params;
	}
}
