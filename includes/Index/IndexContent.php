<?php

namespace ProofreadPage\Index;

use Content;
use MagicWord;
use MediaWiki\Title\Title;
use ProofreadPage\Link;
use ProofreadPage\Pagination\PageList;
use Sanitizer;
use TextContent;
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
	private $fields;

	/**
	 * @var Title[]
	 */
	private $categories;

	/**
	 * @param WikitextContent[] $fields
	 * @param Title[] $categories
	 */
	public function __construct( array $fields, array $categories = [] ) {
		$this->fields = $fields;
		$this->categories = $categories;

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
	 * @return Title[]
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * @return string[]
	 */
	private function getCategoriesText() {
		return array_map( static function ( Title $title ) {
			return $title->getText();
		}, $this->categories );
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

		return empty( $this->categories );
	}

	/**
	 * @inheritDoc
	 */
	public function isValid() {
		foreach ( $this->categories as $category ) {
			if ( !$category->isValid() || !$category->inNamespace( NS_CATEGORY ) ) {
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

		$thisCategories = $this->getCategoriesText();
		sort( $thisCategories );
		$thatCategories = $that->getCategoriesText();
		sort( $thatCategories );
		return $thisCategories === $thatCategories;
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
	public function getText() {
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
	public function getSize() {
		$size = 0;

		foreach ( $this->fields as $value ) {
			$size += $value->getSize();
		}

		foreach ( $this->categories as $category ) {
			$size += strlen( $category->getText() );
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
	 * @return Link[]
	 */
	public function getLinksToNamespace( int $namespace ): array {
		$linksExtractor = new WikitextLinksExtractor();
		$links = [];
		foreach ( $this->fields as $field ) {
			$wikitext = $field->serialize( CONTENT_FORMAT_WIKITEXT );
			$links = array_merge(
				$links, $linksExtractor->getLinksToNamespace( $wikitext, $namespace )
			);
		}
		return $links;
	}
}
