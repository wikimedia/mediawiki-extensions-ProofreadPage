<?php

namespace ProofreadPage\Pagination;

/**
 * @licence GNU GPL v2+
 *
 * Representation for a <pagelist> tag
 */
class PageList {

	/**
	 * @var array parameters of the <pagelist> tag
	 */
	private $params = array();

	/**
	 * @var PageNumber[] PageNumber already computed
	 */
	private $pageNumbers = array();

	/**
	 * @param array $params parameters of the <pagelist> tag
	 */
	public function __construct( array $params ) {
		$this->params = $params;
	}

	/**
	 * Returns the PageNumber for the page number $pageNumber
	 *
	 * @param integer $pageNumber
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
		$mode = PageNumber::DISPLAY_NORMAL; //default mode
		$offset = 0;
		$displayedpageNumber = '';
		$isEmpty = false;
		foreach ( $this->params as $num => $parameters ) {
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
			}

			if ( is_numeric( $num ) && $num <= $pageNumber ) {
				$params = explode( ';', $parameters );
				foreach ( $params as $param ) {
					if ( is_numeric( $param ) ) {
						$offset = $num - $param;
					}
				}
			}
		}

		$displayedpageNumber = ($displayedpageNumber === '') ? $pageNumber - $offset : $displayedpageNumber;
		return new PageNumber( $displayedpageNumber, $mode, $isEmpty );
	}

	/**
	 * Returns if a number is in a range as definded by <pagelist>
	 * Such range may be a single integer or something in the format XXtoYY
	 *
	 * @param $range
	 * @param $number
	 * @return boolean
	 */
	protected function numberInRange( $range, $number ) {
		return
			is_numeric( $range ) && $range == $number ||
			preg_match( '/^([0-9]*)to([0-9]*)$/', $range, $m ) && $m[1] <= $number && $number <= $m[2];
	}
}