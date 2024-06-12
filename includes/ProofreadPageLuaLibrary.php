<?php

namespace ProofreadPage;

use MediaWiki\Extension\Scribunto\Engines\LuaCommon\LibraryBase;
use MediaWiki\Extension\Scribunto\Engines\LuaCommon\LuaError;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Title\Title;
use OutOfBoundsException;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Page\PageLevel;
use ProofreadPage\Pagination\Pagination;
use Psr\Log\LoggerInterface;
use WikitextContent;

class ProofreadPageLuaLibrary extends LibraryBase {

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var ParserOutput|null
	 */
	private $parserOutput;

	/** @inheritDoc */
	public function register() {
		$this->context = Context::getDefaultContext();

		$extensionLuaPath = __DIR__ . '/lualib/ProofreadPage.lua';

		// "export" PHP functions to the Lua library interface
		$lib = [
			'doGetIndexProgress' => [ $this, 'doGetIndexProgress' ],
			'doGetIndexFields' => [ $this, 'doGetIndexFields' ],
			'doGetIndexCategories' => [ $this, 'doGetIndexCategories' ],
			'doGetNumberOfPages' => [ $this, 'doGetNumberOfPages' ],
			'doGetPageInIndex' => [ $this, 'doGetPageInIndex' ],
			'doGetIndexPageNumbers' => [ $this, 'doGetIndexPageNumbers' ],
			'doGetPageQuality' => [ $this, 'doGetPageQuality' ],
			'doGetIndexForPage' => [ $this, 'doGetIndexForPage' ],
			'doGetPageNumbering' => [ $this, 'doGetPageNumbering' ]
		];
		$opts = [
			'NS_INDEX' => $this->context->getIndexNamespaceId(),
			'NS_PAGE' => $this->context->getPageNamespaceId(),
			'qualityLevel' => [
				'WITHOUT_TEXT' => PageLevel::WITHOUT_TEXT,
				'NOT_PROOFREAD' => PageLevel::NOT_PROOFREAD,
				'PROBLEMATIC' => PageLevel::PROBLEMATIC,
				'PROOFREAD' => PageLevel::PROOFREAD,
				'VALIDATED' => PageLevel::VALIDATED,
			]
		];

		$this->logger = LoggerFactory::getInstance( 'ext.proofreadPage.lua' );

		if ( $this->getParser() ) {
			$this->parserOutput = $this->getParser()->getOutput();
		}

		return $this->getEngine()->registerInterface( $extensionLuaPath, $lib, $opts );
	}

	/**
	 * Increment the Lua engine expensive function count
	 */
	public function incrementExpensiveFunctionCount() {
		$this->getEngine()->incrementExpensiveFunctionCount();
	}

	/**
	 * Add a parser dependency on the given page (index or otherwise)
	 *
	 * @param Title|null $pageTitle
	 */
	private function addTemplateDependencyOnPage( ?Title $pageTitle ) {
		if ( $this->parserOutput && $pageTitle ) {
			$this->parserOutput->addTemplate(
				$pageTitle,
				$pageTitle->getArticleID(),
				$pageTitle->getLatestRevID()
			);
		}
	}

	/**
	 * Add a parser dependency on every page in the index (and the index itself)
	 *
	 * @param Title|null $indexTitle
	 */
	private function addTemplateDependencyOnAllPagesInIndex( ?Title $indexTitle ) {
		if ( $this->parserOutput && $indexTitle ) {
			// this depends on the index itself (for the content)
			$pagination = $this->getPaginationForIndex( $indexTitle );
			$pagination->prefetchPageLinks();

			foreach ( $pagination as $pageTitle ) {
				$this->addTemplateDependencyOnPage( $pageTitle );
			}
		}
	}

	/**
	 * Return the index statistics for the given index name
	 *
	 * This function may be expensive if the index has not yet been cached.
	 *
	 * @param string $indexName The index title to get stats for
	 * @return array The result table, in an array
	 * @throws LuaError If the expensive function count has been exceeded
	 */
	public function doGetIndexProgress( string $indexName ): array {
		$indexTitle = Title::makeTitleSafe( $this->context->getIndexNamespaceId(), $indexName );

		$statsLookup = $this->context->getIndexQualityStatsLookup();

		if ( !$statsLookup->isIndexTitleInCache( $indexTitle ) ) {
			$this->logger->debug( "Index stats cache miss: " . $indexTitle->getFullText() );
			$this->incrementExpensiveFunctionCount();
		}

		// Progress depends on every page in the index
		$this->addTemplateDependencyOnAllPagesInIndex( $indexTitle );

		$indexStats = $statsLookup->getStatsForIndexTitle( $indexTitle );

		// Map stats to the Lua table
		return [ [
			0 => $indexStats->getNumberOfPagesForQualityLevel( 0 ),
			1 => $indexStats->getNumberOfPagesForQualityLevel( 1 ),
			2 => $indexStats->getNumberOfPagesForQualityLevel( 2 ),
			3 => $indexStats->getNumberOfPagesForQualityLevel( 3 ),
			4 => $indexStats->getNumberOfPagesForQualityLevel( 4 ),
			"total" => $indexStats->getNumberOfPages(),
			"existing" => $indexStats->getNumberOfPagesWithAnyQualityLevel(),
			"missing" => $indexStats->getNumberOfPagesWithoutQualityLevel(),
		] ];
	}

	/**
	 * Get the IndexContent for a give index
	 * @param string $indexName the name of the index
	 * @return IndexContent|null the index content (or null if the index is
	 *  not found or the title construction fails)
	 */
	private function getIndexContent( string $indexName ): ?IndexContent {
		$indexTitle = Title::makeTitleSafe( $this->context->getIndexNamespaceId(), $indexName );
		$contentLookup = $this->context->getIndexContentLookup();

		if ( !$contentLookup->isIndexTitleInCache( $indexTitle ) ) {
			$this->logger->debug( "Index content cache miss: " . $indexTitle->getFullText() );
			$this->incrementExpensiveFunctionCount();
		}

		// if the index content is needed, there's a dependency on the index
		$this->addTemplateDependencyOnPage( $indexTitle );

		return $contentLookup->getIndexContentForTitle( $indexTitle );
	}

	/**
	 * Return the index fields for the given index name
	 *
	 * This function may be expensive if the index content has not yet been cached.
	 *
	 * @param string $indexName the index title to get stats for
	 * @return array the result table, in an array
	 * @throws LuaError If the expensive function count has been exceeded
	 */
	public function doGetIndexFields( string $indexName ): array {
		// this can be expensive
		$indexContent = $this->getIndexContent( $indexName );

		$textConverter = static function ( WikitextContent $field ): string {
			return $field->getText();
		};

		return [ array_map( $textConverter, $indexContent->getFields() ) ];
	}

	/**
	 * Return the index categories for the given index name
	 *
	 * Note this is only the categories entered on the index page, and doesn't
	 * include categories added by the Index page template or any other
	 * expansion of wikitext.
	 *
	 * This function may be expensive if the index content has not yet been cached.
	 *
	 * @param string $indexName The index title to get stats for
	 * @return array The result table, in an array
	 * @throws LuaError If the expensive function count has been exceeded
	 */
	public function doGetIndexCategories( string $indexName ): array {
		// this can be expensive
		$indexContent = $this->getIndexContent( $indexName );

		$textConverter = static function ( Title $field ): string {
			return $field->getText();
		};

		// remap into a Lua-esque 1-indexed array of title strings
		return [
			array_map( $textConverter, $indexContent->getCategories() )
		];
	}

	/**
	 * Get the Pagination for a given index
	 *
	 * Increments the expensive function counter if needed
	 *
	 * @param Title $indexTitle
	 * @return Pagination
	 */
	private function getPaginationForIndex( Title $indexTitle ) {
		$paginationFactory = $this->context->getPaginationFactory();

		if ( !$paginationFactory->isIndexTitleInCache( $indexTitle ) ) {
			$this->logger->debug( "Index pagination cache miss: " . $indexTitle->getFullText() );
			$this->incrementExpensiveFunctionCount();
		}

		// the pagination depends on the index content
		$this->addTemplateDependencyOnPage( $indexTitle );

		// maybe expensive, but cached
		return $paginationFactory->getPaginationForIndexTitle( $indexTitle );
	}

	/**
	 * Get the total number of pages in the index
	 *
	 * @param string $indexName The index title
	 * @return array The number of pages in the index, 0 for an invalid index
	 */
	public function doGetNumberOfPages( string $indexName ): array {
		$indexTitle = Title::makeTitleSafe( $this->context->getIndexNamespaceId(), $indexName );

		// maybe expensive
		$pagination = $this->getPaginationForIndex( $indexTitle );

		return [ $pagination->getNumberOfPages() ];
	}

	/**
	 * The n'th page in the pagination for an index
	 *
	 * @param string $indexName The index title
	 * @param int $n The index of the pag in that index (1 is the first)
	 * @return array The page title, as an array for Lua
	 */
	public function doGetPageInIndex( string $indexName, int $n ): array {
		$indexTitle = Title::makeTitleSafe( $this->context->getIndexNamespaceId(), $indexName );

		// maybe expensive
		$pagination = $this->getPaginationForIndex( $indexTitle );

		try {
			$pageTitle = $pagination->getPageTitle( $n );
		} catch ( OutOfBoundsException $e ) {
			return [ null ];
		}

		return [ $pageTitle->getText() ];
	}

	/**
	 * Get the quality information for a given page
	 *
	 * @param string $pageName The title of the page to get the info for
	 * @return array The quality information as an array
	 */
	public function doGetPageQuality( string $pageName ): array {
		$pageTitle = Title::makeTitleSafe( $this->context->getPageNamespaceId(), $pageName );
		$pqLookup = $this->context->getPageQualityLevelLookup();

		if ( !$pqLookup->isPageTitleInCache( $pageTitle ) ) {
			$this->logger->debug( "Page quality cache miss: " . $pageTitle->getFullText() );
			$this->incrementExpensiveFunctionCount();
		}

		// the page quality depends only on that page
		$this->addTemplateDependencyOnPage( $pageTitle );

		return [ [
			'level' => $pqLookup->getQualityLevelForPageTitle( $pageTitle ),
			// 'user' => $pageLevel->getUser(), // T289137
			// 'timestamp' maybe?
		] ];
	}

	/**
	 * Get the title of the index for a given page
	 *
	 * @param Title $pageTitle
	 * @return Title|null The title of the index
	 */
	private function getIndexForPage( Title $pageTitle ): ?Title {
		$ifpLookup = $this->context->getIndexForPageLookup();

		if ( !$ifpLookup->isPageTitleInCache( $pageTitle ) ) {
			$this->logger->debug( "Index for page cache miss: " . $pageTitle->getFullText() );
			$this->incrementExpensiveFunctionCount();
		}

		return $ifpLookup->getIndexForPageTitle( $pageTitle );
	}

	/**
	 * Get the title of the index for a given page
	 *
	 * @param string $pageName The title of the page to get the index for
	 * @return array The name of the index
	 */
	public function doGetIndexForPage( string $pageName ): array {
		$pageTitle = Title::makeTitleSafe( $this->context->getPageNamespaceId(), $pageName );
		$indexTitle = $this->getIndexForPage( $pageTitle );

		// if the page is moved, this could change, and also if the index pagination changes
		$this->addTemplateDependencyOnPage( $pageTitle );
		$this->addTemplateDependencyOnPage( $indexTitle );

		if ( $indexTitle == null ) {
			return [ null ];
		}

		return [ $indexTitle->getBaseText() ];
	}

	/**
	 * Get the page numbering for a given page
	 *
	 * @param string $pageName The title of the page to get the numbering for
	 * @return array The name of the index
	 */
	public function doGetPageNumbering( string $pageName ): array {
		$pageTitle = Title::makeTitleSafe( $this->context->getPageNamespaceId(), $pageName );

		// maybe expensive
		$indexTitle = $this->getIndexForPage( $pageTitle );

		if ( $indexTitle == null ) {
			return [ null ];
		}

		// this is a cached lookup, so we'll only look this indexes pagination up once,
		// but that first time is expensive
		$pagination = $this->getPaginationForIndex( $indexTitle );
		$language = $indexTitle->getPageLanguage();

		$pageInPagination = $pagination->getPageNumber( $pageTitle );
		$dispNum = $pagination->getDisplayedPageNumber( $pageInPagination );

		return [ [
			'position' => $pageInPagination,
			'display' => $dispNum->getFormattedPageNumber( $language ),
			'raw' => $dispNum->getRawPageNumber( $language )
		] ];
	}
}
