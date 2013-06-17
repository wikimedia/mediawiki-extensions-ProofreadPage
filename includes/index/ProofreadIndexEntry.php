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

/**
 * An index entry.
 */
class ProofreadIndexEntry {

	/**
	 * The key of the entry
	 * @var string
	 */
	protected $key;

	/**
	 * The value of the entry
	 * @var string
	 */
	protected $value;

	/**
	 * The config of the entry
	 * @var array
	 */
	protected $config;

	/**
	 * @param $key string
	 * @param $value string
	 * @param $config array
	 */
	public function __construct( $key, $value, $config ) {
		$this->key = $key;
		$this->value = trim( $value );
		$this->config = $config;
		if ( isset( $this->config['type'] ) )
			$this->config['type'] = strtolower( $this->config['type'] );
		if ( isset( $this->config['data'] ) )
			$this->config['data'] = strtolower( $this->config['data'] );
	}

	/**
	 * Return the key of the entry
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function getStringValue() {
		if ( $this->value === '' ) {
			if ( isset( $this->config['default'] ) ) {
				return (string) $this->config['default'];
			} else {
				return '';
			}
		} else {
			return $this->value;
		}
	}

	/**
	 * Return the values of the entry as string and splitted with the delimiter content
	 * @return array string
	 */
	public function getStringValues() {
		$value = $this->getStringValue();

		if( $value === '' ) {
			return array();
		}

		if( !isset( $this->config['delimiter'] ) || !$this->config['delimiter'] ) {
			return array( $value );
		}

		$delimiters = $this->config['delimiter'];
		if( !is_array( $delimiters ) ) {
			$delimiters = array( $delimiters );
		}

		$values = array( $value );
		foreach( $delimiters as $delimiter ) {
			$values2 = array();
			foreach( $values as $val) {
				$values2 = array_merge( $values2, explode( $delimiter, $val ) );
			}
			$values = $values2;
		}

		foreach( $values as $id => $value) {
			$values[$id] = trim( $value );
		}
		return $values;
	}

	/**
	 * Return typed value. If the value doesn't match the value pattern a ProofreadIndexValueString is return.
	 * @param $value string
	 * @return ProofreadIndexValue
	 */
	protected function getTypedValue( $value ) {
		try {
			$class = ProofreadIndexValue::getIndexValueClassNameForType( $this->getType() );
			$val = new $class( $value, $this->config );
		} catch( MWException $e ) {
			$class = ProofreadIndexValue::getIndexValueClassNameForType( 'string' );
			$val = new $class( $value, $this->config );
		}
		return $val;
	}

	/**
	 * Return the values of the entry as ProofreadIndexValue and splitted with the delimiter content
	 * @return array ProofreadIndexValue
	 */
	public function getTypedValues() {
		$values = $this->getStringValues();

		foreach( $values as $id => $value) {
			$values[$id] = $this->getTypedValue( $value );
		}
		return $values;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		if ( isset( $this->config['type'] ) && $this->config['type'] != '' ) {
			return $this->config['type'];
		} else {
			return 'string';
		}
	}

	/**
	 * Return the label of the entry
	 * @return string
	 */
	public function getLabel() {
		if ( isset( $this->config['label'] ) && $this->config['label'] != '' ) {
			return $this->config['label'];
		} else {
			return $this->key;
		}
	}

	/**
	 * Return size of the edition field
	 * @return int
	 */
	public function getSize() {
		if ( isset( $this->config['size'] ) && is_numeric( $this->config['size'] ) && $this->config['size'] >= 1 ) {
				return (int) $this->config['size'];
		} else {
			return 1;
		}
	}

	/**
	 * Return the possible values of the entry as an array value => label of null
	 * @return array|null
	 */
	public function getPossibleValues() {
		if ( isset( $this->config['values'] ) && is_array( $this->config['values'] ) ) {
			return $this->config['values'];
		} else {
			return null;
		}
	}

	/**
	 * Return the help text or an empty string
	 * @return string
	 */
	public function getHelp() {
		if ( isset( $this->config['help'] ) && $this->config['help'] ) {
			return $this->config['help'];
		} else {
			return '';
		}
	}

	/**
	 * Say if the entry is "hidden"
	 * @return bool
	 */
	public function isHidden() {
		if ( isset( $this->config['hidden'] ) ) {
			if ( is_bool( $this->config['hidden'] ) ) {
				return $this->config['hidden'];
			} else {
				return filter_var( $this->config['hidden'], FILTER_VALIDATE_BOOLEAN );
			}
		} else {
			return false;
		}
	}

	/**
	 * Say if the entry have to be given to "header template"
	 * @return bool
	 */
	public function isHeader() {
		if ( in_array( strtolower( $this->key ), array( 'header', 'footer', 'css', 'width' ) ) ) {
			return true;
		} else {
			if( isset( $this->config['header'] ) ) {
				if ( is_bool( $this->config['header'] ) ) {
					return $this->config['header'];
				} else {
					return filter_var( $this->config['header'], FILTER_VALIDATE_BOOLEAN );
				}
			} else {
				return false;
			}
		}
	}

	/**
	 * Return the qualified Dublin Core property the entry belongs to with the 'dcterms' or 'dc' prefix
	 * @see http://dublincore.org/documents/dcmi-terms/
	 * @return string
	 */
	public function getQualifiedDublinCoreProperty() {
		if( !isset( $this->config['data'] ) || !$this->config['data'] )
			return null;

		switch( $this->config['data'] ) {
			case 'year':
				return 'dcterms:issued';
			case 'place':
				return 'dcterms:spatial';
			default:
				return $this->getSimpleDublinCoreProperty();
		}
	}

	/**
	 * Return the qualified Dublin Core property the entry belongs to with the 'dc' prefix
	 * @see http://dublincore.org/documents/dces/
	 * @return string
	 */
	public function getSimpleDublinCoreProperty() {
		if( !isset( $this->config['data'] ) || !$this->config['data'] ) {
			return null;
		}

		switch( $this->config['data'] ) {
			case 'language':
				return 'dc:language';
			case 'identifier':
				return 'dc:identifier';
			case 'title':
				return 'dc:title';
			case 'author':
				return 'dc:creator';
			case 'publisher':
				return 'dc:publisher';
			case 'translator':
			case 'illustrator':
				return 'dc:contributor';
			case 'year':
				return 'dc:date';
		}
	}
}
