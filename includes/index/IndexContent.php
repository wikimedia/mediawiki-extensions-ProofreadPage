<?php

namespace ProofreadPage\Index;

use Content;
use MagicWord;
use ParserOptions;
use TextContent;
use Title;
use User;
use WikitextContent;

/**
 * @licence GNU GPL v2+
 *
 * Content of a Index: page
 */
class IndexContent extends TextContent {

	/**
	 * @var WikitextContent[]
	 */
	private $fields = [];

	/**
	 * @param WikitextContent[] $fields
	 */
	public function __construct( array $fields ) {
		$this->fields = $fields;

		parent::__construct( '', CONTENT_MODEL_PROOFREAD_INDEX );
	}

	/**
	 * Returns an associative array property name => value as WikitextContent
	 * @return WikitextContent[]
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @see Content:isEmpty
	 */
	public function isEmpty() {
		foreach ( $this->fields as $value ) {
			if ( !$value->isEmpty() ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @see Content::equals
	 */
	public function equals( Content $that = null ) {
		if ( !( $that instanceof IndexContent ) || $that->getModel() !== $this->getModel() ) {
			return false;
		}

		foreach ( $this->fields as $key => $value ) {
			if ( !array_key_exists( $key, $that->fields ) || !$value->equals( $that->fields[$key] ) ) {
				return false;
			}
		}

		foreach ( $that->fields as $key => $value ) {
			if ( !array_key_exists( $key, $this->fields ) || !$value->equals( $this->fields[$key] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @see Content::getWikitextForTransclusion
	 */
	public function getWikitextForTransclusion() {
		return $this->serialize( CONTENT_FORMAT_WIKITEXT );
	}

	/**
	 * @see Content::getNativeData
	 */
	public function getNativeData() {
		return $this->serialize();
	}

	/**
	 * @see Content::getTextForSummary
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * @see Content::preSaveTransform
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$fields = [];

		foreach ( $this->fields as $key => $value ) {
			$fields[$key] = $value->preSaveTransform( $title, $user, $popts );
		}

		return new IndexContent( $fields );
	}

	/**
	 * @see Content::preloadTransform
	 */
	public function preloadTransform( Title $title, ParserOptions $popts, $params = [] ) {
		$fields = [];

		foreach ( $this->fields as $key => $value ) {
			$fields[$key] = $value->preloadTransform( $title, $popts, $params );
		}

		return new IndexContent( $fields );
	}

	/**
	 * @see Content::getSize
	 */
	public function getSize() {
		$size = 0;

		foreach ( $this->fields as $value ) {
			$size += $value->getSize();
		}

		return $size;
	}

	/**
	 * @see Content::isCountable
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		foreach ( $this->fields as $value ) {
			if ( $value->isCountable( $hasLinks, $title ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @see Content::matchMagicWord
	 */
	public function matchMagicWord( MagicWord $word ) {
		foreach ( $this->fields as $value ) {
			if ( $value->matchMagicWord( $word ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @see Content::getParserOutput
	 */
	public function getParserOutput( Title $title, $revId = null, ParserOptions $options = null, $generateHtml = true ) {
		$wikitextContent = new WikitextContent( $this->serialize( CONTENT_FORMAT_WIKITEXT ) );
		return $wikitextContent->getParserOutput( $title, $revId, $options, $generateHtml );
	}
}
