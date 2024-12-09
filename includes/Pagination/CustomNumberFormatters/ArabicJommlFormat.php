<?php

namespace ProofreadPage\Pagination\CustomNumberFormatters;

class ArabicJommlFormat {
	private const ABJAD = [
		[ '', 'ق', 'ر', 'ش', 'ت', 'ث', 'خ', 'ذ', 'ض', 'ظ', ],
		[ '', 'ي', 'ك', 'ل', 'م', 'ن', 'س', 'ع', 'ف', 'ص', ],
		[ '', 'ا', 'ب', 'ج', 'د', 'ه', 'و', 'ز', 'ح', 'ط' ],
		'thousand' => 'غ'
	];

	private const ABJAD_W = [
		[ '', 'ق', 'ر', 'س', 'ت', 'ث', 'خ', 'ذ', 'ظ', 'غ' ],
		[ '', 'ي', 'ك', 'ل', 'م', 'ن', 'ص', 'ع', 'ف', 'ض' ],
		[ '', 'ا', 'ب', 'ج', 'د', 'ه', 'و', 'ز', 'ح', 'ط' ],
		'thousand' => 'ش'
	];

	/**
	 * base function to convert a number to Arabic Jomml format 1-999
	 * @param int $number number to convert
	 * @param array $jommlarray jomml array to use
	 * @return string
	 */
	private function basejomml( int $number, $jommlarray ): string {
		$result = '';
		foreach ( [ 100, 10, 1 ] as $positionkey => $basenumber ) {
			$postiondigit = intdiv( $number, $basenumber );
			if ( $postiondigit > 0 ) {
				$result .= $jommlarray[$positionkey][$postiondigit];
			}
			$number %= $basenumber;
		}
		return $result;
	}

	/**
	 * Convert a number to Arabic Jomml format
	 * @param int $number number to convert
	 * @param bool $maghribi indicate to use Maghribi jomml table
	 * @return string
	 */
	public function formatNumber( int $number, bool $maghribi = false ): string {
		if ( $number > 999999 || $number < 1 ) {
			return (string)$number;
		}

		$j = $maghribi ? self::ABJAD_W : self::ABJAD;

		$result = '';
		$t = intdiv( $number, 1000 );
		$i = $number % 1000;

		if ( $t > 1 ) {
			$result = $this->basejomml( $t, $j ) . $j[ 'thousand' ];
		} elseif ( $t == 1 ) {
			$result = $j[ 'thousand' ];
		}

		return $result . $this->basejomml( $i, $j );
	}
}
