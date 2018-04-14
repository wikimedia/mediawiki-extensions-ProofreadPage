<?php

namespace ProofreadPage\Parser;

use OutOfBoundsException;
use Parser;
use ProofreadPage\Context;
use ProofreadPage\Pagination\FilePagination;
use ProofreadPageDbConnector;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Parser for the <pages> tag
 */
class PagesTagParser {

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var Context
	 */
	private $context;

	public function __construct( Parser $parser, Context $context ) {
		$this->parser = $parser;
		$this->context = $context;
	}

	/**
	 * Render a <pages> tag
	 *
	 * @param string $input the content between opening and closing tags
	 * @param array $args tags arguments
	 * @return string
	 */
	public function render( $input, array $args ) {
		// abort if this is nested <pages> call
		if ( isset( $this->parser->proofreadRenderingPages ) &&
			$this->parser->proofreadRenderingPages
		) {
			return '';
		}

		$index = array_key_exists( 'index', $args ) ? $args['index'] : null;
		$from = array_key_exists( 'from', $args ) ? $args['from'] : null;
		$to = array_key_exists( 'to', $args ) ? $args['to'] : null;
		$include = array_key_exists( 'include', $args ) ? $args['include'] : null;
		$exclude = array_key_exists( 'exclude', $args ) ? $args['exclude'] : null;
		$step = array_key_exists( 'step', $args ) ? $args['step'] : null;
		$header = array_key_exists( 'header', $args ) ? $args['header'] : null;
		$tosection = array_key_exists( 'tosection', $args ) ? $args['tosection'] : null;
		$fromsection = array_key_exists( 'fromsection', $args ) ? $args['fromsection'] : null;
		$onlysection = array_key_exists( 'onlysection', $args ) ? $args['onlysection'] : null;

		$pageTitle = $this->parser->getTitle();

		// abort if the tag is on an index or a page page
		if (
			$pageTitle->inNamespace( $this->context->getIndexNamespaceId() ) ||
			$pageTitle->inNamespace( $this->context->getPageNamespaceId() )
		) {
			return '';
		}
		// ignore fromsection and tosection arguments if onlysection is specified
		if ( $onlysection !== null ) {
			$fromsection = null;
			$tosection = null;
		}

		if ( !$index ) {
			return $this->formatError( 'proofreadpage_index_expected' );
		}

		$indexTitle = Title::makeTitleSafe( $this->context->getIndexNamespaceId(), $index );
		if ( $indexTitle === null || !$indexTitle->exists() ) {
			return $this->formatError( 'proofreadpage_nosuch_index' );
		}
		$indexContent = $this->context->getIndexContentLookup()->getIndexContentForTitle( $indexTitle );
		$pagination = $this->context->getPaginationFactory()->getPaginationForIndexTitle( $indexTitle );
		$language = $this->parser->getTargetLanguage();
		$this->parser->getOutput()->addTemplate(
			$indexTitle, $indexTitle->getArticleID(), $indexTitle->getLatestRevID()
		);
		$out = '';

		if ( $from || $to || $include ) {
			$pages = [];

			if ( $pagination instanceof FilePagination ) {
				$from = ( $from === null ) ? null : $language->parseFormattedNumber( $from );
				$to = ( $to === null ) ? null : $language->parseFormattedNumber( $to );
				$step = ( $step === null ) ? null : $language->parseFormattedNumber( $step );

				$count = $pagination->getNumberOfPages();
				if ( $count === 0 ) {
					return $this->formatError( 'proofreadpage_nosuch_file' );
				}

				if ( !$step ) {
					$step = 1;
				}
				if ( !is_numeric( $step ) || $step < 1 ) {
					return $this->formatError( 'proofreadpage_number_expected' );
				}

				$pagenums = [];

				// add page selected with $include in pagenums
				if ( $include ) {
					$list = $this->parseNumList( $include );
					if ( $list == null ) {
						return $this->formatError( 'proofreadpage_invalid_interval' );
					}
					$pagenums = $list;
				}

				// ad pages selected with from and to in pagenums
				if ( $from || $to ) {
					if ( !$from ) {
						$from = 1;
					}
					if ( !$to ) {
						$to = $count;
					}
					if ( !is_numeric( $from ) || !is_numeric( $to ) || !is_numeric( $step ) ) {
						return $this->formatError( 'proofreadpage_number_expected' );
					}

					if ( !( 1 <= $from && $from <= $to && $to <= $count ) ) {
						return $this->formatError( 'proofreadpage_invalid_interval' );
					}

					for ( $i = $from; $i <= $to; $i++ ) {
						$pagenums[$i] = $i;
					}
				}

				// remove excluded pages form $pagenums
				if ( $exclude ) {
					$excluded = $this->parseNumList( $exclude );
					if ( $excluded == null ) {
						return $this->formatError( 'proofreadpage_invalid_interval' );
					}
					$pagenums = array_diff( $pagenums, $excluded );
				}

				if ( count( $pagenums ) / $step > 1000 ) {
					return $this->formatError( 'proofreadpage_interval_too_large' );
				}

				// we must sort the array even if the numerical keys are in a good order.
				ksort( $pagenums );
				if ( end( $pagenums ) > $count ) {
					return $this->formatError( 'proofreadpage_invalid_interval' );
				}

				// Create the list of pages to translude.
				// the step system start with the smaller pagenum
				$mod = reset( $pagenums ) % $step;
				foreach ( $pagenums as $num ) {
					if ( $step == 1 || $num % $step == $mod ) {
						$pagenum = $pagination->getDisplayedPageNumber( $num )
							->getFormattedPageNumber( $language );
						$pages[] = [ $pagination->getPageTitle( $num ), $pagenum ];
					}
				}

			} else {
				$fromTitle = null;
				if ( $from ) {
					$fromTitle = Title::makeTitleSafe(
						$this->context->getPageNamespaceId(), $from
					);
				}

				$toTitle = null;
				if ( $to ) {
					$toTitle = Title::makeTitleSafe( $this->context->getPageNamespaceId(), $to );
				}

				$adding = ( $fromTitle === null );
				$i = 1;
				foreach ( $pagination as $link ) {
					if ( $fromTitle !== null && $fromTitle->equals( $link ) ) {
						$adding = true;
					}
					if ( $adding ) {
						$pagenum = $pagination->getDisplayedPageNumber( $i )
							->getFormattedPageNumber( $language );
						$pages[] = [ $link, $pagenum ];
					}
					if ( $toTitle !== null && $toTitle->equals( $link ) ) {
						$adding = false;
					}
					$i++;
				}
			}

			list( $from_page, $from_pagenum ) = reset( $pages );
			list( $to_page, $to_pagenum ) = end( $pages );

			// find which pages have quality0
			$q0_pages = [];
			if ( !empty( $pages ) ) {
				$pp = [];
				foreach ( $pages as $item ) {
					list( $page, $pagenum ) = $item;
					$pp[] = $page->getDBkey();
				}
				$cat = str_replace( ' ', '_', wfMessage( 'proofreadpage_quality0_category' )
					->inContentLanguage()->escaped() );
				$res = ProofreadPageDbConnector::getPagesNameInCategory( $pp, $cat );

				if ( $res ) {
					foreach ( $res as $o ) {
						$q0_pages[] = $o->page_title;
					}
				}
			}

			// write the output
			foreach ( $pages as $item ) {
				list( $page, $pagenum ) = $item;
				if ( in_array( $page->getDBKey(), $q0_pages ) ) {
					$is_q0 = true;
				} else {
					$is_q0 = false;
				}
				$text = $page->getPrefixedText();
				if ( !$is_q0 ) {
					$out .= '<span>{{:MediaWiki:Proofreadpage_pagenum_template|page=' . $text .
						"|num=$pagenum}}</span>";
				}
				if ( $from_page !== null && $page->equals( $from_page ) && $fromsection !== null ) {
					$ts = '';
					// Check if it is single page transclusion
					if ( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
						$ts = $tosection;
					}
					$out .= '{{#lst:' . $text . '|' . $fromsection . '|' . $ts .'}}';
				} elseif ( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
					$out .= '{{#lst:' . $text . '||' . $tosection . '}}';
				} elseif ( $onlysection !== null ) {
					$out .= '{{#lst:' . $text . '|' . $onlysection . '}}';
				} else {
					$out .= '{{:' . $text . '}}';
				}
				if ( !$is_q0 ) {
					$out .= "&#32;";
				}
			}
		} else {
			/* table of Contents */
			$header = 'toc';
			try {
				$firstpage = $pagination->getPageTitle( 1 );
				$this->parser->getOutput()->addTemplate(
					$firstpage,
					$firstpage->getArticleID(),
					$firstpage->getLatestRevID()
				);
			}
			catch ( OutOfBoundsException $e ) {
	  } // if the first page does not exists
		}

		if ( $header ) {
			if ( $header == 'toc' ) {
				$this->parser->getOutput()->is_toc = true;
			}
			$indexLinks = $indexContent->getLinksToNamespace(
				NS_MAIN, $indexTitle, true
			);
			$pageTitle = $this->parser->getTitle();
			$h_out = '{{:MediaWiki:Proofreadpage_header_template';
			$h_out .= "|value=$header";
			// find next and previous pages in list
			$indexLinksCount = count( $indexLinks );
			for ( $i = 0; $i < $indexLinksCount; $i++ ) {
				if ( $pageTitle->equals( $indexLinks[$i]->getTarget() ) ) {
					$current = '[[' . $indexLinks[$i]->getTarget()->getFullText() . '|' .
						$indexLinks[$i]->getLabel() . ']]';
					break;
				}
			}
			if ( $i > 1 ) {
				$prev = '[[' . $indexLinks[$i - 1]->getTarget()->getFullText() . '|' .
					$indexLinks[$i - 1]->getLabel() . ']]';
			}
			if ( $i + 1 < $indexLinksCount ) {
				$next = '[[' . $indexLinks[$i + 1]->getTarget()->getFullText() . '|' .
					$indexLinks[$i + 1]->getLabel() . ']]';
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
			if ( isset( $current ) ) {
				$h_out .= "|current=$current";
			}
			if ( isset( $prev ) ) {
				$h_out .= "|prev=$prev";
			}
			if ( isset( $next ) ) {
				$h_out .= "|next=$next";
			}
			if ( isset( $from_pagenum ) ) {
				$h_out .= "|from=$from_pagenum";
			}
			if ( isset( $to_pagenum ) ) {
				$h_out .= "|to=$to_pagenum";
			}
			$attributes = $this->context->getCustomIndexFieldsParser()
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
		}

		// wrap the output in a div, to prevent the parser from inserting paragraphs
		$out = "<div>\n$out\n</div>";
		$this->parser->proofreadRenderingPages = true;
		$out = $this->parser->recursiveTagParse( $out );
		$this->parser->proofreadRenderingPages = false;
		return $out;
	}

	/**
	 * Parse a comma-separated list of pages. A dash indicates an interval of pages
	 * example: 1-10,23,38
	 *
	 * @param string $input
	 * @return array|null an array of pages, or null if the input does not comply to the syntax
	 */
	public function parseNumList( $input ) {
		$input = str_replace( [ ' ', '\t', '\n' ], '', $input );
		$list = explode( ',', $input );
		$nums = [];
		foreach ( $list as $item ) {
			if ( is_numeric( $item ) ) {
				$nums[$item] = $item;
			} else {
				$interval = explode( '-', $item );
				if ( count( $interval ) != 2
					|| !is_numeric( $interval[0] )
					|| !is_numeric( $interval[1] )
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
	 * @param string $errorMsg
	 * @return string
	 */
	private function formatError( $errorMsg ) {
		return '<strong class="error">' . wfMessage( $errorMsg )->inContentLanguage()->escaped() .
			'</strong>';
	}
}
