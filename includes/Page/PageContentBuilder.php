<?php

namespace ProofreadPage\Page;

use IContextSource;
use OutOfBoundsException;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\PageNumberNotFoundException;
use ProofreadPage\Pagination\PageNotInPaginationException;
use Title;
use WikitextContent;

/**
 * @license GPL-2.0-or-later
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
	 * @param Title $pageTitle
	 * @return PageContent
	 */
	public function buildDefaultContentForPageTitle( Title $pageTitle ) {
		$indexTitle = $this->context->getIndexForPageLookup()->getIndexForPageTitle( $pageTitle );
		$body = '';

		// default header and footer
		if ( $indexTitle !== null ) {
			$params = [];
			try {
				$pagination = $this->context->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );
				$pageNumber = $pagination->getPageNumber( $pageTitle );
				$displayedPageNumber = $pagination->getDisplayedPageNumber( $pageNumber );
				$params['pagenum'] = $displayedPageNumber
					->getFormattedPageNumber( $pageTitle->getPageLanguage() );
			} catch ( PageNotInPaginationException $e ) {
			} catch ( OutOfBoundsException $e ) {
			} // should not happen

			$indexFieldsParser = $this->context->getCustomIndexFieldsParser();
			$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
			$header = $indexFieldsParser->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries(
				$indexContent, 'header', $params
			);
			$footer = $indexFieldsParser->parseCustomIndexFieldWithVariablesReplacedWithIndexEntries(
				$indexContent, 'footer', $params
			);
		} else {
			$header = $this->contextSource->msg( 'proofreadpage_default_header' )
				->inContentLanguage()->plain();
			$footer = $this->contextSource->msg( 'proofreadpage_default_footer' )
				->inContentLanguage()->plain();
		}

		// Extract text layer
		try {
			$fileProvider = $this->context->getFileProvider();
			$image = $fileProvider->getFileForPageTitle( $pageTitle );
			if ( $image->exists() ) {
				$pageNumber = 1;
				if ( $image->isMultipage() ) {
					try {
						$pageNumber = $fileProvider->getPageNumberForPageTitle( $pageTitle );
					} catch ( PageNumberNotFoundException $e ) {
					}
				}
				$text = $image->getHandler()
					? $image->getHandler()->getPageText( $image, $pageNumber )
					: '';
				if ( $text ) {
					$text = preg_replace( "/(\\\\n)/", "\n", $text );
					$body = preg_replace( "/(\\\\\d*)/", '', $text );
				}
			}
		} catch ( FileNotFoundException $e ) {
		}

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
	 * @param int $level
	 * @param PageContent $oldContent the old content used as base for the new content
	 * @return PageContent
	 */
	public function buildContentFromInput(
		$header, $body, $footer, $level, PageContent $oldContent
	) {
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
