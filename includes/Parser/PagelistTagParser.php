<?php

namespace ProofreadPage\Parser;

use HtmlArmor;
use MediaWiki\Html\Html;
use MediaWiki\Parser\Parser;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\SimpleFilePagination;
use ProofreadPage\ProofreadPage;

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
			$this->parser->addTrackingCategory( 'proofreadpage_nosuch_file_for_index_category' );
			return $this->formatError( 'proofreadpage_nosuch_file' );
		}

		if ( $image->isMultipage() ) {
			$pagination = new FilePagination(
				$title,
				$pageList,
				$image->pageCount(),
				$this->context->getPageNamespaceId()
			);
			$pagination->prefetchPageLinks();
		} else {
			$pagination = new SimpleFilePagination( $title, $pageList, $this->context->getPageNamespaceId() );
		}
		$count = $pagination->getNumberOfPages();

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
		$return = [];
		for ( $i = $from; $i <= $to; $i++ ) {
			$pageNumber = $pagination->getDisplayedPageNumber( $i );
			$pageNumberExpression = $pageNumber->getFormattedPageNumber( $title->getPageLanguage() );
			if ( !preg_match( '/^[\p{L}\p{N}\p{Mc}]+$/', $pageNumberExpression ) ) {
				$pageNumberExpression = $this->parser->recursiveTagParse( $pageNumberExpression );
			}

			$pageTitle = $pagination->getPageTitle( $i );
			if ( !$pageNumber->isEmpty() ) {
				// Adds the page as a dependency in order to make sure that the Index: page is
				// purged if the status of the Page: page changes
				$this->parser->getOutput()->addTemplate(
					$pageTitle,
					$pageTitle->getArticleID(),
					$pageTitle->getLatestRevID()
				);

				$qualityClass = ProofreadPage::getQualityLevelClassesForTitle( $pageTitle, true );
				$linkRenderer = $this->parser->getLinkRenderer();
				$pageNumberExpression = $linkRenderer->makeLink(
					$pageTitle,
					new HtmlArmor( $pageNumberExpression ),
					[ 'class' => 'prp-index-pagelist-page ' . $qualityClass ]
				);
			}
			$return[] = $pageNumberExpression;
		}

		$this->parser->getOutput()->addImage(
			$image->getTitle()->getDBkey(), $image->getTimestamp(), $image->getSha1()
		);

		return Html::rawElement(
			'div',
			[ 'class' => 'prp-index-pagelist' ],
			implode( ' ', $return )
		);
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
