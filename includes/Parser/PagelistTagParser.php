<?php

namespace ProofreadPage\Parser;

use ProofreadIndexPage;
use ProofreadPage;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\PageNumber;

/**
 * @licence GNU GPL v2+
 *
 * Parser for the <pagelist> tag
 */
class PagelistTagParser extends TagParser {

	/**
	 * @see TagParser::render
	 */
	public function render( $input, array $args ) {
		$title = $this->parser->getTitle();
		if ( !$title->inNamespace( $this->context->getIndexNamespaceId() ) ) {
			return '';
		}
		$index = ProofreadIndexPage::newFromTitle( $title );
		$pageList = new PageList( $args );
		try {
			$image = $this->context->getFileProvider()->getForIndexPage( $index );
		} catch( FileNotFoundException $e ) {
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}
		if( !$image->isMultipage() ) {
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}

		$pagination = new FilePagination( $index, $pageList, $image, $this->context );
		$count = $pagination->getNumberOfPages();

		$return = '';
		$from = array_key_exists( 'from', $args ) ? $args['from'] : 1;
		$to = array_key_exists( 'to', $args ) ? $args['to'] : $count;

		if( !is_numeric( $from ) || !is_numeric( $to ) ) {
			return $this->formatError( 'proofreadpage_number_expected' );
		}
		if( ( $from > $to ) || ( $from < 1 ) || ( $to < 1 ) || ( $to > $count ) ) {
			return $this->formatError( 'proofreadpage_invalid_interval' );
		}

		for ( $i = $from; $i <= $to; $i++ ) {
			$pageNumber = $pagination->getDisplayedPageNumber( $i );
			$mode = $pageNumber->getDisplayMode();
			$view = $pageNumber->getFormattedPageNumber( $title->getPageLanguage() );

			if (
				$mode === PageNumber::DISPLAY_HIGHROMAN ||
				$mode === PageNumber::DISPLAY_ROMAN
			) {
				$view = '&#160;' . $view;
			}

			$paddingSize = strlen( $count ) - mb_strlen( $view );
			if ( $paddingSize > 0 && $mode == PageNumber::DISPLAY_NORMAL && $pageNumber->isNumeric() ) {
				$txt = '<span style="visibility:hidden;">';
				$pad = $title->getPageLanguage()->formatNum( 0, true );
				for ( $j = 0; $j < $paddingSize; $j++ ) {
					$txt .= $pad;
				}
				$view = $txt . '</span>' . $view;
			}
			$pageTitle = $pagination->getPage( $i )->getTitle();

			if ( $pageNumber->isEmpty() || !$title ) {
				$return .= $view . ' ';
			} else {
				$return .= '[[' . $pageTitle->getPrefixedText() . '|' . $view . ']] '; //TODO: use linker?
			}
		}

		return trim( $this->parser->recursiveTagParse( $return ) );
	}
}
