<?php

namespace ProofreadPage\Parser;

use MediaWiki\Title\Title;
use OutOfBoundsException;
use ProofreadPage\Context;
use ProofreadPage\Index\IndexTemplateStyles;
use ProofreadPage\Index\WikitextLinksExtractor;
use ProofreadPage\Link;
use ProofreadPage\Page\PageLevel;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPage\Pagination\PageNumber;

/**
 * Trait for rendering pages tags.
 */
trait PagesTagRendererTrait {
	/** @inheritDoc */
	abstract public function getTitle();

	/** @inheritDoc */
	abstract public function addTrackingCategory( $category ): void;

	/** @inheritDoc */
	abstract public function getPageNamespaceId(): int;

	/** @inheritDoc */
	abstract public function getIndexNamespaceId(): int;

	/** @inheritDoc */
	abstract public function getPageNumberExpression( $expression ): string;

	/** @inheritDoc */
	abstract public function addTemplate( $title, $articleId, $revId ): void;

	/** @inheritDoc */
	abstract public function makeLink( $title, $text, $options = [] ): string;

	/** @inheritDoc */
	abstract public function addImage( $title, $timestamp, $sha1 ): void;

	/** @inheritDoc */
	abstract public function setExtensionData( $key, $value ): void;

	/** @inheritDoc */
	abstract public function getTargetLanguage();

	/** @inheritDoc */
	abstract public function preprocessWikitext( string $wikitext, Title $title ): string;

	/**
	 * Render the pages tag.
	 *
	 * @param \ProofreadPage\Context $context The ProofreadPage context
	 * @param array $args Tag arguments
	 * @return string The rendered output
	 */
	public function renderTag( $context, $args ) {
		$index = $args['index'] ?? null;
		$from = $args['from'] ?? null;
		$to = $args['to'] ?? null;
		$include = $args['include'] ?? null;
		$exclude = $args['exclude'] ?? null;
		$step = $args['step'] ?? null;
		$header = $args['header'] ?? null;
		$tosection = $args['tosection'] ?? null;
		$fromsection = $args['fromsection'] ?? null;
		$onlysection = $args['onlysection'] ?? null;

		$pageTitle = $this->getTitle();
		if (
			$pageTitle->inNamespace( $this->getIndexNamespaceId() ) ||
			$pageTitle->inNamespace( $this->getPageNamespaceId() )
		) {
			throw new ParserError( 'proofreadpage_pagesnotallowed' );
		}
		// ignore fromsection and tosection arguments if onlysection is specified
		if ( $onlysection !== null ) {
			$fromsection = null;
			$tosection = null;
		}
		if ( !$index ) {
			throw new ParserError( 'proofreadpage_index_expected' );
		}
		$indexTitle = Title::makeTitleSafe( $this->getIndexNamespaceId(), $index );
		if ( $indexTitle === null || !$indexTitle->exists() ) {
			$this->addTrackingCategory( 'proofreadpage_nosuch_index_category' );
			throw new ParserError( 'proofreadpage_nosuch_index' );
		}
		$pagination = $context->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );
		$outputWrapperClass = 'prp-pages-output';
		$language = $this->getTargetLanguage();
		$this->addTemplate(
			$indexTitle, $indexTitle->getArticleID(), $indexTitle->getLatestRevID()
		);
		$out = '';

		$placeholder = $context->getConfig()->get( 'ProofreadPagePageSeparatorPlaceholder' );

		$contentLang = null;

		if ( $from || $to || $include ) {
			$pages = [];

			if ( $pagination instanceof FilePagination ) {
				$from = ( $from === null ) ? null : $language->parseFormattedNumber( $from );
				$to = ( $to === null ) ? null : $language->parseFormattedNumber( $to );
				$step = ( $step === null ) ? null : $language->parseFormattedNumber( $step );

				$count = $pagination->getNumberOfPages();
				if ( $count === 0 ) {
					throw new ParserError( 'proofreadpage_nosuch_file' );
				}

				if ( !$step ) {
					$step = 1;
				} elseif ( !is_numeric( $step ) || $step < 1 ) {
					throw new ParserError( 'proofreadpage_number_expected' );
				} else {
					$step = (int)$step;
				}

				$pagenums = [];

				// add page selected with $include in pagenums
				if ( $include ) {
					$pagenums = $this->parseNumList( $include );
					if ( !$pagenums ) {
						throw new ParserError( 'proofreadpage_invalid_interval' );
					}
				}

				// ad pages selected with from and to in pagenums
				if ( $from || $to ) {
					$from = $from ?: '1';
					$to = $to ?: (string)$count;

					if ( !ctype_digit( $from ) || !ctype_digit( $to ) ) {
						throw new ParserError( 'proofreadpage_number_expected' );
					}
					$from = (int)$from;
					$to = (int)$to;

					if ( $from === 0 || $from > $to || $to > $count ) {
						throw new ParserError( 'proofreadpage_invalid_interval' );
					}

					for ( $i = $from; $i <= $to; $i++ ) {
						$pagenums[$i] = $i;
					}
				}

				// remove excluded pages form $pagenums
				if ( $exclude ) {
					$excluded = $this->parseNumList( $exclude );
					if ( $excluded == null ) {
						throw new ParserError( 'proofreadpage_invalid_interval' );
					}
					$pagenums = array_diff( $pagenums, $excluded );
				}

				if ( count( $pagenums ) / $step > 1000 ) {
					throw new ParserError( 'proofreadpage_interval_too_large' );
				}

				// we must sort the array even if the numerical keys are in a good order.
				ksort( $pagenums );
				if ( end( $pagenums ) > $count ) {
					throw new ParserError( 'proofreadpage_invalid_interval' );
				}

				// Create the list of pages to translude.
				// the step system start with the smaller pagenum
				$mod = reset( $pagenums ) % $step;
				foreach ( $pagenums as $num ) {
					if ( $step == 1 || $num % $step == $mod ) {
						$pageNumber = $pagination->getDisplayedPageNumber( $num );
						$pages[] = [ $pagination->getPageTitle( $num ), $pageNumber ];
					}
				}

			} else {
				$fromTitle = null;
				if ( $from ) {
					$fromTitle = Title::makeTitleSafe(
						$context->getPageNamespaceId(), $from
					);
				}

				$toTitle = null;
				if ( $to ) {
					$toTitle = Title::makeTitleSafe( $context->getPageNamespaceId(), $to );
				}

				$adding = ( $fromTitle === null );
				$i = 1;
				foreach ( $pagination as $link ) {
					if ( $fromTitle !== null && $fromTitle->equals( $link ) ) {
						$adding = true;
					}
					if ( $adding ) {
						$pageNumber = $pagination->getDisplayedPageNumber( $i );
						$pages[] = [ $link, $pageNumber ];
					}
					if ( $toTitle !== null && $toTitle->equals( $link ) ) {
						$adding = false;
					}
					$i++;
				}
			}

			/** @var PageNumber $from_pagenum */
			[ $from_page, $from_pagenum ] = reset( $pages );
			/** @var PageNumber $to_pagenum */
			[ $to_page, $to_pagenum ] = end( $pages );

			$pageQualityLevelLookup = $context->getPageQualityLevelLookup();
			$pageQualityLevelLookup->prefetchQualityLevelForTitles( array_column( $pages, 0 ) );

			$indexTs = new IndexTemplateStyles( $indexTitle );
			$out .= $indexTs->getIndexTemplateStyles( ".$outputWrapperClass" );

			// write the output
			/** @var Title $page */
			foreach ( $pages as [ $page, $pageNumber ] ) {
				if ( $contentLang !== 'mixed' ) {
					$pageLang = $page->getPageLanguage()->getHtmlCode();
					if ( !$contentLang ) {
						$contentLang = $pageLang;
					} elseif ( $contentLang !== $pageLang ) {
						$contentLang = 'mixed';
					}
				}
				$qualityLevel = $pageQualityLevelLookup->getQualityLevelForPageTitle( $page );
				$text = $page->getPrefixedText();
				if ( $qualityLevel !== PageLevel::WITHOUT_TEXT ) {
					$pagenum = $pageNumber->getRawPageNumber( $language );
					$formattedNum = $pageNumber->getFormattedPageNumber( $language );
					$out .= '<span>{{:MediaWiki:Proofreadpage_pagenum_template|page=' . $text .
						"|num=$pagenum|formatted=$formattedNum|quality=$qualityLevel}}</span>";
				}
				if ( $from_page !== null && $page->equals( $from_page ) && $fromsection !== null ) {
					$ts = '';
					// Check if it is single page transclusion
					if ( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
						$ts = $tosection;
					}
					$out .= '{{#lst:' . $text . '|' . $fromsection . '|' . $ts . '}}';
				} elseif ( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
					$out .= '{{#lst:' . $text . '||' . $tosection . '}}';
				} elseif ( $onlysection !== null ) {
					$out .= '{{#lst:' . $text . '|' . $onlysection . '}}';
				} else {
					$out .= '{{:' . $text . '}}';
				}
				if ( $qualityLevel !== PageLevel::WITHOUT_TEXT ) {
					$out .= $placeholder;
				}
			}
		} else {
			/* table of Contents */
			$header = 'toc';
			try {
				$firstpage = $pagination->getPageTitle( 1 );
				$this->addTemplate(
					$firstpage,
					$firstpage->getArticleID(),
					$firstpage->getLatestRevID()
				);
			} catch ( OutOfBoundsException ) {
				// if the first page does not exist
			}
		}

		if ( $header ) {
			if ( $header == 'toc' ) {
				$this->setExtensionData( 'proofreadpage_is_toc', true );
			}

			$indexLinks = $this->getTableOfContentLinks( $context, $indexTitle );
			$pageTitle = $this->getTitle();
			$h_out = '{{:MediaWiki:Proofreadpage_header_template';
			$h_out .= "|value=$header";
			// find next and previous pages in list
			$current = null;
			$indexLinksCount = count( $indexLinks );
			for ( $i = 0; $i < $indexLinksCount; $i++ ) {
				if ( $pageTitle->equals( $indexLinks[$i]->getTarget() ) ) {
					$current = '[[' . $indexLinks[$i]->getTarget()->getFullText() . '|' .
						$indexLinks[$i]->getLabel() . ']]';
					break;
				}
			}
			if ( $current !== null ) {
				if ( $i > 0 ) {
					$prev = '[[' . $indexLinks[$i - 1]->getTarget()->getFullText() . '|' .
						$indexLinks[$i - 1]->getLabel() . ']]';
				}
				if ( $i + 1 < $indexLinksCount ) {
					$next = '[[' . $indexLinks[$i + 1]->getTarget()->getFullText() . '|' .
						$indexLinks[$i + 1]->getLabel() . ']]';
				}
			}
			if ( isset( $args['current'] ) ) {
				$current = $args['current'];
			}
			if ( isset( $args['prev'] ) ) {
				$prev = $args['prev'];
			}
			if ( isset( $args['next'] ) ) {
				$next = $args['next'];
			}
			if ( $current !== null ) {
				$h_out .= "|current=$current";
			}
			if ( isset( $prev ) ) {
				$h_out .= "|prev=$prev";
			}
			if ( isset( $next ) ) {
				$h_out .= "|next=$next";
			}
			if ( isset( $from_pagenum ) ) {
				$formattedFrom = $from_pagenum->getFormattedPageNumber( $language );
				$h_out .= "|from=$formattedFrom";
			}
			if ( isset( $to_pagenum ) ) {
				$formattedTo = $to_pagenum->getFormattedPageNumber( $language );
				$h_out .= "|to=$formattedTo";
			}
			$indexContent = $context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
			$attributes = $context->getCustomIndexFieldsParser()
				->parseCustomIndexFieldsForHeader( $indexContent );
			foreach ( $attributes as $attribute ) {
				$key = strtolower( $attribute->getKey() );
				if ( array_key_exists( $key, $args ) ) {
					$val = $args[$key];
				} else {
					$val = $attribute->getStringValue();
				}
				$h_out .= "|$key=$val";
			}
			$h_out .= '}}';
			$out = $h_out . $out;
			// wrap the output in a div, to prevent the parser from inserting paragraphs
			// and to set the content language
		}
		$langAttr = $contentLang && $contentLang !== 'mixed'
			? " lang=\"$contentLang\""
			: "";
		return "<div class=\"$outputWrapperClass\"$langAttr>\n$out\n</div>";
	}

	/**
	 * Parse a comma-separated list of pages. A dash indicates an interval of pages
	 * example: 1-10,23,38
	 *
	 * @param string $input
	 * @return int[]|null an array of pages, or null if the input does not comply to the syntax
	 */

	/**
	 * Parse a comma-separated list of pages. A dash indicates an interval of pages
	 * example: 1-10,23,38
	 *
	 * @param string $input
	 * @return int[]|null an array of pages, or null if the input does not comply to the syntax
	 */
	public function parseNumList( $input ) {
		$input = str_replace( [ ' ', '\t', '\n' ], '', $input );
		$list = explode( ',', $input );
		$nums = [];
		foreach ( $list as $item ) {
			if ( ctype_digit( $item ) ) {
				if ( $item < 1 ) {
					return null;
				}
				$nums[$item] = $item;
			} else {
				$interval = explode( '-', $item );
				if ( count( $interval ) != 2
					|| !ctype_digit( $interval[0] )
					|| !ctype_digit( $interval[1] )
					|| $interval[0] < 1
					|| $interval[1] < $interval[0]
				) {
					return null;
				}
				for ( $i = $interval[0]; $i <= $interval[1]; $i += 1 ) {
					$nums[$i] = $i;
				}
			}
		}
		return $nums;
	}

	/**
	 * Fetches all the ns0 links from the "toc" field if it exists or considers all fields and skips the first link.
	 *
	 * @param Title $indexTitle
	 * @return Link[]
	 */

	/**
	 * Fetches all the ns0 links from the "toc" field if it exists or considers all fields and skips the first link.
	 *
	 * @param Context $context The ProofreadPage context
	 * @param Title $indexTitle
	 * @return Link[]
	 */
	protected function getTableOfContentLinks( Context $context, Title $indexTitle ): array {
		$indexContent = $context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
		$linksExtractor = new WikitextLinksExtractor();
		try {
			$toc = $context->getCustomIndexFieldsParser()->getCustomIndexFieldForDataKey( $indexContent, 'toc' );
			$wikitext = $this->preprocessWikitext( $toc->getStringValue(), $indexTitle );
			return $linksExtractor->getLinksToNamespace( $wikitext, NS_MAIN );
		} catch ( OutOfBoundsException ) {
			$links = [];
			foreach ( $indexContent->getFields() as $field ) {
				$wikitext = $this->preprocessWikitext(
					$field->serialize( CONTENT_FORMAT_WIKITEXT ),
					$indexTitle
				);
				$links = array_merge(
					$links,
					$linksExtractor->getLinksToNamespace( $wikitext, NS_MAIN )
				);
			}

			// Hack to ignore the book title
			array_shift( $links );

			return $links;
		}
	}
}
