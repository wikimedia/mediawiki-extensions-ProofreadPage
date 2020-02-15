<?php

namespace ProofreadPage\Page;

use Content;
use ContentHandler;
use IContextSource;
use MWContentSerializationException;
use MWException;
use ProofreadPage\Context;
use TextContentHandler;
use Title;
use User;
use WikitextContentHandler;

/**
 * @license GPL-2.0-or-later
 *
 * Content handler for a Page: pages
 */
class PageContentHandler extends TextContentHandler {

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

		return json_encode( [
			'header' => $content->getHeader()->serialize(),
			'body' => $content->getBody()->serialize(),
			'footer' => $content->getFooter()->serialize(),
			'level' => [
				'level' => $level->getLevel(),
				'user' => $level->getUser() instanceof User ? $level->getUser()->getName() : null
			]
		] );
	}

	/**
	 * @param PageContent $content
	 * @return string
	 */
	private function serializeContentInWikitext( PageContent $content ) {
		$level = $content->getLevel();
		$userName = $level->getUser() instanceof User ? $level->getUser()->getName() : '';
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
			$format = $this->guessFormat( $text );
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
	 * @param string $text
	 * @return string
	 */
	private function guessFormat( $text ) {
		return is_array( json_decode( $text, true ) )
			? CONTENT_FORMAT_JSON
			: CONTENT_FORMAT_WIKITEXT;
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
		$this->assertArrayKeyExistsInSerialization( 'header', $array );
		$this->assertArrayKeyExistsInSerialization( 'body', $array );
		$this->assertArrayKeyExistsInSerialization( 'footer', $array );
		$this->assertArrayKeyExistsInSerialization( 'level', $array );
		$this->assertArrayKeyExistsInSerialization( 'level', $array['level'] );

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

	private function assertArrayKeyExistsInSerialization( $key, array $serialization ) {
		if ( !array_key_exists( $key, $serialization ) ) {
			throw new MWContentSerializationException(
				"The serialization should contain an $key entry."
			);
		}
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
				list( $header, /*unused*/ ) = $this->cleanDeprecatedWrappers( $header, '' );
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
	 * @return array
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
}
