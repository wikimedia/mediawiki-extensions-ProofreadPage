<?php

namespace ProofreadPage\Index;

use Content;
use ContentHandler;
use IContextSource;
use MediaWiki\Content\Transform\PreloadTransformParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRenderingProvider;
use MWContentSerializationException;
use Parser;
use ParserOptions;
use PPFrame;
use ProofreadPage\Context;
use ProofreadPage\Link;
use TextContentHandler;
use Title;
use WikitextContent;
use WikitextContentHandler;

/**
 * @license GPL-2.0-or-later
 *
 * Content handler for a Index: pages
 */
class IndexContentHandler extends TextContentHandler {

	/**
	 * @var WikitextContentHandler
	 */
	private $wikitextContentHandler;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var WikitextLinksExtractor
	 */
	private $wikitextLinksExtractor;

	/**
	 * @inheritDoc
	 */
	public function __construct( $modelId = CONTENT_MODEL_PROOFREAD_INDEX ) {
		$this->wikitextContentHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
		$this->parser = $this->buildParser();
		$this->wikitextLinksExtractor = new WikitextLinksExtractor();

		parent::__construct( $modelId, [ CONTENT_FORMAT_WIKITEXT ] );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return IndexContent::class;
	}

	/**
	 * Warning: should not be used outside of IndexContent
	 * @return Parser
	 */
	public function getParser() {
		return $this->parser;
	}

	private function buildParser() {
		$parser = MediaWikiServices::getInstance()->getParserFactory()->create();
		$parser->startExternalParse(
			null, ParserOptions::newCanonical( 'canonical' ), Parser::OT_PLAIN
		);
		return $parser;
	}

	/**
	 * @inheritDoc
	 */
	public function canBeUsedOn( Title $title ) {
		return parent::canBeUsedOn( $title ) &&
			$title->getNamespace() === Context::getDefaultContext()->getIndexNamespaceId();
	}

	/**
	 * @inheritDoc
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		return $this->serializeContentInWikitext( $content );
	}

	/**
	 * @param Content $content
	 * @throws MWContentSerializationException
	 * @return string
	 */
	private function serializeContentInWikitext( Content $content ) {
		if ( $content instanceof IndexRedirectContent ) {
			return '#REDIRECT [[' . $content->getRedirectTarget()->getFullText() . ']]';
		}
		if ( !( $content instanceof IndexContent ) ) {
			throw new MWContentSerializationException(
				'IndexContentHandler could only serialize IndexContent'
			);
		}

		$text = '{{:MediaWiki:Proofreadpage_index_template';
		/** @var WikitextContent $value */
		foreach ( $content->getFields() as $key => $value ) {
			$text .= "\n|" . $key . '=' . $value->serialize();
		}
		$text .= "\n}}";

		foreach ( $content->getCategories() as $category ) {
			$text .= "\n[[" . $category->getFullText() . ']]';
		}

		return $text;
	}

	/**
	 * @inheritDoc
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return $this->unserializeContentInWikitext( $text );
	}

	 /**
	  * @param string $text
	  * @return IndexRedirectContent|IndexContent
	  */
	private function unserializeContentInWikitext( $text ) {
		$fullWikitext = new WikitextContent( $text );
		if ( $fullWikitext->isRedirect() ) {
			return new IndexRedirectContent( $fullWikitext->getRedirectTarget() );
		}

		$dom = $this->parser->preprocessToDom( $text );
		$customFieldsValues = [];
		$categories = [];
		// We iterate on the main components of the Wikitext serialization
		for ( $child = $dom->getFirstChild(); $child; $child = $child->getNextSibling() ) {
			if ( $child->getName() === 'template' ) {
				// It's a template call, we extract the fields
				$frame = $this->parser->getPreprocessor()->newFrame();
				$childFrame = $frame->newChild( $child->getChildrenOfType( 'part' ) );
				// @phan-suppress-next-line PhanUndeclaredProperty
				foreach ( $childFrame->namedArgs as $varName => $value ) {
					$value = $this->parser->getStripState()->unstripBoth(
						$frame->expand( $value, PPFrame::RECOVER_ORIG )
					);

					if ( substr( $value, -1 ) === "\n" ) {
						// We strip one "\n"
						$value = substr( $value, 0, -1 );
					}
					$customFieldsValues[$varName] = new WikitextContent( $value );
				}
			} elseif ( $child->getName() === '#text' ) {
				// It's some text, we look for category links
				$text = $this->parser->getStripState()->unstripBoth( strval( $child ) );
				$categoryLinks = $this->wikitextLinksExtractor->getLinksToNamespace( $text, NS_CATEGORY );
				/** @var Link $categoryLink */
				foreach ( $categoryLinks as $categoryLink ) {
					$categories[] = $categoryLink->getTarget();
				}
			}
		}
		return new IndexContent( $customFieldsValues, $categories );
	}

	/**
	 * @inheritDoc
	 */
	public function getActionOverrides() {
		return [
			'edit' => IndexEditAction::class,
			'submit' => IndexSubmitAction::class
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getSlotDiffRendererInternal( IContextSource $context ) {
		return new IndexSlotDiffRenderer(
			$context,
			Context::getDefaultContext()->getCustomIndexFieldsParser(),
			$this->wikitextContentHandler->getSlotDiffRenderer( $context )
		);
	}

	/**
	 * @inheritDoc
	 */
	public function makeEmptyContent() {
		return new IndexContent( [] );
	}

	/**
	 * @inheritDoc
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

		if ( !( $oldContent instanceof IndexContent && $myContent instanceof IndexContent &&
			$yourContent instanceof IndexContent )
		) {
			return false;
		}

		$oldFields = $oldContent->getFields();
		$myFields = $myContent->getFields();
		$yourFields = $yourContent->getFields();

		// We adds yourFields to myFields
		foreach ( $yourFields as $key => $yourValue ) {
			if ( array_key_exists( $key, $myFields ) ) {
				$oldValue  = array_key_exists( $key, $oldFields )
					? $oldFields[$key]
					: $this->wikitextContentHandler->makeEmptyContent();
				$myFields[$key] = $this->wikitextContentHandler->merge3(
					$oldValue, $myFields[$key], $yourValue
				);

				if ( $myFields[$key] === false ) {
					return false;
				}
			} else {
				$myFields[$key] = $yourValue;
			}
		}

		// Categories
		$categories = $this->arrayMerge3(
			$oldContent->getCategories(),
			$myContent->getCategories(),
			$yourContent->getCategories()
		);

		return new IndexContent( $myFields, $categories );
	}

	/**
	 * @param array $old
	 * @param array $my
	 * @param array $your
	 * @return array
	 */
	private function arrayMerge3( array $old, array $my, array $your ) {
		// TODO: detection of deletions
		return array_unique( array_merge( $my, $your ) );
	}

	/**
	 * @inheritDoc
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		return new IndexRedirectContent( $destination );
	}

	/**
	 * @inheritDoc
	 */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function isParserCacheSupported() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getSecondaryDataUpdates(
		Title $title,
		Content $content,
		$role,
		SlotRenderingProvider $slotOutput
	) {
		$updates = parent::getSecondaryDataUpdates( $title, $content, $role, $slotOutput );
		$updates[] = ( $content instanceof IndexContent )
			? $this->buildIndexQualityStatsUpdate( $title, $content )
			: $this->buildIndexQualityStatsDelete( $title );
		return $updates;
	}

	/**
	 * @inheritDoc
	 */
	public function getDeletionUpdates( Title $title, $role ) {
		$updates = parent::getDeletionUpdates( $title, $role );
		$updates[] = $this->buildIndexQualityStatsDelete( $title );
		return $updates;
	}

	/**
	 * @inheritDoc
	 */
	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();

		if ( $content instanceof IndexRedirectContent ) {
			return $content;
		}

		'@phan-var IndexContent $content';
		$fields = [];
		foreach ( $content->getFields() as $key => $value ) {
			$contentHandler = $contentHandlerFactory->getContentHandler( $value->getModel() );
			$fields[$key] = $contentHandler->preSaveTransform(
				$value,
				$pstParams
			);
		}

		$contentClass = $this->getContentClass();
		return new $contentClass( $fields, $content->getCategories() );
	}

	/**
	 * @inheritDoc
	 */
	public function preloadTransform(
		Content $content,
		PreloadTransformParams $pltParams
	): Content {
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();

		if ( $content instanceof IndexRedirectContent ) {
			return $content;
		}

		'@phan-var IndexContent $content';
		$fields = [];

		foreach ( $content->getFields() as $key => $value ) {
			$contentHandler = $contentHandlerFactory->getContentHandler( $value->getModel() );
			$fields[$key] = $contentHandler->preloadTransform(
				$value,
				$pltParams
			);
		}

		$contentClass = $this->getContentClass();
		return new $contentClass( $fields, $content->getCategories() );
	}

	/**
	 * @param Title $title
	 * @param IndexContent $content
	 * @return UpdateIndexQualityStats
	 */
	private function buildIndexQualityStatsUpdate( Title $title, IndexContent $content ): UpdateIndexQualityStats {
		$context = Context::getDefaultContext();
		return new UpdateIndexQualityStats(
			MediaWikiServices::getInstance()->getDBLoadBalancer(),
			$context->getPageQualityLevelLookup(),
			$context->getPaginationFactory()->buildPaginationForIndexContent( $title, $content ),
			$title
		);
	}

	/**
	 * @param Title $title
	 * @return DeleteIndexQualityStats
	 */
	private function buildIndexQualityStatsDelete( Title $title ): DeleteIndexQualityStats {
		return new DeleteIndexQualityStats( MediaWikiServices::getInstance()->getDBLoadBalancer(), $title );
	}
}
