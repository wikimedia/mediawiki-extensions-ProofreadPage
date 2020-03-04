<?php

namespace ProofreadPage\Api;

use ApiQueryBase;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
 */
class ApiQueryProofread extends ApiQueryBase {
	private $qualityLevelCategoryCache = [];

	public function execute() {
		$context = Context::getDefaultContext();
		$pages = $this->getPageSet()->getGoodTitles();
		$result = $this->getResult();

		$pageNamespaceId = $context->getPageNamespaceId();
		$pageQualityLevelLookup = $context->getPageQualityLevelLookup();
		$pageQualityLevelLookup->prefetchQualityLevelForTitles( $pages );

		foreach ( $pages as $pageId => $title ) {
			if ( $title->inNamespace( $pageNamespaceId ) ) {
				$pageQualityLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $title );
				if ( $pageQualityLevel === null ) {
					continue;
				}

				$val = [
					'quality' => $pageQualityLevel,
					'quality_text' => $this->getQualityLevelCategory( $pageQualityLevel )
				];
				$result->addValue( [ 'query', 'pages', $pageId ], 'proofread', $val );
			}
		}
	}

	/**
	 * @param int $level
	 * @return string
	 */
	private function getQualityLevelCategory( $level ) {
		if ( !array_key_exists( $level, $this->qualityLevelCategoryCache ) ) {
			$messageName = "proofreadpage_quality{$level}_category";
			$category = $this->msg( $messageName )->inContentLanguage()->text();
			$this->qualityLevelCategoryCache[$level] = $category;
		}
		return $this->qualityLevelCategoryCache[$level];
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	protected function getExamplesMessages() {
		return [
			'action=query&generator=allpages&gapnamespace=250&prop=proofread'
				=> 'apihelp-query+proofread-example-1',
		];
	}
}
