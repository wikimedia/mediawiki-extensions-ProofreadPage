<?php

namespace ProofreadPage\Pagination;

use Language;
use MediaWiki\Parser\Sanitizer;
use NumberFormatter;
use ProofreadPage\Pagination\CustomNumberFormatters\BengaliCurrencyFormat;

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
	public const DISPLAY_BENGALI_CURRENCY = 'prpbengalicurrency';
	public const DISPLAY_EMPTY = 'empty';

	/**
	 * Mapping between the ProofreadPage number formats ids and the one from ICU.
	 * The full list of ICU number formats is available here:
	 * https://github.com/unicode-org/cldr/blob/master/common/supplemental/numberingSystems.xml
	 */
	private const DISPLAY_FROM_ICU = [
		'beng' => 'beng',
		'deva' => 'deva',
		'highroman' => 'roman',
		'roman' => 'romanlow',
		'tamldec' => 'tamldec',
		'guru' => 'guru',
		'gujr' => 'gujr',
		'telu' => 'telu',
		'knda' => 'knda',
		'mlym' => 'mlym',
		'orya' => 'orya',
		'thai' => 'thai',
	];

	/** @var string */
	private $number;

	/** @var string */
	private $displayMode;

	/** @var bool */
	private $isEmpty;

	/** @var bool */
	private $isRecto;

	/**
	 * @param string $number the page number
	 * @param string $displayMode the display mode (one of the DISPLAY_* constant)
	 * @param bool $isEmpty
	 * @param bool $isRecto true if recto, false if verso (for folio modes only)
	 */
	public function __construct(
		string $number,
		string $displayMode = self::DISPLAY_NORMAL,
		bool $isEmpty = false,
		bool $isRecto = true
	) {
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
	public function getFormattedPageNumber( Language $language ): string {
		if ( !is_numeric( $this->number ) ) {
			return $this->number;
		}

		$number = (int)$this->number;
		switch ( $this->displayMode ) {
			case self::DISPLAY_NORMAL:
				return Sanitizer::escapeHtmlAllowEntities( $language->formatNumNoSeparators( $number ) );
			case self::DISPLAY_FOLIO:
				return Sanitizer::escapeHtmlAllowEntities( $language->formatNumNoSeparators( $number ) ) .
					$this->formatRectoVerso();
			case self::DISPLAY_FOLIOHIGHROMAN:
				return self::formatICU( $language, 'roman', $number ) .
					$this->formatRectoVerso();
			case self::DISPLAY_FOLIOROMAN:
				return self::formatICU( $language, 'romanlow', $number ) .
					$this->formatRectoVerso();
			case self::DISPLAY_BENGALI_CURRENCY:
				$formatter = new BengaliCurrencyFormat();
				return $formatter->formatNumber( $language, $number );
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
	public function getRawPageNumber( Language $language ): string {
		if ( !is_numeric( $this->number ) ) {
			return $this->number;
		}

		$number = (int)$this->number;
		switch ( $this->displayMode ) {
			case self::DISPLAY_NORMAL:
				return Sanitizer::escapeHtmlAllowEntities( $language->formatNumNoSeparators( $number ) );
			case self::DISPLAY_FOLIO:
				return Sanitizer::escapeHtmlAllowEntities( $language->formatNumNoSeparators( $number ) ) .
					$this->rawRectoVerso();
			case self::DISPLAY_FOLIOHIGHROMAN:
				return self::formatICU( $language, 'roman', $number ) .
					$this->rawRectoVerso();
			case self::DISPLAY_FOLIOROMAN:
				return self::formatICU( $language, 'romanlow', $number ) .
					$this->rawRectoVerso();
			case self::DISPLAY_BENGALI_CURRENCY:
				$formatter = new BengaliCurrencyFormat();
				return $formatter->formatNumber( $language, $number );
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
	public function isEmpty(): bool {
		return $this->isEmpty;
	}

	/**
	 * @return string
	 */
	public function getDisplayMode(): string {
		return $this->displayMode;
	}

	/**
	 * @return bool
	 */
	public function isNumeric(): bool {
		return is_numeric( $this->number );
	}

	/**
	 * @return bool
	 */
	public function isRecto(): bool {
		return $this->isRecto;
	}

	/**
	 * @return string
	 */
	private function formatRectoVerso(): string {
		return $this->isRecto ? '<sup>r</sup>' : '<sup>v</sup>';
	}

	/**
	 * @return string
	 */
	private function rawRectoVerso(): string {
		return $this->isRecto ? 'r' : 'v';
	}

	/**
	 * @return string[]
	 */
	public static function getDisplayModes(): array {
		$modes = array_keys( self::DISPLAY_FROM_ICU );
		$modes[] = self::DISPLAY_NORMAL;
		$modes[] = self::DISPLAY_FOLIO;
		$modes[] = self::DISPLAY_FOLIOHIGHROMAN;
		$modes[] = self::DISPLAY_FOLIOROMAN;
		$modes[] = self::DISPLAY_BENGALI_CURRENCY;
		return $modes;
	}

	/**
	 * Formats a number in $language using the name numbering system using the ICU data
	 *
	 * @param Language $language
	 * @param string|null $name
	 * @param int $number
	 * @return string|false
	 */
	private static function formatICU( Language $language, ?string $name, int $number ) {
		$locale = $language->getCode();
		if ( $name !== null ) {
			$locale .= '-u-nu-' . $name;
		}
		$formatter = new NumberFormatter( $locale, NumberFormatter::DEFAULT_STYLE );
		$formatter->setSymbol( NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '' );
		return $formatter->format( $number );
	}
}
