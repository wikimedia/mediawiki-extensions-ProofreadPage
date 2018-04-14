<?php

namespace ProofreadPage\Index;

use Content;
use ContentHandler;
use MWContentSerializationException;
use Parser;
use PPFrame;
use ProofreadPage\Context;
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

	public function __construct( $modelId = CONTENT_MODEL_PROOFREAD_INDEX ) {
		$this->wikitextContentHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
		$this->parser = $this->buildParser();

		parent::__construct( $modelId, [ CONTENT_FORMAT_WIKITEXT ] );
	}

	/**
	 * Warning: should not be used outside of IndexContent
	 * @return Parser
	 */
	public function getParser() {
		return $this->parser;
	}

	private function buildParser() {
		$parser = new Parser();
		$parser->startExternalParse(
			null, $this->makeParserOptions( 'canonical' ), Parser::OT_PLAIN
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

	private function serializeContentInWikitext( Content $content ) {
		if ( $content instanceof IndexRedirectContent ) {
			return '#REDIRECT [[' . $content->getRedirectTarget()->getFullText() . ']]';
		}
		if ( !( $content instanceof IndexContent ) ) {
			throw new MWContentSerializationException(
				'IndexContentHandler could only serialize IndexContent'
			);
		}

		$text = "{{:MediaWiki:Proofreadpage_index_template";

		/** @var WikitextContent $value */
		foreach ( $content->getFields() as $key => $value ) {
			$text .= "\n|" . $key . "=" . $value->serialize();
		}

		return $text . "\n}}";
	}

	/**
	 * @inheritDoc
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return $this->unserializeContentInWikitext( $text );
	}

	private function unserializeContentInWikitext( $text ) {
		$fullWikitext = new WikitextContent( $text );
		if ( $fullWikitext->isRedirect() ) {
			return new IndexRedirectContent( $fullWikitext->getRedirectTarget() );
		}

		$dom = $this->parser->preprocessToDom( $text );
		$dom = $dom->getFirstChild();
		if ( $dom === false ) {
			return new IndexContent( [] );
		}

		$frame = $this->parser->getPreprocessor()->newFrame();
		$childFrame = $frame->newChild( $dom->getChildrenOfType( 'part' ) );
		$values = [];
		foreach ( $childFrame->namedArgs as $varName => $value ) {
			$value = $this->parser->mStripState->unstripBoth(
				$frame->expand( $value, PPFrame::RECOVER_ORIG )
			);
			if ( substr( $value, -1 ) === "\n" ) { // We strip one "\n"
				$value = substr( $value, 0, -1 );
			}
			$values[$varName] = new WikitextContent( $value );
		}
		return new IndexContent( $values );
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
	protected function getDiffEngineClass() {
		return IndexDifferenceEngine::class;
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

		return new IndexContent( $myFields );
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
}
