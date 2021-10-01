<?php

namespace ProofreadPage\Parser;

use Html;
use Parser;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\PageNumber;
use ProofreadPage\Pagination\SimpleFilePagination;

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
			return $this->formatError( 'proofreadpage_pagelistnotallowed' );
		}
		$pageList = new PageList( $args );
		try {
			$image = $this->context->getFileProvider()->getFileForIndexTitle( $title );
		} catch ( FileNotFoundException $e ) {
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}

		if ( $image->isMultipage() ) {
			$pagination = new FilePagination(
				$title,
				$pageList,
				$image->pageCount(),
				$this->context->getPageNamespaceId()
			);
		} else {
			$pagination = new SimpleFilePagination( $title, $pageList, $this->context->getPageNamespaceId() );
		}
		$count = $pagination->getNumberOfPages();

		$return = '';
		$from = $args['from'] ?? 1;
		$to = $args['to'] ?? $count;
		if ( !is_int( $from ) ) {
			if ( !ctype_digit( $from ) ) {
				return $this->formatError( 'proofreadpage_number_expected' );
			}
			$from = (int)$from;
		}
		if ( !is_int( $to ) ) {
			if ( !ctype_digit( $to ) ) {
				return $this->formatError( 'proofreadpage_number_expected' );
			}
			$to = (int)$to;
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
				$pad = $title->getPageLanguage()->formatNumNoSeparators( 0 );
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
