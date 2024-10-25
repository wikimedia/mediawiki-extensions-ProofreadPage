<?php

namespace ProofreadPage\Api;

use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryBase;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use ProofreadPage\Context;
use ProofreadPage\Page\PageContentBuilder;
use ProofreadPage\Page\PageContentHandler;

class ApiQueryDefaultContentForPage extends ApiQueryBase {
	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var PageContentBuilder
	 */
	private $pageContentBuilder;

	/**
	 * @var PageContentHandler
	 */
	private $pageContentHandler;

	/** @var string API module prefix */
	private static $prefix = 'prppdefaultcontent';

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, static::$prefix );
		$this->context = Context::getDefaultContext();
		$this->pageContentBuilder = new PageContentBuilder( $this, $this->context );
		$this->pageContentHandler = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_PROOFREAD_PAGE );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$pageSet = $this->getPageSet()->getGoodAndMissingPages();
		$result = $this->getResult();

		foreach ( $pageSet as $pageID => $page ) {

			if ( $page->getNamespace() !== $this->context->getPageNamespaceId() ) {
				continue;
			}

			$title = Title::castFromPageIdentity( $page );

			if ( !$title ) {
				continue;
			}

			$result->addValue( [ 'query', 'pages', $pageID ], 'defaultcontentforpage',
				$this->pageContentHandler->serializeContent(
					$this->pageContentBuilder->buildDefaultContentForPageTitle( $title ),
					CONTENT_FORMAT_WIKITEXT
				)
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function getAllowedParams() {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function isInternal() {
		return true;
	}
}
