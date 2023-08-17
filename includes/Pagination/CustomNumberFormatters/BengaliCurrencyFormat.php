<?php

namespace ProofreadPage\Pagination\CustomNumberFormatters;

use Language;
use NumberFormatter;

class BengaliCurrencyFormat {
	private const NUMBER_TRANSLATION = [
		'৲',
		'৴৹',
		'৵৹',
		'৶৹',
		'৷৹',
		'৷৴৹',
		'৷৵৹',
		'৷৶৹',
		'৷৷৹',
		'৷৷৴৹',
		'৷৷৵৹',
		'৷৷৶৹',
		'৸৹',
		'৸৴৹',
		'৸৵৹',
		'৸৶৹',
	];

	/**
	 * Convert a number to Bengali currency format
	 * @param Language $lang
	 * @param int $number
	 * @return string
	 */
	public function formatNumber( Language $lang, int $number ): string {
		$numStr = '';
		if ( $number <= 0 ) {
			return '০';
		}

		$locale = $lang->getCode();
		$locale .= '-u-nu-beng';

		$firstDigit = $number % 16;
		$number = (int)( $number / 16 );
		$numStr = self::NUMBER_TRANSLATION[$firstDigit];

		$formatter = new NumberFormatter( $locale, NumberFormatter::DEFAULT_STYLE );
		$formatter->setSymbol( NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '' );

		while ( $number > 0 ) {
			$digit = $number % 16;
			$numStr = $formatter->format( $digit ) . $numStr;
			$number = (int)( $number / 16 );
		}

		return $numStr;
	}
}
