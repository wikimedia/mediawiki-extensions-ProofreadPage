<?php

namespace ProofreadPage\Page;

use IContextSource;
use OutOfBoundsException;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Pagination\PageNotInPaginationException;
use ProofreadPagePage;
use WikitextContent;

/**
 * @licence GNU GPL v2+
 *
 * Utility class to build a Page: page content
 */
class PageContentBuilder {

	/**
	 * @var IContextSource
	 */
	private $contextSource;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @param IContextSource $contextSource
	 * @param Context $context
	 */
	public function __construct( IContextSource $contextSource, Context $context ) {
		$this->contextSource = $contextSource;
		$this->context = $context;
	}

	/**
	 * @param ProofreadPagePage $page
	 * @return PageContent
	 */
	public function buildDefaultContentForPage( ProofreadPagePage $page ) {
		$index = $page->getIndex();
		$body = '';

		//default header and footer
		if ( $index ) {
			$params = array();
			try {
				$pagination = $this->context->getPaginationFactory()->getPaginationForIndexPage( $index );
				$pageNumber = $pagination->getPageNumber( $page );
				$displayedPageNumber = $pagination->getDisplayedPageNumber( $pageNumber );
				$params['pagenum'] = $displayedPageNumber->getFormattedPageNumber( $page->getTitle()->getPageLanguage() );
			} catch( PageNotInPaginationException $e ) {
			} catch( OutOfBoundsException $e ) {} //should not happen

			$header = $index->replaceVariablesWithIndexEntries( 'header', $params );
			$footer = $index->replaceVariablesWithIndexEntries( 'footer', $params );
		} else {
			$header = $this->contextSource->msg( 'proofreadpage_default_header' )->inContentLanguage()->plain();
			$footer = $this->contextSource->msg( 'proofreadpage_default_footer' )->inContentLanguage()->plain();
		}

		//Extract text layer
		try {
			$image = $this->context->getFileProvider()->getForPagePage( $page );
			$pageNumber = $page->getPageNumber();
			if ( $image->exists() ) {
				if ( $pageNumber !== null && $image->isMultipage() ) {
					$text = $image->getHandler()->getPageText( $image, $pageNumber );
				} else {
					$text = $image->getHandler()->getPageText( $image, 1 );
				}
				if ( $text ) {
					$text = preg_replace( "/(\\\\n)/", "\n", $text );
					$body = preg_replace( "/(\\\\\d*)/", '', $text );
				}
			}
		} catch( FileNotFoundException $e ) {}

		return new PageContent(
			new WikitextContent( $header ),
			new WikitextContent( $body ),
			new WikitextContent( $footer ),
			new PageLevel()
		);
	}

	/**
	 * @param string $header
	 * @param string $body
	 * @param string $footer
	 * @param integer $level
	 * @param PageContent $oldContent the old content used as base for the new content
	 * @return PageContent
	 */
	public function buildContentFromInput( $header, $body, $footer, $level, PageContent $oldContent ) {
		$oldLevel = $oldContent->getLevel();
		$user = ( $oldLevel->getLevel() === $level )
			? $oldLevel->getUser()
			: $this->contextSource->getUser();
		if ( $oldLevel->getUser() === null ) {
			$user = $this->contextSource->getUser();
		}

		return new PageContent(
			new WikitextContent( $header ),
			new WikitextContent( $body ),
			new WikitextContent( $footer ),
			new PageLevel( $level, $user )
		);
	}
}