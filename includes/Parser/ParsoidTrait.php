<?php

namespace ProofreadPage\Parser;

use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;
use ProofreadPage\Context;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;

/**
 * Parsoid implementation helper
 */
trait ParsoidTrait {
	private LinkRenderer $linkRenderer;
	private ParsoidExtensionAPI $extApi;
	private Context $context;
	private Language $language;

	/**
	 * Get the title of the current page.
	 *
	 * @return Title The page title
	 */
	public function getTitle(): Title {
		$linkTarget = $this->extApi->getPageConfig()->getLinkTarget();
		return Title::newFromLinkTarget( $linkTarget );
	}

	/**
	 * Add a tracking category to the page.
	 *
	 * @param string $category The category name
	 */
	public function addTrackingCategory( $category ): void {
		$this->extApi->addTrackingCategory( $category );
	}

	/**
	 * Get the namespace ID for pages.
	 *
	 * @return int The page namespace ID
	 */
	public function getPageNamespaceId(): int {
		return $this->context->getPageNamespaceId();
	}

	/**
	 * Get the namespace ID for index pages.
	 *
	 * @return int The index namespace ID
	 */
	public function getIndexNamespaceId(): int {
		return $this->context->getIndexNamespaceId();
	}

	/**
	 * Process a page number expression.
	 *
	 * @param string $expression The expression to process
	 * @return string The processed expression
	 */
	public function getPageNumberExpression( $expression ): string {
		$pageNumberExpression = $this->extApi->wikitextToDOM( $expression, [
			'parseOpts' => [ 'context' => 'inline' ]
		], true );
		return $this->extApi->domToHtml( $pageNumberExpression, true );
	}

	/**
	 * Add a template dependency to the parser output.
	 *
	 * @param Title $title Template title
	 * @param int $articleId Article ID
	 * @param int $revId Revision ID
	 */
	public function addTemplate( $title, $articleId, $revId ): void {
		$metadata = $this->extApi->getMetadata();
		if ( $metadata instanceof ParserOutput ) {
			// T296038/T357048: this will be moved to the Parsoid API eventually
			$metadata->addTemplate(
				$title,
				$title->getArticleID(),
				$title->getLatestRevID()
			);
		}
	}

	/**
	 * Create a link to a page.
	 *
	 * @param Title $title Target title
	 * @param string|null $text Link text
	 * @param array $options Link options
	 * @return string The rendered link
	 */
	public function makeLink( $title, $text, $options = [] ): string {
		return $this->linkRenderer->makeLink( $title, $text, $options );
	}

	/**
	 * Add an image dependency to the parser output.
	 *
	 * @param string $title Image title
	 * @param string|false $timestamp Image timestamp
	 * @param string|false $sha1 Image SHA1 hash
	 */
	public function addImage( $title, $timestamp, $sha1 ): void {
		$metadata = $this->extApi->getMetadata();
		if ( $metadata instanceof ParserOutput ) {
			$metadata->addImage(
				$title,
				$timestamp,
				$sha1
			);
		}
	}

	/**
	 * Set extension data in the Parsoid output.
	 *
	 * @param string $key The data key
	 * @param mixed $value The data value
	 */
	public function setExtensionData( $key, $value ): void {
		$metadata = $this->extApi->getMetadata();
		if ( $metadata instanceof ParserOutput ) {
			$metadata->setExtensionData( $key, $value );
		}
	}
}
