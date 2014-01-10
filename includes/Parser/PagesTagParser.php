<?php

namespace ProofreadPage\Parser;

use ProofreadIndexPage;
use ProofreadPage;
use ProofreadPageDbConnector;
use Title;

/**
 * @licence GNU GPL v2+
 *
 * Parser for the <pages> tag
 */
class PagesTagParser extends TagParser {

	/**
	 * @see TagParser::render
	 */
	public function render( $input, array $args ) {
		$pageNamespaceId = ProofreadPage::getPageNamespaceId();

		// abort if this is nested <pages> call
		if ( isset( $this->parser->proofreadRenderingPages ) && $this->parser->proofreadRenderingPages ) {
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

		// abort if the tag is on an index page
		if ( $this->parser->getTitle()->inNamespace( ProofreadPage::getIndexNamespaceId() ) ) {
			return '';
		}
		// abort too if the tag is in the page namespace
		if ( $this->parser->getTitle()->inNamespace( $pageNamespaceId ) ) {
			return '';
		}
		// ignore fromsection and tosection arguments if onlysection is specified
		if ( $onlysection !== null ) {
			$fromsection = null;
			$tosection = null;
		}

		if( !$index ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_index_expected' )->inContentLanguage()->escaped() . '</strong>';
		}
		$index_title = Title::makeTitleSafe( ProofreadPage::getIndexNamespaceId(), $index );
		if( !$index_title || !$index_title->exists() ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_index' )->inContentLanguage()->escaped() . '</strong>';
		}
		$indexPage = ProofreadIndexPage::newFromTitle( $index_title );

		$this->parser->getOutput()->addTemplate( $index_title, $index_title->getArticleID(), $index_title->getLatestRevID() );

		$out = '';
		$language = $this->parser->getTargetLanguage();

		list( $links, $params ) = $indexPage->getPages();

		if( $from || $to || $include ) {
			$pages = array();

			if( empty( $links ) ) {
				$from = ( $from === null ) ? null : $language->parseFormattedNumber( $from );
				$to = ( $to === null ) ? null : $language->parseFormattedNumber( $to );
				$step = ( $step === null ) ? null : $language->parseFormattedNumber( $step );

				$imageTitle = Title::makeTitleSafe( NS_IMAGE, $index );
				if ( !$imageTitle ) {
					return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
				}
				$image = wfFindFile( $imageTitle );
				if ( !( $image && $image->isMultipage() && $image->pageCount() ) ) {
					return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
				}
				$count = $image->pageCount();

				if( !$step ) {
					$step = 1;
				}
				if( !is_numeric( $step ) || $step < 1 ) {
					return '<strong class="error">' . wfMessage( 'proofreadpage_number_expected' )->inContentLanguage()->escaped() . '</strong>';
				}

				$pagenums = array();

				//add page selected with $include in pagenums
				if( $include ) {
					$list = $this->parseNumList( $include );
					if( $list  == null ) {
						return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
					}
					$pagenums = $list;
				}

				//ad pages selected with from and to in pagenums
				if( $from || $to ) {
					if( !$from ) {
						$from = 1;
					}
					if( !$to ) {
						$to = $count;
					}
					if( !is_numeric( $from ) || !is_numeric( $to )  || !is_numeric( $step ) ) {
						return '<strong class="error">' . wfMessage( 'proofreadpage_number_expected' )->inContentLanguage()->escaped() . '</strong>';
					}
					if( ($from > $to) || ($from < 1) || ($to < 1 ) || ($to > $count) ) {
						return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
					}

					for( $i = $from; $i <= $to; $i++ ) {
						$pagenums[$i] = $i;
					}
				}

				//remove excluded pages form $pagenums
				if( $exclude ) {
					$excluded = $this->parseNumList( $exclude );
					if( $excluded  == null ) {
						return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
					}
					$pagenums = array_diff( $pagenums, $excluded );
				}

				if( count($pagenums)/$step > 1000 ) {
					return '<strong class="error">' . wfMessage( 'proofreadpage_interval_too_large' )->inContentLanguage()->escaped() . '</strong>';
				}

				ksort( $pagenums ); //we must sort the array even if the numerical keys are in a good order.
				if( reset( $pagenums ) > $count ) {
					return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
				}

				//Create the list of pages to translude. the step system start with the smaller pagenum
				$mod = reset( $pagenums ) % $step;
				foreach( $pagenums as $num ) {
					if( $step == 1 || $num % $step == $mod ) {
						list( $pagenum, $links, $mode ) = ProofreadPage::pageNumber( $num, $params );
						$pages[] = array( ProofreadPage::getPageTitle( $index, $num ), $pagenum );
					}
				}

				list( $from_page, $from_pagenum ) = reset( $pages );
				list( $to_page, $to_pagenum ) = end( $pages );

			} else {
				if( $from ) {
					$adding = false;
				} else {
					$adding = true;
					$from_pagenum = $links[0][1];
				}

				$from_page = Title::makeTitleSafe( $pageNamespaceId, $from );
				$to_page = Title::makeTitleSafe( $pageNamespaceId, $to );
				for( $i = 0; $i < count( $links ); $i++ ) {
					$link = $links[$i][0];
					$pagenum = $links[$i][1];
					if( $from_page !== null && $from_page->equals( $link ) ) {
						$adding = true;
						$from_pagenum = $pagenum;
					}
					if( $adding ) {
						$pages[] = array( $link, $pagenum );
					}
					if( $to_page !== null && $to_page->equals( $link ) ) {
						$adding = false;
						$to_pagenum = $pagenum;
					}
				}
				if( !$to ) {
					$to_pagenum = $links[count( $links[1] ) - 1][1];
				}
			}
			// find which pages have quality0
			$q0_pages = array();
			if( !empty( $pages ) ) {
				$pp = array();
				foreach( $pages as $item ) {
					list( $page, $pagenum ) = $item;
					$pp[] = $page->getDBkey();
				}
				$cat = str_replace( ' ' , '_' , wfMessage( 'proofreadpage_quality0_category' )->inContentLanguage()->escaped() );
				$res = ProofreadPageDbConnector::getPagesNameInCategory( $pp, $cat );

				if( $res ) {
					foreach ( $res as $o ) {
						$q0_pages[] = $o->page_title;
					}
				}
			}

			// write the output
			foreach( $pages as $item ) {
				list( $page, $pagenum ) = $item;
				if( in_array( $page->getDBKey(), $q0_pages ) ) {
					$is_q0 = true;
				} else {
					$is_q0 = false;
				}
				$text = $page->getPrefixedText();
				if( !$is_q0 ) {
					$out .= '<span>{{:MediaWiki:Proofreadpage_pagenum_template|page=' . $text . "|num=$pagenum}}</span>";
				}
				if( $from_page !== null && $page->equals( $from_page ) && $fromsection !== null ) {
					$ts = '';
					// Check if it is single page transclusion
					if ( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
						$ts = $tosection;
					}
					$out .= '{{#lst:' . $text . '|' . $fromsection . '|' . $ts .'}}';
				} elseif( $to_page !== null && $page->equals( $to_page ) && $tosection !== null ) {
					$out .= '{{#lst:' . $text . '||' . $tosection . '}}';
				} elseif ( $onlysection !== null ) {
					$out .= '{{#lst:' . $text . '|' . $onlysection . '}}';
				} else {
					$out .= '{{:' . $text . '}}';
				}
				if( !$is_q0 ) {
					$out.= "&#32;";
				}
			}
		} else {
			/* table of Contents */
			$header = 'toc';
			if( $links == null ) {
				$firstpage = ProofreadPage::getPageTitle( $index, 1 );
			} else {
				$firstpage = $links[0][0];
			}
			if ( $firstpage !== null ) {
				$this->parser->getOutput()->addTemplate(
					$firstpage,
					$firstpage->getArticleID(),
					$firstpage->getLatestRevID()
				);
			}
		}

		if( $header ) {
			if( $header == 'toc') {
				$this->parser->getOutput()->is_toc = true;
			}
			$indexLinks = $indexPage->getLinksToMainNamespace();
			$pageTitle = $this->parser->getTitle();
			$h_out = '{{:MediaWiki:Proofreadpage_header_template';
			$h_out .= "|value=$header";
			// find next and previous pages in list
			for( $i = 0; $i < count( $indexLinks ); $i++ ) {
				if( $pageTitle->equals( $indexLinks[$i][0] ) ) {
					$current = '[[' . $indexLinks[$i][0]->getFullText() . '|' . $indexLinks[$i][1] . ']]';
					break;
				}
			}
			if( $i > 1 ) {
				$prev = '[[' . $indexLinks[$i - 1][0]->getFullText() . '|' . $indexLinks[$i - 1][1] . ']]';
			}
			if( $i + 1 < count( $indexLinks ) ) {
				$next = '[[' . $indexLinks[$i + 1][0]->getFullText() . '|' . $indexLinks[$i + 1][1] . ']]';
			}
			if( isset( $args['current'] ) ) {
				$current = $args['current'];
			}
			if( isset( $args['prev'] ) ) {
				$prev = $args['prev'];
			}
			if( isset( $args['next'] ) ) {
				$next = $args['next'];
			}
			if( isset( $current ) ) {
				$h_out .= "|current=$current";
			}
			if( isset( $prev ) ) {
				$h_out .= "|prev=$prev";
			}
			if( isset( $next ) ) {
				$h_out .= "|next=$next";
			}
			if( isset( $from_pagenum ) ) {
				$h_out .= "|from=$from_pagenum";
			}
			if( isset( $to_pagenum ) ) {
				$h_out .= "|to=$to_pagenum";
			}
			$attributes = $indexPage->getIndexEntriesForHeader();
			foreach( $attributes as $attribute ) {
				$key = strtolower( $attribute->getKey() );
				if( array_key_exists( $key, $args ) ) {
					$val = $args[$key];
				} else {
					$val = $attribute->getStringValue();
				}
				$h_out .= "|$key=$val";
			}
			$h_out .= '}}';
			$out = $h_out . $out ;
		}

		// wrap the output in a div, to prevent the parser from inserting pararaphs
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
	 * @param $input string
	 * @return array|null an array of pages, or null if the input does not comply to the syntax
	 */
	public function parseNumList($input) {
		$input = str_replace(array(' ', '\t', '\n'), '', $input);
		$list = explode( ',', $input );
		$nums = array();
		foreach( $list as $item ) {
			if( is_numeric( $item ) ) {
				$nums[$item] = $item;
			} else {
				$interval = explode( '-', $item );
				if( count( $interval ) != 2
					|| !is_numeric( $interval[0] )
					|| !is_numeric( $interval[1] )
					|| $interval[1] < $interval[0]
				) {
					return null;
				}
				for( $i = $interval[0]; $i <= $interval[1]; $i += 1 ) {
					$nums[$i] = $i;
				}
			}
		}
		return $nums;
	}
}
