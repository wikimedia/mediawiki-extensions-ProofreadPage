<?php

namespace ProofreadPage\Index;

/**
 * @license GPL-2.0-or-later
 *
 * An index entry.
 */
class CustomIndexField {

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
	 * @param string $key
	 * @param string $value
	 * @param array $config
	 */
	public function __construct( $key, $value, array $config ) {
		$this->key = $key;
		$this->value = trim( $value );
		$this->config = $config;
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
				return (string)$this->config['default'];
			} else {
				return '';
			}
		} else {
			return $this->value;
		}
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType(): string {
		if ( isset( $this->config['type'] ) && $this->config['type'] !== null && $this->config['type'] !== '' ) {
			return strtolower( $this->config['type'] );
		} else {
			return 'string';
		}
	}

	/**
	 * Return the field's data name.
	 * This is a lowercase string that defines what sort of information is stored in this field.
	 *
	 * @return ?string
	 */
	public function getData(): ?string {
		if ( isset( $this->config['data'] ) && $this->config['data'] !== null && $this->config['data'] !== '' ) {
			return strtolower( $this->config['data'] );
		} else {
			return null;
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
		if (
			isset( $this->config['size'] ) &&
			is_numeric( $this->config['size'] ) &&
			$this->config['size'] >= 1
		) {
			return (int)$this->config['size'];
		} else {
			return 1;
		}
	}

	/**
	 * Return the possible values of the entry as an array value => label of null
	 * @return string[]|null
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
		if ( in_array( strtolower( $this->key ), [ 'header', 'footer', 'css', 'width' ] ) ) {
			return true;
		} else {
			if ( isset( $this->config['header'] ) ) {
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
	 * Say if entry should be exposed via mw.config
	 * @return bool
	 */
	public function isAllowedInJs() {
		if ( isset( $this->config['js'] ) ) {
			if ( is_bool( $this->config['js'] ) ) {
				return $this->config['js'];
			} else {
				return filter_var( $this->config['js'], FILTER_VALIDATE_BOOLEAN );
			}
		} else {
			return false;
		}
	}

	/**
	 * Say if entry expects a pagelist
	 * @return bool
	 */
	public function isPagelist() {
		return $this->getData() === 'pagelist';
	}
}
