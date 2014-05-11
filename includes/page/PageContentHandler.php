<?php

namespace ProofreadPage\Page;

use Content;
use ContentHandler;
use FormatJson;
use MWContentSerializationException;
use TextContentHandler;
use Title;
use User;
use WikitextContentHandler;

/**
 * @licence GNU GPL v2+
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
		parent::__construct( $modelId, array( CONTENT_FORMAT_WIKITEXT, CONTENT_FORMAT_JSON ) );
		$this->wikitextContentHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @see ContentHandler::serializeContent
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		switch( $format ) {
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

		return FormatJson::encode( array(
			'header' => $content->getHeader()->serialize(),
			'body' => $content->getBody()->serialize(),
			'footer' => $content->getFooter()->serialize(),
			'level' => array(
				'level' => $level->getLevel(),
				'user' => $level->getUser()->getName()
			)
		) );
	}

	/**
	 * @param PageContent $content
	 * @return string
	 */
	private function serializeContentInWikitext( PageContent $content ) {
		$level = $content->getLevel();
		$text = '<noinclude><pagequality level="' . $level->getLevel() . '" user="';
		if ( $level->getUser() instanceof User ) {
			$text .= $level->getUser()->getName();
		}
		$text .= '" /><div class="pagetext">' . $content->getHeader()->serialize() . "\n\n\n" . '</noinclude>';
		$text .= $content->getBody()->serialize();
		$text .= '<noinclude>' . $content->getFooter()->serialize() . '</div></noinclude>';

		return $text;
	}

	/**
	 * @see ContentHandler::unserializeContent
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		switch( $format ) {
			case CONTENT_FORMAT_JSON:
				return $this->unserializeContentInJson( $text );
			default:
				return $this->unserializeContentInWikitext( $text );
		}
	}

	/**
	 * @param $text
	 * @return PageContent
	 */
	private function unserializeContentInJson( $text ) {
		$array = FormatJson::decode( $text, true );

		if ( $array === null || !is_array( $array ) ) {
			throw new MWContentSerializationException( 'The serialization is an invalid JSON array.' );
		}
		$this->assertArrayKeyExistsInSerialization( 'header', $array );
		$this->assertArrayKeyExistsInSerialization( 'body', $array );
		$this->assertArrayKeyExistsInSerialization( 'footer', $array );
		$this->assertArrayKeyExistsInSerialization( 'level', $array );
		$this->assertArrayKeyExistsInSerialization( 'level', $array['level'] );

		$user = array_key_exists( 'user', $array['level'] )
			? PageLevel::getUserFromUserName(  $array['level']['user'] )
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
			throw new MWContentSerializationException( "The serialization should contain an $key entry." );
		}
	}

	/**
	 * @param $text
	 * @return PageContent
	 */
	private function unserializeContentInWikitext( $text ) {
		$header = '';
		$footer = '';
		$proofreader = '';
		$level = 1;

		if ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*)<noinclude>(.*?)<\/noinclude>$/s', $text, $m ) ) {
			$header = $m[1];
			$body = $m[2];
			$footer = $this->cleanTrailingDivTag( $m[3] );
		} elseif ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*?)$/s', $text, $m ) ) {
			$header = $m[1];
			$body = $this->cleanTrailingDivTag( $m[2] );
		} else {
			$body = $text;
		}

		if ( preg_match( '/^<pagequality level="(0|1|2|3|4)" user="(.*?)" \/>(.*?)$/s', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[2];
			$header = $this->cleanHeader( $m[3] );
		} elseif ( preg_match( '/^\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}(.*)/is', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[3];
			$header = $this->cleanHeader( $m[4] );
		}

		return new PageContent(
			$this->wikitextContentHandler->unserializeContent( $header ),
			$this->wikitextContentHandler->unserializeContent( $body ),
			$this->wikitextContentHandler->unserializeContent( $footer ),
			new PageLevel( $level, PageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	protected function cleanTrailingDivTag( $text ) {
		if ( preg_match( '/^(.*?)<\/div>$/s', $text, $m2 ) ) {
			return $m2[1];
		} else {
			return $text;
		}
	}

	protected function cleanHeader( $header ) {
		if ( preg_match( '/^(.*?)<div class="pagetext">(.*?)$/s', $header, $mt ) ) {
			$header = $mt[2];
		} elseif ( preg_match( '/^(.*?)<div>(.*?)$/s', $header, $mt ) ) {
			$header = $mt[2];
		}

		return $header;
	}

	/**
	 * @see ContentHandler::getActionOverrides
	 */
	public function getActionOverrides() {
		return array(
			'edit' => '\ProofreadPage\Page\PageEditAction',
			'submit' => '\ProofreadPage\Page\PageSubmitAction',
			'view' => '\ProofreadPage\Page\PageViewAction'
		);
	}

	/**
	 * @see ContentHandler::getDiffEngineClass
	 */
	protected function getDiffEngineClass() {
		return '\ProofreadPage\Page\PageDifferenceEngine';
	}

	/**
	 * @see ContentHandler::makeEmptyContent
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
	 * @see ContentHandler::merge3
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
			: $wikitextHandler->merge3( $oldContent->getHeader(), $myContent->getHeader(), $yourContent->getHeader() );
		$mergedBody = $myContent->getBody()->equals( $yourContent->getBody() )
			? $myContent->getBody()
			: $wikitextHandler->merge3( $oldContent->getBody(), $myContent->getBody(), $yourContent->getBody() );
		$mergedFooter = $myContent->getFooter()->equals( $yourContent->getFooter() )
			? $yourContent->getFooter()
			: $wikitextHandler->merge3( $oldContent->getFooter(), $myContent->getFooter(), $yourContent->getFooter() );

		if ( !$mergedHeader || !$mergedBody || !$mergedFooter ) {
			return false;
		}

		return new PageContent( $mergedHeader, $mergedBody, $mergedFooter, $yourContent->getLevel() );
	}

	/**
	 * @see ContentHandler::getAutosummary
	 */
	public function getAutosummary( Content $oldContent = null, Content $newContent = null, $flags ) {
		$summary = parent::getAutosummary( $oldContent, $newContent, $flags );

		if ( $newContent instanceof PageContent &&
			( $oldContent === null || $oldContent instanceof PageContent && !$newContent->getLevel()->equals( $oldContent->getLevel() ) )
		) {
			$summary = trim( '/* ' . $newContent->getLevel()->getLevelCategoryName() . ' */ ' . $summary );
		}

		return $summary;
	}

	/**
	 * @see ContentHandler::makeParserOptions
	 */
	public function makeParserOptions( $context ) {
		$parserOptions = parent::makeParserOptions( $context );
		$parserOptions->setEditSection( false );

		return $parserOptions;
	}

	/**
	 * @see ContentHandler::makeRedirectContent
	 * @todo is it the right content for redirects?
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
	 * @see ContentHandler::supportsRedirects
	 */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * @see ContentHandler::isParserCacheSupported
	 */
	public function isParserCacheSupported() {
		return true;
	}
}
