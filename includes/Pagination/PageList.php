<?php

namespace ProofreadPage\Pagination;

/**
 * @license GPL-2.0-or-later
 *
 * Representation for a <pagelist> tag
 */
class PageList {

	/**
	 * @var array parameters of the <pagelist> tag
	 */
	private $params = [];

	/**
	 * @var PageNumber[] PageNumber already computed
	 */
	private $pageNumbers = [];

	/**
	 * @param array $params parameters of the <pagelist> tag
	 */
	public function __construct( array $params ) {
		$this->params = $params;
	}

	/**
	 * Returns the PageNumber for the page number $pageNumber
	 *
	 * @param int $pageNumber
	 * @return PageNumber
	 */
	public function getNumber( $pageNumber ) {
		if ( !array_key_exists( $pageNumber, $this->pageNumbers ) ) {
			$this->pageNumbers[$pageNumber] = $this->buildNumber( $pageNumber );
		}

		return $this->pageNumbers[$pageNumber];
	}

	/**
	 * Returns the PageNumber for the page number $pageNumber
	 *
	 * @param integer $pageNumber
	 * @return PageNumber
	 */
	private function buildNumber( $pageNumber ) {
		$mode = PageNumber::DISPLAY_NORMAL; // default mode
		$offset = 0;
		$displayedpageNumber = '';
		$isEmpty = false;
		$isRecto = true;

		foreach ( $this->params as $num => $parameters ) {

			if ( is_numeric( $num ) && $num <= $pageNumber ) {
				$params = explode( ';', $parameters );
				foreach ( $params as $param ) {
					if ( is_numeric( $param ) ) {
						$offset = $num - $param;
					}
				}
			}

			if ( $this->numberInRange( $num, $pageNumber ) ) {
				$params = explode( ';', $parameters );
				foreach ( $params as $param ) {
					if ( !is_numeric( $param ) ) {
						if ( in_array( $param, PageNumber::getDisplayModes() ) ) {
							$mode = $param;
						} elseif ( $param == PageNumber::DISPLAY_EMPTY ) {
							$isEmpty = true;
						} else {
							$displayedpageNumber = $param;
						}
					}
				}

				if ( $param == PageNumber::DISPLAY_FOLIO
					|| $param == PageNumber::DISPLAY_FOLIOHIGHROMAN
					|| $param == PageNumber::DISPLAY_FOLIOROMAN ) {

					$folioStart = $this->getRangeStart( $num );
					$displayedpageNumber = $folioStart - $offset
						+ (int)( ( $pageNumber - $folioStart ) / 2 );

					$isRecto = ( $pageNumber - $folioStart ) % 2 === 0;
				}
			}
		}

		$displayedpageNumber = ( $displayedpageNumber === '' )
			? $pageNumber - $offset
			: $displayedpageNumber;
		return new PageNumber( $displayedpageNumber, $mode, $isEmpty, $isRecto );
	}

	/**
	 * Returns if a number is in a range as defined by <pagelist>
	 * Such range may be a single integer or something in the format XXtoYY
	 *
	 * @param string $range
	 * @param int $number
	 * @return bool
	 */
	protected function numberInRange( $range, $number ) {
		return is_numeric( $range ) && $range == $number ||
			preg_match( '/^([0-9]*)to([0-9]*)((even|odd)?)$/', $range, $m ) &&
			$m[1] <= $number && $number <= $m[2] &&
				( $m[3] === ''
				|| ( $m[3] === 'even' && $number % 2 === 0 )
				|| ( $m[3] === 'odd' && $number % 2 === 1 ) );
	}

	private function getRangeStart( $range ) {
		if ( is_numeric( $range ) ) {
			return $range;
		} elseif ( preg_match( '/^([0-9]*)to([0-9]*)((even|odd)?)$/', $range, $m ) ) {
			return $m[1];
		} else {
			throw new RuntimeException(
				$range . ' is not a valid range.'
			);
		}
	}
}
