<?php

namespace ProofreadPage\Api;

use ApiBase;
use ApiPageSet;
use ApiQuery;
use ApiQueryGeneratorBase;
use LogicException;
use ProofreadPage\Context;
use ProofreadPage\Pagination\Pagination;
use Title;

/**
 * API list module for getting the list of pages in an index
 */
class ApiQueryPagesInIndex extends ApiQueryGeneratorBase {

	/**
	 * @var Context
	 */
	private $context;

	/** @var string API module prefix */
	private static $prefix = 'prppii';

	/**
	 * @inheritDoc
	 */
	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, static::$prefix );
		$this->context = Context::getDefaultContext();
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		try {
			$this->run();
		} catch ( LogicException $e ) {
			$this->dieWithException( $e );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function executeGenerator( $resultPageSet ) {
		try {
			$this->run( $resultPageSet );
		} catch ( LogicException $e ) {
			$this->dieWithException( $e );
		}
	}

	/**
	 * Main API logic for gathering the pages in an index
	 * @param ApiPageSet|null $resultPageSet
	 */
	private function run( ?ApiPageSet $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		// handles 'missingparam' error if needed
		$indexTitle = $this->getTitleOrPageId( $params )->getTitle();

		if ( $indexTitle->getNamespace() !== $this->context->getIndexNamespaceId() ) {
			$this->dieWithError(
				[ 'apierror-proofreadpage-invalidindex', $indexTitle->getFullText() ] );
		}

		$limit = $params['limit'];
		$from = intval( $params['continue'] );
		$prop = array_fill_keys( $params['prop'], true );

		$result = $this->getResult();
		$pages = [];

		$pagination = $this->context->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );

		if ( isset( $prop[ 'ids' ] ) ) {
			// We prefetch the page ids
			$pagination->prefetchPageLinks();
		}

		$result->addValue( [ 'query' ], $this->getModuleName(), [] );

		foreach ( $pagination as $key => $pageTitle ) {

			// Skip ahead until we find the region of interest
			if ( $key < $from ) {
				continue;
			}

			if ( count( $pages ) >= $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'from', $pagination->getPageNumber( $pageTitle ) );
				break;
			}

			$pages[] = $pageTitle;

			if ( $resultPageSet === null ) {
				// Not a generator, build the result array
				$pageInfo = $this->getPageInfo( $pageTitle, $pagination, $prop );

				$fits = $result->addValue( [ 'query', $this->getModuleName() ],
					null, $pageInfo );

				if ( !$fits ) {
					$this->setContinueEnumParameter( 'from', $pagination->getPageNumber( $pageTitle ) );
					break;
				}
			}
		}

		// for generators
		if ( $resultPageSet !== null ) {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	/**
	 * Get returned page info for the query result
	 * @param Title $pageTitle the title of the page in the pagination
	 * @param Pagination $pagination the index pagination
	 * @param array $prop properties array
	 * @return array array of the info
	 */
	private function getPageInfo( Title $pageTitle, $pagination, array $prop ) {
		// The index of the page within the index - vital for continuation
		$pageInfo = [
			'pageoffset' => $pagination->getPageNumber( $pageTitle )
		];

		if ( isset( $prop[ 'ids' ] ) ) {
			$pageInfo[ 'pageid' ] = $pageTitle->getId();
		}

		if ( isset( $prop[ 'title' ] ) ) {
			$pageInfo[ 'title' ] = $pageTitle->getFullText();
		}

		return $pageInfo;
	}

	/**
	 * @inheritDoc
	 */
	protected function getAllowedParams() {
		return [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
				ApiBase::PARAM_TYPE => 'integer',
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title',
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'title' => [
				ApiBase::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 100,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getHelpUrls() {
		return [
			'https://www.mediawiki.org/wiki/Special:MyLanguage/Extension:ProofreadPage/Index pagination API',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getExamplesMessages() {
		$prefix = static::$prefix;
		return [
			"action=query&list=proofreadpagesinindex&${prefix}title=Index:Sandbox.djvu"
				=> 'apihelp-query+proofreadpagesinindex-example-1',
		];
	}
}