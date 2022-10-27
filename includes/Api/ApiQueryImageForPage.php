<?php

namespace ProofreadPage\Api;

use ApiBase;
use ApiQueryBase;
use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Page\PageDisplayHandler;
use Title;
use Wikimedia\ParamValidator\ParamValidator;

class ApiQueryImageForPage extends ApiQueryBase {
	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var PageDisplayhandler
	 */
	private $pageDisplayHandler;

	/** @var string API module prefix */
	private static $prefix = 'prppifp';

	/**
	 * @param \ApiQuery $query
	 * @param string $moduleName
	 */
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, static::$prefix );
		$this->context = $this->context = Context::getDefaultContext();
		$this->pageDisplayHandler = new PageDisplayHandler( $this->context );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$pageSet = $this->getPageSet()->getGoodAndMissingPages();
		$result = $this->getResult();
		$pagePageImages = [];

		$props = array_fill_keys( $params['prop'], true );

		foreach ( $pageSet as $pageID => $page ) {
			if ( $page->getNamespace() !== $this->context->getPageNamespaceId() ) {
				continue;
			}

			$title = Title::castFromPageIdentity( $page );

			if ( !$title ) {
				continue;
			}

			$result->addValue( [ 'query', 'pages', $pageID ], 'imagesforpage', $this->getImageData( $title, $props ) );
		}
	}

	/**
	 * Get data about images for a title based on parameters supplied by the user
	 *
	 * The function does not check for any kind of null values returned for the various getImage...() calls
	 * instead relying on a file provider check at the start to confirm the existence of a file for the page title.
	 *
	 * If the page title is outside bounds, the details of the last page is returned.
	 *
	 * @param Title $title
	 * @param array $props parameters sent by user
	 * @return array Array of image urls
	 */
	public function getImageData( Title $title, array $props ): array {
		$file = null;
		try {
			$fileProvider = $this->context->getFileProvider();
			$file = $fileProvider->getFileForPageTitle( $title );
		} catch ( FileNotFoundException $e ) {
			return [];
		}

		if ( !$file || !$file->exists() ) {
			return [];
		}

		$data = [
			'thumbnail' => $this->pageDisplayHandler->getImageThumbnail( $title )->getUrl()
		];

		if ( isset( $props['size'] ) ) {
			$data['size'] = $this->pageDisplayHandler->getImageWidth( $title );
		}

		if ( isset( $props['fullsize'] ) ) {
			$data['fullsize'] = $this->pageDisplayHandler->getImageFullSize( $title )->getUrl();
		}

		if ( isset( $props['filename'] ) ) {
			$data['filename'] = $file->getName();
		}

		if ( isset( $props['responsiveimages'] ) ) {
			$responsiveUrls = $this->pageDisplayHandler->getImageThumbnail( $title )->responsiveUrls;
			$data['responsiveimages'] = [];
			foreach ( $responsiveUrls as $density => $url ) {
				$data['responsiveimages'][$density] = $url;
			}
		}

		return $data;
	}

	/**
	 * @inheritDoc
	 */
	protected function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'filename|size|fullsize|responsiveimages',
				ParamValidator::PARAM_TYPE => [
					'filename',
					'size',
					'fullsize',
					'responsiveimages'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
		];
	}
}
