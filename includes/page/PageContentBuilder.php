<?php

namespace ProofreadPage\Page;

use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\FileProvider;
use ProofreadPagePage;
use ProofreadPageLevel;
use WikitextContent;
use ProofreadPageContent;
use IContextSource;

/**
 * @licence GNU GPL v2+
 *
 * Utility class to build a Page: page content
 */
class PageContentBuilder {

	/**
	 * @var IContextSource
	 */
	private $context;

	/**
	 * @var FileProvider
	 */
	private $fileProvider;

	/**
	 * @param IContextSource $context
	 * @param Context $context
	 */
	public function __construct( IContextSource $contextSource, Context $context ) {
		$this->context = $contextSource;
		$this->fileProvider = $context->getFileProvider();
	}

	/**
	 * @param ProofreadPagePage $page
	 * @return ProofreadPageContent
	 */
	public function buildDefaultContentForPage( ProofreadPagePage $page ) {
		$index = $page->getIndex();
		$body = '';

		//default header and footer
		if ( $index ) {
			$params = array(
				'pagenum' => $index->getDisplayedPageNumber( $page->getTitle() )
			);
			$header = $index->replaceVariablesWithIndexEntries( 'header', $params );
			$footer = $index->replaceVariablesWithIndexEntries( 'footer', $params );
		} else {
			$header = $this->context->msg( 'proofreadpage_default_header' )->inContentLanguage()->plain();
			$footer = $this->context->msg( 'proofreadpage_default_footer' )->inContentLanguage()->plain();
		}

		//Extract text layer
		try {
			$image = $this->fileProvider->getForPagePage( $page );
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

		return new ProofreadPageContent(
			new WikitextContent( $header ),
			new WikitextContent( $body ),
			new WikitextContent( $footer ),
			new ProofreadPageLevel()
		);
	}

	/**
	 * @param string $header
	 * @param string $body
	 * @param string $footer
	 * @param integer $level
	 * @param ProofreadPageContent $oldContent the old content used as base for the new content
	 */
	public function buildContentFromInput( $header, $body, $footer, $level, ProofreadPageContent $oldContent ) {
		$oldLevel = $oldContent->getLevel();
		$user = ( $oldLevel->getLevel() === $level )
			? $oldLevel->getUser()
			: $this->context->getUser();
		if ( $oldLevel->getUser() === null ) {
			$user = $this->context->getUser();
		}

		return new ProofreadPageContent(
			new WikitextContent( $header ),
			new WikitextContent( $body ),
			new WikitextContent( $footer ),
			new ProofreadPageLevel( $level, $user )
		);
	}
}