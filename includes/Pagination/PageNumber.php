<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use Language;

/**
 * @licence GNU GPL v2+
 */
class PageNumber {

	const DISPLAY_NORMAL = 'normal';
	const DISPLAY_HIGHROMAN = 'highroman';
	const DISPLAY_ROMAN = 'roman';
	const DISPLAY_EMPTY = 'empty';

	/**
	 * @var string
	 */
	protected $number;

	/**
	 * @var string
	 */
	protected $displayMode = self::DISPLAY_NORMAL;

	/**
	 * @var boolean
	 */
	protected $isEmpty = false;

	/**
	 * @param string $number the page number
	 * @param string  $displayMode the display mode (one of the DISPLAY_* constant)
	 * @param boolean $isEmpty
	 * @throws InvalidArgumentException
	 */
	public function __construct( $number, $displayMode = self::DISPLAY_NORMAL, $isEmpty = false ) {
		if ( !in_array( $displayMode, self::getDisplayModes() ) ) {
			throw new InvalidArgumentException('$displayMode in invalid');
		}
		$this->number = $number;
		$this->displayMode = $displayMode;
		$this->isEmpty = $isEmpty;
	}

	/**
	 * Returns the formatted page number as it should be displayed
	 *
	 * @param Language $language the language used for formatting
	 * @return string
	 */
	public function getFormattedPageNumber( Language $language ) {
		if ( !is_numeric( $this->number ) ) {
			return $this->number;
		}

		switch ( $this->displayMode ) {
			case self::DISPLAY_HIGHROMAN:
				return Language::romanNumeral( $this->number );
			case self::DISPLAY_ROMAN:
				return strtolower( Language::romanNumeral( $this->number ) );
			case self::DISPLAY_NORMAL:
				return $language->formatNum( $this->number, true );
			default:
				return $this->number;
		}
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->isEmpty;
	}

	/**
	 * @return string
	 */
	public function getDisplayMode() {
		return $this->displayMode;
	}

	/**
	 * @return bool
	 */
	public function isNumeric() {
		return is_numeric( $this->number );
	}

	/**
	 * @return array
	 */
	public static function getDisplayModes() {
		return array(
			self::DISPLAY_NORMAL,
			self::DISPLAY_ROMAN,
			self::DISPLAY_HIGHROMAN
		);
	}
}