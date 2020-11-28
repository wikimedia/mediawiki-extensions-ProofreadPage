<?php

namespace ProofreadPage\Pagination;

use InvalidArgumentException;
use Language;
use NumberFormatter;

/**
 * @license GPL-2.0-or-later
 */
class PageNumber {

	public const DISPLAY_NORMAL = 'normal';
	public const DISPLAY_HIGHROMAN = 'highroman';
	public const DISPLAY_ROMAN = 'roman';
	public const DISPLAY_FOLIO = 'folio';
	public const DISPLAY_FOLIOHIGHROMAN = 'foliohighroman';
	public const DISPLAY_FOLIOROMAN = 'folioroman';
	public const DISPLAY_EMPTY = 'empty';

	/**
	 * Mapping between the ProofreadPage number formats ids and the one from ICU.
	 * The full list of ICU number formats is available here:
	 * https://github.com/unicode-org/cldr/blob/master/common/supplemental/numberingSystems.xml
	 */
	public const DISPLAY_FROM_ICU = [
		'highroman' => 'roman',
		'roman' => 'romanlow',
		'thai' => 'thai',
	];

	/**
	 * @var string
	 */
	protected $number;

	/**
	 * @var string
	 */
	protected $displayMode = self::DISPLAY_NORMAL;

	/**
	 * @var bool
	 */
	protected $isEmpty = false;

	/**
	 * @var bool
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
			throw new InvalidArgumentException( 'PageNumber display mode ' . $displayMode . ' is invalid' );
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

		$number = (int)$this->number;
		switch ( $this->displayMode ) {
			case self::DISPLAY_NORMAL:
				return $language->formatNumNoSeparators( $number );
			case self::DISPLAY_FOLIO:
				return $language->formatNumNoSeparators( $number ) .
					$this->formatRectoVerso();
			case self::DISPLAY_FOLIOHIGHROMAN:
				return self::formatICU( $language, 'roman', $number ) .
					$this->formatRectoVerso();
			case self::DISPLAY_FOLIOROMAN:
				return self::formatICU( $language, 'romanlow', $number ) .
					$this->formatRectoVerso();
			default:
				if ( array_key_exists( $this->displayMode, self::DISPLAY_FROM_ICU ) ) {
					return self::formatICU( $language, self::DISPLAY_FROM_ICU[$this->displayMode], $number );
				} else {
					return $this->number;
				}
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

		$number = (int)$this->number;
		switch ( $this->displayMode ) {
			case self::DISPLAY_NORMAL:
				return $language->formatNumNoSeparators( $number );
			case self::DISPLAY_FOLIO:
				return $language->formatNumNoSeparators( $number ) .
					$this->rawRectoVerso();
			case self::DISPLAY_FOLIOHIGHROMAN:
				return self::formatICU( $language, 'roman', $number ) .
					$this->rawRectoVerso();
			case self::DISPLAY_FOLIOROMAN:
				return self::formatICU( $language, 'romanlow', $number ) .
					$this->rawRectoVerso();
			default:
				if ( array_key_exists( $this->displayMode, self::DISPLAY_FROM_ICU ) ) {
					return self::formatICU( $language, self::DISPLAY_FROM_ICU[$this->displayMode], $number );
				} else {
					return $this->number;
				}
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
	 * @return string[]
	 */
	public static function getDisplayModes() {
		$modes = array_keys( self::DISPLAY_FROM_ICU );
		$modes[] = self::DISPLAY_NORMAL;
		$modes[] = self::DISPLAY_FOLIO;
		$modes[] = self::DISPLAY_FOLIOHIGHROMAN;
		$modes[] = self::DISPLAY_FOLIOROMAN;
		return $modes;
	}

	/**
	 * Formats a number in $language using the name numbering system using the ICU data
	 *
	 * @param Language $language
	 * @param string $name
	 * @param int $number
	 * @return string|false
	 */
	private static function formatICU( Language $language, string $name, int $number ) {
		$locale = $language->getCode() . '-u-nu-' . $name;
		$formatter = new NumberFormatter( $locale, NumberFormatter::DEFAULT_STYLE );
		$formatter->setSymbol( NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '' );
		return $formatter->format( $number );
	}
}
