<?php

namespace ProofreadPage\Index;

use Article;
use Content;
use IContextSource;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreloadTransformParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Content\ValidationParams;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRenderingProvider;
use MWContentSerializationException;
use Parser;
use ParserOptions;
use ParserOutput;
use PPFrame;
use ProofreadPage\Context;
use ProofreadPage\Link;
use ProofreadPage\MultiFormatSerializerUtils;
use StatusValue;
use TextContentHandler;
use Title;
use UnexpectedValueException;
use WikitextContent;
use WikitextContentHandler;

/**
 * @license GPL-2.0-or-later
 *
 * Content handler for a Index: pages
 */
class IndexContentHandler extends TextContentHandler {

	use MultiFormatSerializerUtils;

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
		$this->wikitextContentHandler = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_WIKITEXT );
		$this->parser = $this->buildParser();
		$this->wikitextLinksExtractor = new WikitextLinksExtractor();

		parent::__construct( $modelId, [ CONTENT_FORMAT_WIKITEXT, CONTENT_FORMAT_JSON ] );
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
			null, ParserOptions::newFromAnon(), Parser::OT_PLAIN
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
		// if not given, default is Wikitext
		$format = $format ?: CONTENT_FORMAT_WIKITEXT;

		$this->checkFormat( $format );

		// redirects can only be wikitext
		if ( $content instanceof IndexRedirectContent ) {
			self::assertFormatSuitableForRedirect( $format );
			return '#REDIRECT [[' . $content->getRedirectTarget()->getFullText() . ']]';
		}

		if ( !( $content instanceof IndexContent ) ) {
			throw new MWContentSerializationException(
				'IndexContentHandler could only serialize IndexContent'
			);
		}

		switch ( $format ) {
			case CONTENT_FORMAT_JSON:
				return $this->serializeContentInJson( $content );
			case CONTENT_FORMAT_WIKITEXT:
				return $this->serializeContentInWikitext( $content );
			default:
				throw new MWContentSerializationException(
					"Format '$format' is not supported for serialization of content model " .
						$this->getModelID()
				);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		if ( $format === null ) {
			$format = self::guessDataFormat( $text, false );
		}

		switch ( $format ) {
			case CONTENT_FORMAT_JSON:
				return $this->unserializeContentInJson( $text );
			case CONTENT_FORMAT_WIKITEXT:
				return $this->unserializeContentInWikitext( $text );
			default:
				throw new UnexpectedValueException(
					"Format '$format' is not supported for unserialization of content model " .
						$this->getModelID()
				);
		}
	}

	/**
	 * @param IndexContent $content
	 * @throws MWContentSerializationException
	 * @return string
	 */
	private function serializeContentInWikitext( IndexContent $content ): string {
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
	 * @param IndexContent $content
	 * @throws MWContentSerializationException
	 * @return string
	 */
	public function serializeContentInJson( IndexContent $content ): string {
		$data = [
			'fields' => [],
			'categories' => [],
		];

		/** @var WikitextContent $value */
		foreach ( $content->getFields() as $key => $value ) {
			$data['fields'][ $key ] = $value->serialize();
		}

		foreach ( $content->getCategories() as $category ) {
			$data['categories'][] = $category->getText();
		}

		return json_encode( $data, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * @param string $text
	 * @return IndexContent
	 * @throws MWContentSerializationException
	 */
	private function unserializeContentInJson( $text ): IndexContent {
		$array = json_decode( $text, true );

		if ( !is_array( $array ) ) {
			throw new MWContentSerializationException(
				'The serialization is an invalid JSON array.'
			);
		}

		$customFieldsValues = [];
		$categories = [];

		if ( isset( $array['categories'] ) ) {

			self::assertArrayValueIsArray( $array, 'categories' );
			self::assertArrayIsSequential( $array['categories'], 'categories' );
			self::assertContainsOnlyStrings( $array['categories'], false, 'categories' );

			/** @var string $category */
			foreach ( $array['categories'] as $category ) {
				$title = Title::makeTitleSafe( NS_CATEGORY, $category );

				if ( $title ) {
					$categories[] = $title;
				} else {
					throw new MWContentSerializationException(
						"The category title '$category' is invalid."
					);
				}
			}
		}

		if ( isset( $array['fields'] ) ) {
			self::assertArrayValueIsArray( $array, 'fields' );
			// for now, all supported 'type' values are encoded as strings
			// we may relax this in future if we accept object-type date in fields
			self::assertContainsOnlyStrings( $array['fields'], true, 'fields' );

			/** @var string $fieldValue */
			foreach ( $array['fields'] as $fieldKey => $fieldValue ) {
				if ( $fieldValue !== null ) {
					$customFieldsValues[$fieldKey] = new WikitextContent( $fieldValue );
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
	public function validateSave(
		Content $content,
		ValidationParams $validationParams
	) {
		if ( $content instanceof IndexRedirectContent ) {
			return StatusValue::newGood();
		} else {
			'@phan-var IndexContent $content';
			if ( !$content->isValid() ) {
				return StatusValue::newFatal( 'invalid-content-data' );
			}

			// Get list of pages titles
			$links = $content->getLinksToNamespace(
				Context::getDefaultContext()->getPageNamespaceId()
			);
			$linksTitle = [];
			foreach ( $links as $link ) {
				$linksTitle[] = $link->getTarget();
			}

			if ( count( $linksTitle ) !== count( array_unique( $linksTitle ) ) ) {
				return StatusValue::newFatal( 'proofreadpage_indexdupetext' );
			}

			return StatusValue::newGood();
		}
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
	public function supportsPreloadContent(): bool {
		return true;
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
	 * @inheritDoc
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$parserOutput
	) {
		$title = Title::castFromPageReference( $cpoParams->getPage() );
		$parserOptions = $cpoParams->getParserOptions();

		if ( $content instanceof IndexRedirectContent ) {
			$parserOutput->addLink( $content->getRedirectTarget() );
			if ( $cpoParams->getGenerateHtml() ) {
				$parserOutput->setText( Article::getRedirectHeaderHtml(
					$title->getPageLanguage(), $content->getRedirectTarget()
				) );
				$parserOutput->addModuleStyles( [ 'mediawiki.action.view.redirectPage' ] );
			}
		} else {
			'@phan-var IndexContent $content';
			$parserHelper = new ParserHelper( $title, $parserOptions );

			// Start with the default index styles
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$indexTs = new IndexTemplateStyles( $title );
			$text = $indexTs->getIndexTemplateStyles( null );

			// make sure the template starts on a new line in case it starts
			// with something like '{|'
			if ( $text ) {
				$text .= "\n";
			}

			// We retrieve the view template
			list( $templateText, $templateTitle ) = $parserHelper->fetchTemplateTextAndTitle(
				Title::makeTitle( NS_MEDIAWIKI, 'Proofreadpage index template' )
			);

			// We replace the arguments calls by their values
			$text .= $parserHelper->expandTemplateArgs(
				$templateText,
				array_map( static function ( Content $content ) {
					return $content->serialize( CONTENT_FORMAT_WIKITEXT );
				}, $content->getFields() )
			);

			// Force no section edit links
			$text = '__NOEDITSECTION__' . $text;

			// We do the final rendering
			$parserOutput = MediaWikiServices::getInstance()->getParser()
				// @phan-suppress-next-line PhanTypeMismatchArgument
				->parse( $text, $title, $parserOptions, true, true, $cpoParams->getRevId() );
			$parserOutput->addTemplate( $templateTitle,
				$templateTitle->getArticleID(),
				$templateTitle->getLatestRevID()
			);

			foreach ( $content->getCategories() as $category ) {
				$parserOutput->addCategory( $category->getDBkey(), $category->getText() );
			}
		}
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
