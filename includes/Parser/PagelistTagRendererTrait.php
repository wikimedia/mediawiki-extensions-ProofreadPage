<?php

namespace ProofreadPage\Parser;

use HtmlArmor;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageList;
use ProofreadPage\Pagination\SimpleFilePagination;
use ProofreadPage\ProofreadPage;
use Wikimedia\Parsoid\DOM\DocumentFragment;

/**
 * Trait for rendering pagelist tags.
 */
trait PagelistTagRendererTrait {
	/** @inheritDoc */
	abstract public function getTitle();

	/** @inheritDoc */
	abstract public function addTrackingCategory( $category ): void;

	/** @inheritDoc */
	abstract public function getPageNamespaceId(): int;

	/** @inheritDoc */
	abstract public function getIndexNamespaceId(): int;

	/** @inheritDoc */
	abstract public function getPageNumberExpression( $expression ): string;

	/** @inheritDoc */
	abstract public function addTemplate( $title, $articleId, $revId ): void;

	/** @inheritDoc */
	abstract public function makeLink( $title, $text, $options = [] ): string;

	/** @inheritDoc */
	abstract public function addImage( $title, $timestamp, $sha1 ): void;

	/**
	 * @param string|array $output
	 * @return string|DocumentFragment
	 */
	abstract public function renderOutput( $output ): string|DocumentFragment;

	/**
	 * Render the pagelist tag.
	 *
	 * @param Context $context The ProofreadPage context
	 * @param array $args Tag arguments
	 * @return string|DocumentFragment
	 * @throws ParserError
	 */
	public function renderTag( $context, $args ): string|DocumentFragment {
		$title = $this->getTitle();
		if ( !$title->inNamespace( $context->getIndexNamespaceId() ) ) {
			throw new ParserError( 'proofreadpage_pagelistnotallowed' );
		}
		$pageList = new PageList( $args );
		try {
			$image = $context->getFileProvider()->getFileForIndexTitle( $title );
		} catch ( FileNotFoundException ) {
			$this->addTrackingCategory( 'proofreadpage_nosuch_file_for_index_category' );
			throw new ParserError( 'proofreadpage_nosuch_file' );
		}

		if ( $image->isMultipage() ) {
			$pagination = new FilePagination(
				$title,
				$pageList,
				$image->pageCount(),
				$this->getPageNamespaceId()
			);
			$pagination->prefetchPageLinks();
		} else {
			$pagination = new SimpleFilePagination( $title, $pageList, $this->getPageNamespaceId() );
		}
		$count = $pagination->getNumberOfPages();

		$from = $args['from'] ?? 1;
		$to = $args['to'] ?? $count;
		if ( !is_int( $from ) ) {
			if ( !ctype_digit( $from ) ) {
				throw new ParserError( 'proofreadpage_number_expected' );
			}
			$from = (int)$from;
		}
		if ( !is_int( $to ) ) {
			if ( !ctype_digit( $to ) ) {
				throw new ParserError( 'proofreadpage_number_expected' );
			}
			$to = (int)$to;
		}
		if ( !FilePagination::isValidInterval( $from, $to, $count ) ) {
			throw new ParserError( 'proofreadpage_invalid_interval' );
		}
		$return = [];
		for ( $i = $from; $i <= $to; $i++ ) {
			$pageNumber = $pagination->getDisplayedPageNumber( $i );
			$pageNumberExpression = $pageNumber->getFormattedPageNumber( $title->getPageLanguage() );
			if ( !preg_match( '/^[\p{L}\p{N}\p{Mc}]+$/', $pageNumberExpression ) ) {
				$pageNumberExpression = $this->getPageNumberExpression( $pageNumberExpression );
			}

			$pageTitle = $pagination->getPageTitle( $i );
			if ( !$pageNumber->isEmpty() ) {
				// Adds the page as a dependency in order to make sure that the Index: page is
				// purged if the status of the Page: page changes
				$this->addTemplate(
					$pageTitle,
					$pageTitle->getArticleID(),
					$pageTitle->getLatestRevID()
				);

				$qualityClass = ProofreadPage::getQualityLevelClassesForTitle( $pageTitle, true );
				$pageNumberExpression = $this->makeLink(
					$pageTitle,
					new HtmlArmor( $pageNumberExpression ),
					[ 'class' => 'prp-index-pagelist-page ' . $qualityClass ]
				);
			}
			$return[] = $pageNumberExpression;
		}

		$this->addImage(
			$image->getTitle()->getDBkey(), $image->getTimestamp(), $image->getSha1()
		);

		return $this->renderOutput( $return );
	}
}
