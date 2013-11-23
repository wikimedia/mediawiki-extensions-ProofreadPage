<?php

namespace ProofreadPage\Parser;

use ProofreadPage;
use Title;

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
		if ( !$title->inNamespace( ProofreadPage::getIndexNamespaceId() ) ) {
			return '';
		}
		$imageTitle = Title::makeTitleSafe( NS_IMAGE, $title->getText() );
		if ( !$imageTitle ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
		}

		$image = wfFindFile( $imageTitle );
		if ( !( $image && $image->isMultipage() && $image->pageCount() ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
		}

		$return = '';

		$name = $imageTitle->getDBkey();
		$count = $image->pageCount();

		$from = array_key_exists( 'from', $args ) ? $args['from'] : 1;
		$to = array_key_exists( 'to', $args ) ? $args['to'] : $count;

		if( !is_numeric( $from ) || !is_numeric( $to ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_number_expected' )->inContentLanguage()->escaped() . '</strong>';
		}
		if( ( $from > $to ) || ( $from < 1 ) || ( $to < 1 ) || ( $to > $count ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
		}

		for ( $i = $from; $i < $to + 1; $i++ ) {
			list( $view, $links, $mode ) = ProofreadPage::pageNumber( $i, $args );

			if ( $mode == 'highroman' || $mode == 'roman' ) {
				$view = '&#160;' . $view;
			}

			$n = strlen( $count ) - mb_strlen( $view );
			$language = $this->parser->getTargetLanguage();
			if ( $n && ( $mode == 'normal' || $mode == 'empty' ) ) {
				$txt = '<span style="visibility:hidden;">';
				$pad = $language->formatNum( 0, true );
				for ( $j = 0; $j < $n; $j++ ) {
					$txt = $txt . $pad;
				}
				$view = $txt . '</span>' . $view;
			}
			$title = ProofreadPage::getPageTitle( $name, $i );

			if ( !$links || !$title ) {
				$return .= $view . ' ';
			} else {
				$return .= '[[' . $title->getPrefixedText() . '|' . $view . ']] ';
			}
		}
		$return = $this->parser->recursiveTagParse( $return );
		return $return;
	}
}
