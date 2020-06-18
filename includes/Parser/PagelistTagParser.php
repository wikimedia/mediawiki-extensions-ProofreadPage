<?php

namespace ProofreadPage\Parser;

use Html;
use Parser;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\PageNumber;

/**
 * @license GPL-2.0-or-later
 *
 * Parser for the <pagelist> tag
 */
class PagelistTagParser {

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @param Parser $parser
	 * @param Context $context
	 */
	public function __construct( Parser $parser, Context $context ) {
		$this->parser = $parser;
		$this->context = $context;
	}

	/**
	 * Render a <pagelist> tag
	 *
	 * @param array $args tags arguments
	 * @return string
	 */
	public function render( array $args ) {
		$title = $this->parser->getTitle();
		if ( !$title->inNamespace( $this->context->getIndexNamespaceId() ) ) {
			return '';
		}
		$pageList = new PageList( $args );
		try {
			$image = $this->context->getFileProvider()->getFileForIndexTitle( $title );
		} catch ( FileNotFoundException $e ) {
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}
		if ( !$image->isMultipage() ) {
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}

		$pagination = new FilePagination( $title, $pageList, $image, $this->context );
		$count = $pagination->getNumberOfPages();

		$return = '';
		$from = $args['from'] ?? 1;
		$to = $args['to'] ?? $count;

		if ( !is_numeric( $from ) || !is_numeric( $to ) ) {
			return $this->formatError( 'proofreadpage_number_expected' );
		}
		if ( !FilePagination::isValidInterval( $from, $to, $count ) ) {
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

			$paddingSize = strlen( (string)$count ) - mb_strlen( $view );
			if ( $paddingSize > 0 && $mode === PageNumber::DISPLAY_NORMAL &&
				$pageNumber->isNumeric()
			) {
				$txt = '<span style="visibility:hidden;">';
				$pad = $title->getPageLanguage()->formatNum( 0, true );
				for ( $j = 0; $j < $paddingSize; $j++ ) {
					$txt .= $pad;
				}
				$view = $txt . '</span>' . $view;
			}
			$pageTitle = $pagination->getPageTitle( $i );

			if ( $pageNumber->isEmpty() || !$title ) {
				$return .= $view . ' ';
			} else {
				// Adds the page as a dependency in order to make sure that the Index: page is
				// purged if the status of the Page: page changes
				$this->parser->getOutput()->addTemplate(
					$pageTitle,
					$pageTitle->getArticleID(),
					$pageTitle->getLatestRevID()
				);
				// TODO: use linker?
				$return .= '[[' . $pageTitle->getPrefixedText() . '|' . $view . ']] ';
			}
		}

		$this->parser->getOutput()->addImage(
			$image->getTitle()->getDBkey(), $image->getTimestamp(), $image->getSha1()
		);

		return Html::rawElement( 'span',
			[ 'class' => 'prp-index-pagelist' ],
			trim( $this->parser->recursiveTagParse( $return ) ) );
	}

	/**
	 * @param string $errorMsg
	 * @return string
	 */
	private function formatError( $errorMsg ) {
		$error = Html::element( 'strong',
				[ 'class' => 'error' ],
				wfMessage( $errorMsg )->inContentLanguage()->text()
			);
		return Html::rawElement( 'span',
			[ 'class' => 'prp-index-pagelist' ],
			$error
		);
	}
}
