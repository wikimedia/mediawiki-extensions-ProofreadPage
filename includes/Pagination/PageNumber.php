<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use Language;

/**
 * @license GPL-2.0-or-later
 */
class PageNumber {

	const DISPLAY_NORMAL = 'normal';
	const DISPLAY_HIGHROMAN = 'highroman';
	const DISPLAY_ROMAN = 'roman';
	const DISPLAY_FOLIO = 'folio';
	const DISPLAY_FOLIOHIGHROMAN = 'foliohighroman';
	const DISPLAY_FOLIOROMAN = 'folioroman';
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
	 * @var boolean
	 */
	protected $isRecto = true;

	/**
	 * @param string $number the page number
	 * @param string $displayMode the display mode (one of the DISPLAY_* constant)
	 * @param bool $isEmpty
	 * @param bool $isRecto true if recto, false if verso (for folio modes only)
	 * @throws InvalidArgumentException
	 */
	public function __construct( $number, $displayMode = self::DISPLAY_NORMAL, $isEmpty = false,
		$isRecto = true ) {
		if ( !in_array( $displayMode, self::getDisplayModes() ) ) {
			throw new InvalidArgumentException( '$displayMode is invalid' );
		}
		$this->number = $number;
		$this->displayMode = $displayMode;
		$this->isEmpty = $isEmpty;
		$this->isRecto = $isRecto;
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
			case self::DISPLAY_FOLIO:
				return $language->formatNum( $this->number, true ) .
					$this->formatRectoVerso( $this->isRecto );
			case self::DISPLAY_FOLIOHIGHROMAN:
				return Language::romanNumeral( $this->number ) .
					$this->formatRectoVerso( $this->isRecto );
			case self::DISPLAY_FOLIOROMAN:
				return strtolower( Language::romanNumeral( $this->number ) ) .
					$this->formatRectoVerso( $this->isRecto );
			default:
				return $this->number;
		}
	}

	/**
	 * Returns the raw page number, without any formatting
	 *
	 * @param Language $language
	 * @return string
	 */
	public function getRawPageNumber( Language $language ) {
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
			case self::DISPLAY_FOLIO:
				return $language->formatNum( $this->number, true ) .
					$this->rawRectoVerso( $this->isRecto );
			case self::DISPLAY_FOLIOHIGHROMAN:
				return Language::romanNumeral( $this->number ) .
					$this->rawRectoVerso( $this->isRecto );
			case self::DISPLAY_FOLIOROMAN:
				return strtolower( Language::romanNumeral( $this->number ) ) .
					$this->rawRectoVerso( $this->isRecto );
			default:
				return $this->number;
		}
	}

	/**
	 * @return bool
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
	 * @return bool
	 */
	public function isRecto() {
		return $this->isRecto;
	}

	/**
	 * @return string
	 */
	private function formatRectoVerso() {
		return $this->isRecto ? '<sup>r</sup>' : '<sup>v</sup>';
	}

	/**
	 * @return string
	 */
	private function rawRectoVerso() {
		return $this->isRecto ? 'r' : 'v';
	}

	/**
	 * @return array
	 */
	public static function getDisplayModes() {
		return [
			self::DISPLAY_NORMAL,
			self::DISPLAY_ROMAN,
			self::DISPLAY_HIGHROMAN,
			self::DISPLAY_FOLIO,
			self::DISPLAY_FOLIOHIGHROMAN,
			self::DISPLAY_FOLIOROMAN
		];
	}
}
