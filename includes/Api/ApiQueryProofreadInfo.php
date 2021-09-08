<?php

namespace ProofreadPage\Api;

use ApiBase;
use ApiQueryBase;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
 *
 * A query action to return meta information about the proofread extension.
 *
 * Note: this used to have the prefix 'pi', which conflicted with PageImages
 * (T290585), so it was changed to 'prpi'.
 */
class ApiQueryProofreadInfo extends ApiQueryBase {

	/**
	 * @param \ApiQuery $query
	 * @param string $moduleName
	 */
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'prpi' );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		if ( isset( $prop['namespaces'] ) ) {
			$this->appendNamespaces();
		}

		if ( isset( $prop['qualitylevels'] ) ) {
			$this->appendQualityLevels();
		}
	}

	protected function appendNamespaces() {
		$context = Context::getDefaultContext();
		$data = [
			'index' => [
				'id' => $context->getIndexNamespaceId()
			],
			'page' => [
				'id' => $context->getPageNamespaceId()
			]
		];
		return $this->getResult()->addValue( 'query', 'proofreadnamespaces', $data );
	}

	protected function appendQualityLevels() {
		$data = [];
		for ( $i = 0; $i < 5; $i++ ) {
			$data[$i] = [
				'id' => $i,
				'category' => $this->getQualityLevelCategory( $i )
			];
		}
		$this->getResult()->setIndexedTagName( $data, 'level' );
		return $this->getResult()->addValue( 'query', 'proofreadqualitylevels', $data );
	}

	/**
	 * Get the category name (in the wiki's content language, and without 'Category:' prefix)
	 * for a given quality level.
	 * @param int $level
	 * @return string
	 */
	private function getQualityLevelCategory( $level ) {
		$messageName = "proofreadpage_quality{$level}_category";
		return $this->msg( $messageName )->inContentLanguage()->text();
	}

	/**
	 * @param array $params [optional] Parameters (unused parameter)
	 * @see ApiQueryBase::getCacheMode()
	 * @return string
	 */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * Returns list of allowed parameters
	 * @see ApiBase::getAllowedParams()
	 * @return array
	 */
	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_DFLT => 'namespaces|qualitylevels',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					'namespaces',
					'qualitylevels',
				]
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getExamplesMessages() {
		return [
			'action=query&meta=proofreadinfo'
				=> 'apihelp-query+proofreadinfo-example-1',
			'action=query&meta=proofreadinfo&prpiprop=namespaces'
				=> 'apihelp-query+proofreadinfo-example-3',
		];
	}
}
