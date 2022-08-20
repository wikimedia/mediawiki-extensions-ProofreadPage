<?php

namespace ProofreadPage\Page;

use Content;
use ContentHandler;
use Html;
use IContextSource;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreloadTransformParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRenderingProvider;
use MWContentSerializationException;
use MWException;
use ParserOutput;
use ProofreadPage\Context;
use ProofreadPage\Index\IndexTemplateStyles;
use ProofreadPage\Index\UpdateIndexQualityStats;
use ProofreadPage\MultiFormatSerializerUtils;
use TextContentHandler;
use Title;
use WikitextContent;
use WikitextContentHandler;

/**
 * @license GPL-2.0-or-later
 *
 * Content handler for a Page: pages
 */
class PageContentHandler extends TextContentHandler {

	use MultiFormatSerializerUtils;

	/**
	 * @var WikitextContentHandler
	 */
	protected $wikitextContentHandler;

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_PROOFREAD_PAGE ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_WIKITEXT, CONTENT_FORMAT_JSON ] );
		$this->wikitextContentHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return PageContent::class;
	}

	/**
	 * @inheritDoc
	 */
	public function canBeUsedOn( Title $title ) {
		return parent::canBeUsedOn( $title ) &&
			$title->getNamespace() === Context::getDefaultContext()->getPageNamespaceId();
	}

	/**
	 * @param PageContent $content
	 * @param string|null $format
	 * @return string
	 * @suppress PhanParamSignatureMismatch Intentional mismatching Content
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		switch ( $format ) {
			case CONTENT_FORMAT_JSON:
				return $this->serializeContentInJson( $content );
			default:
				return $this->serializeContentInWikitext( $content );
		}
	}

	/**
	 * @param PageContent $content
	 * @return string
	 */
	private function serializeContentInJson( PageContent $content ) {
		$level = $content->getLevel();
		$user = $level->getUser();

		return json_encode( [
			'header' => $content->getHeader()->serialize(),
			'body' => $content->getBody()->serialize(),
			'footer' => $content->getFooter()->serialize(),
			'level' => [
				'level' => $level->getLevel(),
				'user' => $user ? $user->getName() : null
			]
		] );
	}

	/**
	 * @param PageContent $content
	 * @return string
	 */
	private function serializeContentInWikitext( PageContent $content ) {
		$level = $content->getLevel();
		$user = $level->getUser();
		$userName = $user ? $user->getName() : '';
		$text =
			'<noinclude>' .
				'<pagequality level="' . $level->getLevel() . '" user="' . $userName . '" />' .
				$content->getHeader()->serialize() .
			'</noinclude>' .
			$content->getBody()->serialize() .
			'<noinclude>' .
				$content->getFooter()->serialize() .
			'</noinclude>';

		return $text;
	}

	/**
	 * @param string $text
	 * @param string|null $format
	 * @return PageContent
	 */
	public function unserializeContent( $text, $format = null ) {
		if ( $format === null ) {
			$format = self::guessDataFormat( $text, true );
		}

		switch ( $format ) {
			case CONTENT_FORMAT_JSON:
				return $this->unserializeContentInJson( $text );
			case CONTENT_FORMAT_WIKITEXT:
				return $this->unserializeContentInWikitext( $text );
			default:
				throw new MWException(
					"Format ' . $format . ' is not supported for content model " . $this->getModelID()
				);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		'@phan-var PageContent $content';

		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		$contentClass = $this->getContentClass();
		$header = $content->getHeader();
		$body = $content->getBody();
		$footer = $content->getFooter();

		return new $contentClass(
			$contentHandlerFactory->getContentHandler( $header->getModel() )
				->preSaveTransform( $header, $pstParams ),
			$contentHandlerFactory->getContentHandler( $body->getModel() )
				->preSaveTransform( $body, $pstParams ),
				$contentHandlerFactory->getContentHandler( $footer->getModel() )
				->preSaveTransform( $footer, $pstParams ),
			$content->getLevel()
		);
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
		PreloadTransformParams $pltparams
	): Content {
		'@phan-var PageContent $content';
		$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();
		$contentClass = $this->getContentClass();
		$header = $content->getHeader();
		$body = $content->getBody();
		$footer = $content->getFooter();

		return new $contentClass(
			$contentHandlerFactory->getContentHandler( $header->getModel() )
				->preloadTransform( $header, $pltparams ),
			$contentHandlerFactory->getContentHandler( $body->getModel() )
				->preloadTransform( $body, $pltparams ),
			$contentHandlerFactory->getContentHandler( $footer->getModel() )
				->preloadTransform( $footer, $pltparams ),
			$content->getLevel()
		);
	}

	/**
	 * @param string $text
	 * @return PageContent
	 * @throws MWContentSerializationException
	 * @suppress PhanTypeMismatchArgument
	 */
	private function unserializeContentInJson( $text ) {
		$array = json_decode( $text, true );

		if ( !is_array( $array ) ) {
			throw new MWContentSerializationException(
				'The serialization is an invalid JSON array.'
			);
		}
		self::assertArrayKeyExistsInSerialization( 'header', $array );
		self::assertArrayKeyExistsInSerialization( 'body', $array );
		self::assertArrayKeyExistsInSerialization( 'footer', $array );
		self::assertArrayKeyExistsInSerialization( 'level', $array );
		self::assertArrayKeyExistsInSerialization( 'level', $array['level'] );

		$user = array_key_exists( 'user', $array['level'] )
			? PageLevel::getUserFromUserName( $array['level']['user'] )
			: null;

		return new PageContent(
			$this->wikitextContentHandler->unserializeContent( $array['header'] ),
			$this->wikitextContentHandler->unserializeContent( $array['body'] ),
			$this->wikitextContentHandler->unserializeContent( $array['footer'] ),
			new PageLevel( $array['level']['level'], $user )
		);
	}

	/**
	 * @param string $text
	 * @return PageContent
	 * @suppress PhanTypeMismatchArgument
	 */
	private function unserializeContentInWikitext( $text ) {
		$header = '';
		$footer = '';
		$proofreader = '';
		$level = 1;

		$cleanHeader = false;
		$cleanBody = false;
		$cleanFooter = false;

		if ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*)<noinclude>(.*?)<\/noinclude>$/s',
			$text, $m )
		) {
			$header = $m[1];
			$body = $m[2];
			$footer = $m[3];
			$cleanFooter = true;
		} elseif ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*?)$/s', $text, $m ) ) {
			$header = $m[1];
			$body = $m[2];
			$cleanBody = true;
		} else {
			$body = $text;
		}

		if ( preg_match(
			'/^<pagequality level="([0-4])" user="(.*?)" *(?:\/>|> *<\/pagequality>)(.*?)$/s',
			$header, $m )
		) {
			$level = intval( $m[1] );
			$proofreader = $m[2];
			$header = $m[3];
			$cleanHeader = true;
		} elseif (
			preg_match( '/^\{\{PageQuality\|([0-4])(?:\|(.*?))?\}\}(.*)/is', $header, $m )
		) {
			$level = intval( $m[1] );
			$proofreader = $m[2];
			$header = $m[3];
			$cleanHeader = true;
		}

		if ( $cleanHeader ) {
			if ( $cleanFooter ) {
				list( $header, $footer ) = $this->cleanDeprecatedWrappers( $header, $footer );
			} elseif ( $cleanBody ) {
				list( $header, $body ) = $this->cleanDeprecatedWrappers( $header, $body );
			} else {
				// notice that second parameter is unused
				list( $header, ) = $this->cleanDeprecatedWrappers( $header, '' );
			}
		}

		return new PageContent(
			$this->wikitextContentHandler->unserializeContent( $header ),
			$this->wikitextContentHandler->unserializeContent( $body ),
			$this->wikitextContentHandler->unserializeContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	/**
	 * @param string $header
	 * @param string $footer
	 * @return string[]
	 */
	protected function cleanDeprecatedWrappers( $header, $footer ) {
		$cleanedHeader = false;
		if ( preg_match( '/^(.*?)<div class="pagetext">(.*?)$/s', $header, $mt ) ) {
			$header = $mt[2];
			$cleanedHeader = true;
		} elseif ( preg_match( '/^(.*?)<div>(.*?)$/s', $header, $mt ) ) {
			$header = $mt[2];
			$cleanedHeader = true;
		}

		if ( $cleanedHeader && preg_match( '/^(.*?)<\/div>$/s', $footer, $mt ) ) {
			$footer = $mt[1];
		}

		return [ $header, $footer ];
	}

	/**
	 * @inheritDoc
	 */
	public function getActionOverrides() {
		return [
			'edit' => PageEditAction::class,
			'submit' => PageSubmitAction::class,
			'view' => PageViewAction::class
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getSlotDiffRendererInternal( IContextSource $context ) {
		return new PageSlotDiffRenderer( $context );
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchArgument
	 */
	public function makeEmptyContent() {
		return new PageContent(
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeEmptyContent(),
			new PageLevel()
		);
	}

	/**
	 * @param PageContent $oldContent
	 * @param PageContent $myContent
	 * @param PageContent $yourContent
	 * @return PageContent|false
	 * @suppress PhanParamSignatureMismatch Intentional mismatching Content
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

		if ( $myContent->getLevel()->getLevel() !== $yourContent->getLevel()->getLevel() ) {
			return false;
		}

		$wikitextHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
		$mergedHeader = $myContent->getHeader()->equals( $yourContent->getHeader() )
			? $myContent->getHeader()
			: $wikitextHandler->merge3(
				$oldContent->getHeader(), $myContent->getHeader(), $yourContent->getHeader()
			);
		$mergedBody = $myContent->getBody()->equals( $yourContent->getBody() )
			? $myContent->getBody()
			: $wikitextHandler->merge3(
				$oldContent->getBody(), $myContent->getBody(), $yourContent->getBody()
			);
		$mergedFooter = $myContent->getFooter()->equals( $yourContent->getFooter() )
			? $yourContent->getFooter()
			: $wikitextHandler->merge3(
				$oldContent->getFooter(), $myContent->getFooter(), $yourContent->getFooter()
			);

		if ( !$mergedHeader || !$mergedBody || !$mergedFooter ) {
			return false;
		}

		return new PageContent(
			$mergedHeader, $mergedBody, $mergedFooter, $yourContent->getLevel()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getAutosummary(
		Content $oldContent = null, Content $newContent = null, $flags = 0
	) {
		$summary = parent::getAutosummary( $oldContent, $newContent, $flags );

		if ( $newContent instanceof PageContent &&
			( $oldContent === null || $oldContent instanceof PageContent &&
			!$newContent->getLevel()->equals( $oldContent->getLevel() ) )
		) {
			$summary = trim( '/* ' . $newContent->getLevel()->getLevelCategoryName() .
				' */ ' . $summary );
		}

		return $summary;
	}

	/**
	 * @inheritDoc
	 * @todo is it the right content for redirects?
	 * @suppress PhanTypeMismatchArgument
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		return new PageContent(
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeRedirectContent( $destination, $text ),
			$this->wikitextContentHandler->makeEmptyContent(),
			new PageLevel()
		);
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

		$indexTitle = $this->getIndexTitle( $title );
		if ( $indexTitle !== null ) {
			$indexTitle->invalidateCache();
			$updates[] = $this->buildIndexQualityStatsUpdate( $title, $indexTitle, $content );
		}

		return $updates;
	}

	/**
	 * @inheritDoc
	 */
	public function getDeletionUpdates( Title $title, $role ) {
		$updates = parent::getDeletionUpdates( $title, $role );

		$indexTitle = $this->getIndexTitle( $title );
		if ( $indexTitle !== null ) {
			$updates[] = $this->buildIndexQualityStatsUpdate( $title, $indexTitle );
		}

		return $updates;
	}

	/**
	 * @param Title $pageTitle
	 * @return Title|null
	 */
	private function getIndexTitle( Title $pageTitle ): ?Title {
		return Context::getDefaultContext()->getIndexForPageLookup()->getIndexForPageTitle( $pageTitle );
	}

	/**
	 * @param Title $pageTitle
	 * @param Title $indexTitle
	 * @param Content|null $pageContent
	 * @return UpdateIndexQualityStats
	 */
	private function buildIndexQualityStatsUpdate(
		Title $pageTitle,
		Title $indexTitle,
		Content $pageContent = null
	): UpdateIndexQualityStats {
		$context = Context::getDefaultContext();
		$newLevel = ( $pageContent instanceof PageContent )
			? $pageContent->getLevel()->getLevel()
			: null;
		return new UpdateIndexQualityStats(
			MediaWikiServices::getInstance()->getDBLoadBalancer(),
			$context->getPageQualityLevelLookup(),
			$context->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle ),
			$indexTitle,
			$pageTitle,
			$newLevel
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		$context = Context::getDefaultContext();
		$indexTitle = $context->getIndexForPageLookup()->getIndexForPageTitle( $title );
		if ( $indexTitle ) {
			$indexContent = $context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
			$indexLang = $context->getCustomIndexFieldsParser()->getContentLanguage( $indexContent );
			if ( $indexLang ) {
				// if unrecognized, uses $wgContentLanguage
				$services = MediaWikiServices::getInstance();
				return $services->getLanguageNameUtils()->isKnownLanguageTag( $indexLang ) ?
					$services->getLanguageFactory()->getLanguage( $indexLang ) :
					$services->getContentLanguage();
			}
		}
		return parent::getPageLanguage( $title, $content );
	}

	/**
	 * @inheritDoc
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$parserOutput
	) {
		'@phan-var PageContent $content';
		$title = Title::castFromPageReference( $cpoParams->getPage() );
		'@phan-var Title $title';
		if ( $content->isRedirect() ) {
			$parserOutput = $this->wikitextContentHandler->getParserOutput( $content->getBody(), $cpoParams );
			return;
		}

		$context = Context::getDefaultContext();
		$indexTitle = $context->getIndexForPageLookup()->getIndexForPageTitle( $title );

		// create content
		$wikitext = trim(
			$content->getHeader()->getText()
			. "\n\n"
			. $content->getBody()->getText()
			. $content->getFooter()->getText()
		);

		$indexTs = null;
		if ( $indexTitle !== null ) {
			$indexTs = new IndexTemplateStyles( $indexTitle );
			// newline so that following wikitext that needs to start on a newline
			// like tables, lists, etc, can do so.
			$wikitext = $indexTs->getIndexTemplateStyles( '.pagetext' ) . "\n" . $wikitext;
		}
		$wikitextContent = new WikitextContent( $wikitext );

		$parserOutput = new ParserOutput();
		$this->wikitextContentHandler->fillParserOutputInternal( $wikitextContent, $cpoParams, $parserOutput );
		$parserOutput->addCategory(
			Title::makeTitleSafe(
				NS_CATEGORY,
				$content->getLevel()->getLevelCategoryName()
			)->getDBkey(),
			$title->getText()
		);
		$parserOutput->setPageProperty(
			'proofread_page_quality_level',
			(string)$content->getLevel()->getLevel()
		);

		// html container
		$html = Html::openElement( 'div',
			[ 'class' => 'prp-page-qualityheader quality' . $content->getLevel()->getLevel() ] ) .
			wfMessage( 'proofreadpage_quality' . $content->getLevel()->getLevel() . '_message' )
				->title( $title )->inContentLanguage()->parse() .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', [ 'class' => 'pagetext' ] ) .
			$parserOutput->getText( [ 'enableSectionEditLinks' => false ] ) .
			Html::closeElement( 'div' );
		$parserOutput->setText( $html );

		$pageDisplayHandler = new PageDisplayHandler( $context );
		$jsVars = $pageDisplayHandler->getPageJsConfigVars( $title, $content );
		foreach ( $jsVars as $key => $value ) {
			$parserOutput->setJsConfigVar( $key, $value );
		}

		// add modules
		$parserOutput->addModuleStyles( [ 'ext.proofreadpage.base' ] );

		// add scan image to dependencies
		$parserOutput->addImage( strtok( $title->getDBkey(), '/' ) );

		// add the styles.css as a dependency (even if it doesn't exist yet)
		if ( $indexTs !== null ) {
			$stylesPage = $indexTs->getTemplateStylesPage();

			if ( $stylesPage ) {
				$parserOutput->addTemplate(
					$stylesPage,
					$stylesPage->getArticleID(),
					$stylesPage->getLatestRevID() );
			}
		}
	}
}
