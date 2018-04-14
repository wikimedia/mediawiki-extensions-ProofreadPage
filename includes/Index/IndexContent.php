<?php

namespace ProofreadPage\Index;

use Content;
use MagicWord;
use MalformedTitleException;
use Parser;
use ParserOptions;
use ProofreadPage\Context;
use ProofreadPage\Link;
use ProofreadPage\Pagination\PageList;
use Sanitizer;
use ParserOutput;
use Status;
use TextContent;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * @license GPL-2.0-or-later
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
	 * @inheritDoc
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
	 * @inheritDoc
	 */
	public function equals( Content $that = null ) {
		if ( !( $that instanceof IndexContent ) || $that->getModel() !== $this->getModel() ) {
			return false;
		}

		foreach ( $this->fields as $key => $value ) {
			if ( !array_key_exists( $key, $that->fields ) ||
				!$value->equals( $that->fields[$key] )
			) {
				return false;
			}
		}

		foreach ( $that->fields as $key => $value ) {
			if ( !array_key_exists( $key, $this->fields ) ||
				!$value->equals( $this->fields[$key] )
			) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getWikitextForTransclusion() {
		return $this->serialize( CONTENT_FORMAT_WIKITEXT );
	}

	/**
	 * @inheritDoc
	 */
	public function getNativeData() {
		return $this->serialize();
	}

	/**
	 * @inheritDoc
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$fields = [];

		foreach ( $this->fields as $key => $value ) {
			$fields[$key] = $value->preSaveTransform( $title, $user, $popts );
		}

		return new IndexContent( $fields );
	}

	/**
	 * @inheritDoc
	 */
	public function preloadTransform( Title $title, ParserOptions $popts, $params = [] ) {
		$fields = [];

		foreach ( $this->fields as $key => $value ) {
			$fields[$key] = $value->preloadTransform( $title, $popts, $params );
		}

		return new IndexContent( $fields );
	}

	/**
	 * @inheritDoc
	 */
	public function prepareSave( WikiPage $page, $flags, $parentRevId, User $user ) {
		if ( !$this->isValid() ) {
			return Status::newFatal( 'invalid-content-data' );
		}

		// Get list of pages titles
		$links = $this->getLinksToNamespace(
			Context::getDefaultContext()->getPageNamespaceId(), $page->getTitle()
		);
		$linksTitle = [];
		foreach ( $links as $link ) {
			$linksTitle[] = $link->getTarget();
		}

		if ( count( $linksTitle ) !== count( array_unique( $linksTitle ) ) ) {
			return Status::newFatal( 'proofreadpage_indexdupetext' );
		}

		return Status::newGood();
	}

	/**
	 * @inheritDoc
	 */
	public function getSize() {
		$size = 0;

		foreach ( $this->fields as $value ) {
			$size += $value->getSize();
		}

		return $size;
	}

	/**
	 * @inheritDoc
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
	 * @inheritDoc
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
	 * @inheritDoc
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		/** @var Parser $wgParser */
		global $wgParser;
		$parserHelper = new ParserHelper( $title, $options );

		// We retrieve the view template
		list( $templateText, $templateTitle ) = $parserHelper->fetchTemplateTextAndTitle(
			Title::makeTitle( NS_MEDIAWIKI, 'Proofreadpage index template' )
		);

		// We replace the arguments calls by their values
		$text = $parserHelper->expandTemplateArgs(
			$templateText,
			array_map( function ( Content $content ) {
				return $content->serialize( CONTENT_FORMAT_WIKITEXT );
			}, $this->fields )
		);

		// Force no section edit links
		$text = '__NOEDITSECTION__' . $text;

		// We do the final rendering
		$output = $wgParser->parse( $text, $title, $options, true, true, $revId );
		$output->addTemplate( $templateTitle,
			$templateTitle->getArticleID(),
			$templateTitle->getLatestRevID()
		);
	}

	/**
	 * @return PageList|null
	 */
	public function getPagelistTagContent() {
		$tagParameters = null;
		foreach ( $this->fields as $field ) {
			preg_match_all( '/<pagelist([^<]*?)\/>/is',
				$field->serialize( CONTENT_FORMAT_WIKITEXT ), $m, PREG_PATTERN_ORDER
			);
			if ( $m[1] ) {
				if ( $tagParameters === null ) {
					$tagParameters = $m[1];
				} else {
					$tagParameters = array_merge( $tagParameters, $m[1] );
				}
			}
		}
		if ( $tagParameters === null ) {
			return $tagParameters;
		}

		return new PageList( Sanitizer::decodeTagAttributes( implode( $tagParameters ) ) );
	}

	/**
	 * Returns all links in a given namespace
	 *
	 * @param int $namespace the default namespace id
	 * @param Title $title the Index: page title
	 * @param bool $withPrepossessing apply preprocessor before looking for links
	 * @return Link[]
	 */
	public function getLinksToNamespace(
		$namespace, Title $title = null, $withPrepossessing = false
	) {
		$links = [];
		foreach ( $this->fields as $field ) {
			$wikitext = $field->serialize( CONTENT_FORMAT_WIKITEXT );
			if ( $withPrepossessing ) {
				/** @var IndexContentHandler $contentHandler */
				$contentHandler = $this->getContentHandler();
				$wikitext = $contentHandler->getParser()->preprocess(
					$wikitext, $title, new ParserOptions()
				);
			}
			$links = array_merge(
				$links, $this->getLinksToNamespaceFromWikitext( $wikitext, $namespace )
			);
		}
		return $links;
	}

	private function getLinksToNamespaceFromWikitext( $wikitext, $namespace ) {
		preg_match_all( '/\[\[(.*?)(\|(.*?)|)\]\]/i', $wikitext, $textLinks, PREG_PATTERN_ORDER );
		$links = [];
		$textLinksCount = count( $textLinks[1] );
		for ( $i = 0; $i < $textLinksCount; $i++ ) {
			try {
				$title = Title::newFromTextThrow( $textLinks[1][$i] );
				if ( $title->inNamespace( $namespace ) ) {
					if ( $textLinks[3][$i] === '' ) {
						$links[] = new Link( $title, $title->getSubpageText() );
					} else {
						$links[] = new Link( $title, $textLinks[3][$i] );
					}
				}
			} catch ( MalformedTitleException $e ) {
				// We ignore invalid links
			}
		}
		return $links;
	}
}
