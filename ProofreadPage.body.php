<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup ProofreadPage
 */

/*
 @todo :
 - check unicity of the index page : when index is saved too
*/

class ProofreadPage {

	/**
	 * Returns id of Page namespace.
	 *
	 * @return integer namespace id
	 */
	public static function getPageNamespaceId() {
		static $namespace;
		if ( $namespace === null ) {
			$namespace = ProofreadPageInit::getNamespaceId( 'page' );
		}
		return $namespace;
	}

	/**
	 * Returns id of Index namespace.
	 *
	 * @return integer namespace id
	 */
	public static function getIndexNamespaceId() {
		static $namespace;
		if ( $namespace === null ) {
			$namespace = ProofreadPageInit::getNamespaceId( 'index' );
		}
		return $namespace;
	}

	/**
	 * @deprecated
	 * @return array
	 */
	public static function getPageAndIndexNamespace() {
		static $res;
		if ( $res === null ) {
			global $wgExtraNamespaces;
			$res = array(
				preg_quote( $wgExtraNamespaces[self::getPageNamespaceId()], '/' ),
				preg_quote( $wgExtraNamespaces[self::getIndexNamespaceId()], '/' ),
			);
		}
		return $res;
	}

	/**
	 * Returns titles of a page namespace page from name of scan and page number
	 * If the title with an internationalized number doesn't exist and a page with
	 * arabic number exists, the title for the arabic number is returned
	 * @param $scan string scan name
	 * @param $number int page number
	 * @return Title|null
	 */
	public static function getPageTitle( $scan, $number ) {
		global $wgContLang;

		$i18nNumber = $wgContLang->formatNum( $number, true );
		$title = Title::makeTitleSafe( self::getPageNamespaceId(), $scan . '/' . $i18nNumber );
		if ( $i18nNumber != $number && !$title->exists() ) {
			$arabicTitle = Title::makeTitleSafe( self::getPageNamespaceId(), $scan . '/' . $number );
			if ( $arabicTitle->exists() ) {
				return $arabicTitle;
			}
		}
		return $title;
	}

	/**
	 * @param $queryPages array
	 * @return bool
	 */
	public static function onwgQueryPages( &$queryPages ) {
		$queryPages[] = array( 'ProofreadPages', 'IndexPages' );
		$queryPages[] = array( 'PagesWithoutScans', 'PagesWithoutScans' );
		return true;
	}

	/**
	 * Set up our custom edition system.
	 *
	 * @param Article $article  being edited
	 * @param User $user User performing the edit
	 * @return boolean hook return value
	 */
	public static function onCustomEditor( $article, $user ) {
		global $request;
		if ( $article->getTitle()->inNamespace( self::getIndexNamespaceId() ) ) { //TODO ExternalEditor case
			$editor = new EditProofreadIndexPage( $article );
			$editor->edit();
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Set up our custom parser hooks when initializing parser.
	 *
	 * @param Parser $parser
	 * @return boolean hook return value
	 */
	public static function onParserFirstCallInit( $parser ) {
		$parser->setHook( 'pagelist', array( __CLASS__, 'renderPageList' ) );
		$parser->setHook( 'pages', array( __CLASS__, 'renderPages' ) );
		$parser->setHook( 'pagequality', array( __CLASS__, 'pageQuality' ) );
		return true;
	}

	/**
	 * Query the database to find if the current page is referred in an Index page.
	 * @param $title Title
	 */
	public static function loadIndex( $title ) {
		$title->prpIndexPage = null;
		$result = ProofreadIndexDbConnector::getRowsFromTitle( $title );

		foreach ( $result as $x ) {
			$refTitle = Title::makeTitle( $x->page_namespace, $x->page_title );
			if ( $refTitle !== null && $refTitle->inNamespace( self::getIndexNamespaceId() ) ) {
				$title->prpIndexPage = ProofreadIndexPage::newFromTitle( $refTitle );
				return;
			}
		}

		$m = explode( '/', $title->getText(), 2 );
		if ( !isset( $m[1] ) ) {
			return;
		}
		$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[0] );
		if ( $imageTitle === null ) {
			return;
		}
		$image = wfFindFile( $imageTitle );
		// if it is multipage, we use the page order of the file
		if ( $image && $image->exists() && $image->isMultipage() ) {
			$indexTitle = Title::makeTitle( self::getIndexNamespaceId(), $image->getTitle()->getText() );
			if ( $indexTitle !== null ) {
				$title->prpIndexPage = ProofreadIndexPage::newFromTitle( $indexTitle );
			}
		}
	}

	/**
	 * Append javascript variables and code to the page.
	 * @param $out OutputPage
	 * @return bool
	 */
	public static function onBeforePageDisplay( $out ) {
		$action = $out->getRequest()->getVal( 'action' );
		$isEdit = ( $action == 'submit' || $action == 'edit' );

		if ( ( !$out->isArticle() && !$isEdit ) || isset( $out->proofreadPageDone ) ) {
			return true;
		}
		$out->proofreadPageDone = true;
		$title = $out->getTitle();

		if ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			list( $page_namespace, $index_namespace ) = self::getPageAndIndexNamespace();
			if ( preg_match( "/^$page_namespace:(.*?)(\/(.*?)|)$/", $out->getTitle()->getPrefixedText(), $m ) ) {
				self::preparePage( $out, $m, $isEdit );
			}
		} elseif ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			if( !$isEdit ) {
				$out->addModules( 'ext.proofreadpage.base' );
			}
		} elseif ( $title->inNamespace( NS_MAIN ) ) {
			self::prepareArticle( $out );
		}

		return true;
	}

	/**
	 * @param $out OutputPage
	 * @param $m
	 * @param $isEdit
	 * @return bool
	 */
	private static function preparePage( $out, $m, $isEdit ) {
		global $wgUser, $wgExtensionAssetsPath, $wgContLang;

		$pageTitle = $out->getTitle();

		if ( !isset( $pageTitle->prpIndexPage ) ) {
			self::loadIndex( $pageTitle );
		}

		$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
		if ( !$imageTitle ) {
			return true;
		}

		$fileName = null;
		$filePage = null;

		$image = wfFindFile( $imageTitle );
		if ( $image && $image->exists() ) {
			$fileName = $imageTitle->getPrefixedText();

			$width = $image->getWidth();
			$height = $image->getHeight();
			if ( $m[2] ) {
				$filePage = $wgContLang->parseFormattedNumber( $m[3] );

				$params = array( 'width' => $width, 'page' => $filePage );
				$handler = $image->getHandler();
				if ( $handler && $handler->normaliseParams( $image, $params ) ) {
					$thumbName = $image->thumbName( $params );
					$fullURL = $image->getThumbUrl( $thumbName );
				} else {
					$fullURL = $image->getViewURL();
				}
			} else {
				$fullURL = $image->getViewURL();
			}
			$scan_link = Html::element( 'a',
				array(
					'href' => $fullURL,
					'title' =>  $out->msg( 'proofreadpage_image' )->text()
				),
				$out->msg( 'proofreadpage_image' )->text()
			);
		} else {
			$width = 0;
			$height = 0;
			$fullURL = '';
			$scan_link = '';
		}

		$path = $wgExtensionAssetsPath . '/ProofreadPage';
		$jsVars = array(
			'proofreadPageWidth' => intval( $width ),
			'proofreadPageHeight' => intval( $height ),
			'proofreadPageURL' => $fullURL,
			'proofreadPageFileName' => $fileName,
			'proofreadPageFilePage' => $filePage,
			'proofreadPageIsEdit' => intval( $isEdit ),
			'proofreadPageScanLink' => $scan_link,
			'proofreadPageAddButtons' => $wgUser->isAllowed( 'pagequality' ),
			'proofreadPageUserName' => $wgUser->getName(),
			'proofreadPageIndexLink' => '',
			'proofreadPageNextLink' => '',
			'proofreadPagePrevLink' => '',
			'proofreadPageEditWidth' => '',
			'proofreadPageHeader' => '',
			'proofreadPageFooter' => '',
			'proofreadPageCss' => ''
		);

		$indexPage = $out->getTitle()->prpIndexPage;
		if ( $indexPage !== null ) {
			list( $prevTitle, $nextTitle ) = $indexPage->getPreviousAndNextPages( $out->getTitle() );
			if ( $prevTitle !== null ) {
				$jsVars['proofreadPagePrevLink'] = Linker::link( $prevTitle,
					Html::element( 'img', array( 'src' => $path . '/leftarrow.png',
						'alt' => $out->msg( 'proofreadpage_nextpage' )->text(), 'width' => 15, 'height' => 15 ) ),
					array( 'title' => $out->msg( 'proofreadpage_prevpage' )->text() ) );
			}
			if ( $nextTitle !== null ) {
				$jsVars['proofreadPageNextLink'] = Linker::link( $nextTitle,
					Html::element( 'img', array( 'src' => $path . '/rightarrow.png',
						'alt' => $out->msg( 'proofreadpage_nextpage' )->text(), 'width' => 15, 'height' => 15 ) ),
					array( 'title' => $out->msg( 'proofreadpage_nextpage' )->text() ) );
			}
			$jsVars['proofreadPageIndexLink'] = Linker::link( $indexPage->getTitle(),
				Html::element( 'img', array(	'src' => $path . '/uparrow.png',
					'alt' => $out->msg( 'proofreadpage_index' )->text(), 'width' => 15, 'height' => 15 ) ),
				array( 'title' => $out->msg( 'proofreadpage_index' )->text() ) );

			list( $header, $footer, $css, $editWidth ) = $indexPage->getIndexDataForPage( $pageTitle );
			$jsVars['editWidth'] = $editWidth;
			$jsVars['proofreadPageHeader'] = $header;
			$jsVars['proofreadPageFooter'] = $footer;
			$jsVars['proofreadPageCss'] = $css;
		}

		$out->addJsConfigVars( $jsVars );

		$out->addModules( 'ext.proofreadpage.page' );

		return true;
	}

	/**
	 * Hook function
	 * @param $page_ids
	 * @param $colours
	 * @return bool
	 */
	public static function onGetLinkColours( $page_ids, &$colours ) {
		global $wgTitle;
		if ( !isset( $wgTitle ) ) {
			return true;
		}
		self::getLinkColours( $page_ids, $colours );
		return true;
	}

	/**
	 * Return the quality colour codes to pages linked from an index page
	 * @param $page_ids array
	 * @param $colours array
	 */
	private static function getLinkColours( $page_ids, &$colours ) {
		global $wgTitle;

		$page_namespace_id = self::getPageNamespaceId();
		$page_namespace = MWNamespace::getCanonicalName( $page_namespace_id );
		$in_index_namespace = $wgTitle->inNamespace( self::getIndexNamespaceId() );

		$values = array();
		foreach ( $page_ids as $id => $pdbk ) {
			$title = Title::newFromText( $pdbk );
			// consider only link in page namespace
			if ( $title->inNamespace( $page_namespace_id ) ) {
				if ( $in_index_namespace ) {
					$colours[$pdbk] = 'quality1 prp-pagequality-1';
				} else {
					$colours[$pdbk] = 'prp-pagequality-1';
				}
				$values[] = intval( $id );
			}
		}

		// Get the names of the quality categories.  Replaces earlier code which
		// called wfMessage()->inContentLanguagE() 5 times for each page.
		// ISSUE: Should the number of quality levels be adjustable?
		// ISSUE 2: Should this array be saved as a member variable?
		// How often is this code called anyway?
		$qualityCategories = array();
		for ( $i = 0; $i < 5; $i++ ) {
			$cat = Title::makeTitleSafe( NS_CATEGORY, wfMessage( "proofreadpage_quality{$i}_category" )->inContentLanguage()->text() );
			if ( $cat ) {
				if ( $in_index_namespace ) {
					$qualityCategories[$cat->getDBkey()] = 'quality' . $i . ' prp-pagequality-' . $i;
				} else {
					$qualityCategories[$cat->getDBkey()] = 'prp-pagequality-' . $i;
				}
			}
		}

		if ( count( $values ) ) {
			$res = ProofreadPageDbConnector::getCategoryNamesForPageIds( $values );
			foreach ( $res as $x ) {
				$pdbk = $page_ids[$x->cl_from];
				if ( array_key_exists( $x->cl_to, $qualityCategories ) ) {
					$colours[$pdbk] = $qualityCategories[$x->cl_to];
				}
			}
		}
	}

	/**
	 * @param $imgpage ImagePage
	 * @param $out OutputPage
	 * @return bool
	 */
	public static function onImageOpenShowImageInlineBefore( &$imgpage, &$out ) {
		list( $page_namespace, $index_namespace ) = self::getPageAndIndexNamespace();
		$image = $imgpage->getFile();
		if ( !$image->isMultipage() ) {
			return true;
		}
		$name = $image->getTitle()->getText();
		$title = Title::makeTitle( self::getIndexNamespaceId(), $name );
		$link = Linker::link( $title, $out->msg( 'proofreadpage_image_message' )->text(), array(), array(), 'known' );
		$out->addHTML( $link );
		return true;
	}

	/**
	 * @param $i int
	 * @param $args array
	 * @return array
	 */
	public static function pageNumber( $i, $args ) {
		global $wgContLang;
		$mode = 'normal'; // default
		$offset = 0;
		$links = true;
		foreach ( $args as $num => $param ) {
			if ( ( preg_match( "/^([0-9]*)to([0-9]*)$/", $num, $m ) && ( $i >= $m[1] && $i <= $m[2] ) )
			     || ( is_numeric( $num ) && ( $i == $num ) ) ) {
				$params = explode( ';', $param );
				foreach ( $params as $iparam ) {
					switch( $iparam ) {
					case 'roman':
						$mode = 'roman';
						break;
					case 'highroman':
						$mode = 'highroman';
						break;
					case 'empty':
						$links = false;
						break;
					default:
						if ( !is_numeric( $iparam ) ) {
							$mode = $iparam;
						}
					}
				}
			}

			if ( is_numeric( $num ) && ( $i >= $num ) )  {
				$params = explode( ';', $param );
				foreach ( $params as $iparam ) {
					if ( is_numeric( $iparam ) ) {
						$offset = $num - $iparam;
					}
				}
			}

		}
		$view = ( $i - $offset );
		switch( $mode ) {
		case 'highroman':
			$view = Language::romanNumeral( $view );
			break;
		case 'roman':
				$view = strtolower( Language::romanNumeral( $view ) );
			break;
		case 'normal':
		case 'empty':
			$view = '' . $wgContLang->formatNum( $view, true );
			break;
		default:
			$view = $mode;
		}
		return array( $view, $links, $mode );
	}

	/**
	 * Add the pagequality category.
	 * @todo FIXME: display whether page has been proofread by the user or by someone else
	 * @param $input
	 * @param $args array
	 * @param $parser Parser
	 * @return string
	 */
	public static function pageQuality( $input, $args, $parser ) {

		if ( !$parser->getTitle()->inNamespace( self::getPageNamespaceId() ) ) {
			return '';
		}

		$q = $args['level'];
		if( !in_array( $q, array( '0', '1', '2', '3', '4' ) ) ) {
			return '';
		}
		$message = "<div id=\"pagequality\" width=100% class=quality$q>" .
			wfMessage( "proofreadpage_quality{$q}_message" )->inContentLanguage()->text() . '</div>';
		$out = '__NOEDITSECTION__[[Category:' .
			wfMessage( "proofreadpage_quality{$q}_category" )->inContentLanguage()->text() . ']]';
		return $parser->recursiveTagParse( $out . $message );
	}

	/**
	 * Parser hook for index pages
	 * Display a list of coloured links to pages
	 * @param $input
	 * @param $args array
	 * @param $parser Parser
	 * @return string
	 */
	public static function renderPageList( $input, $args, $parser ) {
		global $wgContLang;

		$title = $parser->getTitle();
		if ( !$title->inNamespace( self::getIndexNamespaceId() ) ) {
			return '';
		}
		$imageTitle = Title::makeTitleSafe( NS_IMAGE, $title->getText() );
		if ( !$imageTitle ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
		}

		$image = wfFindFile( $imageTitle );
		if ( !( $image && $image->isMultipage() && $image->pageCount() ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_file' )->inContentLanguage()->escaped() . '</strong>';
		}

		$return = '';

		$name = $imageTitle->getDBkey();
		$count = $image->pageCount();

		$from = array_key_exists( 'from', $args ) ? $args['from'] : 1;
		$to = array_key_exists( 'to', $args ) ? $args['to'] : $count;

		if( !is_numeric( $from ) || !is_numeric( $to ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_number_expected' )->inContentLanguage()->escaped() . '</strong>';
		}
		if( ( $from > $to ) || ( $from < 1 ) || ( $to < 1 ) || ( $to > $count ) ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_invalid_interval' )->inContentLanguage()->escaped() . '</strong>';
		}

		for ( $i = $from; $i < $to + 1; $i++ ) {
			list( $view, $links, $mode ) = self::pageNumber( $i, $args );

			if ( $mode == 'highroman' || $mode == 'roman' ) {
				$view = '&#160;' . $view;
			}

			$n = strlen( $count ) - mb_strlen( $view );
			if ( $n && ( $mode == 'normal' || $mode == 'empty' ) ) {
				$txt = '<span style="visibility:hidden;">';
				$pad = $wgContLang->formatNum( 0, true );
				for ( $j = 0; $j < $n; $j++ ) {
					$txt = $txt . $pad;
				}
				$view = $txt . '</span>' . $view;
			}
			$title = self::getPageTitle( $name, $i );

			if ( !$links || !$title ) {
				$return .= $view . ' ';
			} else {
				$return .= '[[' . $title->getPrefixedText() . '|' . $view . ']] ';
			}
		}
		$return = $parser->recursiveTagParse( $return );
		return $return;
	}

	/**
	 * Parser hook that includes a list of pages.
	 *  parameters : index, from, to, header
	 * @param $input
	 * @param $args array
	 * @param $parser Parser
	 * @return string
	 */
	public static function renderPages( $input, $args, $parser ) {
		global $wgContLang;

		$pageNamespaceId = self::getPageNamespaceId();

		// abort if this is nested <pages> call
		if ( isset( $parser->proofreadRenderingPages ) && $parser->proofreadRenderingPages ) {
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
		if ( $parser->getTitle()->inNamespace( self::getIndexNamespaceId() ) ) {
			return '';
		}
		// abort too if the tag is in the page namespace
		if ( $parser->getTitle()->inNamespace( $pageNamespaceId ) ) {
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
		$index_title = Title::makeTitleSafe( self::getIndexNamespaceId(), $index );
		if( !$index_title || !$index_title->exists() ) {
			return '<strong class="error">' . wfMessage( 'proofreadpage_nosuch_index' )->inContentLanguage()->escaped() . '</strong>';
		}
		$indexPage = ProofreadIndexPage::newFromTitle( $index_title );

		$parser->getOutput()->addTemplate( $index_title, $index_title->getArticleID(), $index_title->getLatestRevID() );

		$out = '';

		list( $links, $params ) = $indexPage->getPages();

		if( $from || $to || $include ) {
			$pages = array();

			if( $links === null ) {
				$from = ( $from === null ) ? null : $wgContLang->parseFormattedNumber( $from );
				$to = ( $to === null ) ? null : $wgContLang->parseFormattedNumber( $to );
				$step = ( $step === null ) ? null : $wgContLang->parseFormattedNumber( $step );

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
					$list = self::parse_num_list( $include );
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
					$excluded = self::parse_num_list( $exclude );
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
						list( $pagenum, $links, $mode ) = self::pageNumber( $num, $params );
						$pages[] = array( self::getPageTitle( $index, $num ), $pagenum );
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
				$firstpage = self::getPageTitle( $index, 1 );
			} else {
				$firstpage = $links[0][0];
			}
			if ( $firstpage !== null ) {
				$parser->getOutput()->addTemplate(
					$firstpage,
					$firstpage->getArticleID(),
					$firstpage->getLatestRevID()
				);
			}
		}

		if( $header ) {
			if( $header == 'toc') {
				$parser->getOutput()->is_toc = true;
			}
			$indexLinks = $indexPage->getLinksToMainNamespace();
			$pageTitle = $parser->getTitle();
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
		$parser->proofreadRenderingPages = true;
		$out = $parser->recursiveTagParse( $out );
		$parser->proofreadRenderingPages = false;
		return $out;
	}

	/**
	 * Parse a comma-separated list of pages. A dash indicates an interval of pages
	 * example: 1-10,23,38
	 * Return an array of pages, or null if the input does not comply to the syntax
	 * @param $input string
	 * @return array|null
	 */
	private static function parse_num_list($input) {
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

	/**
	 * Set is_toc flag (true if page is a table of contents)
	 * @param $outputPage OutputPage
	 * @param $parserOutput ParserOutput
	 * @return bool
	 */
	public static function onOutputPageParserOutput( $outputPage, $parserOutput ) {
		if( isset( $parserOutput->is_toc ) ) {
			$outputPage->is_toc = $parserOutput->is_toc;
		} else {
			$outputPage->is_toc = false;
		}
		return true;
	}

	/**
	 * Try to parse a page.
	 * Return quality status of the page and username of the proofreader
	 * Return -1 if the page cannot be parsed
	 */
	public static function parsePage( $text, $title ) {
		global $wgUser;

		$username = $wgUser->getName();
		$page_regexp = "/^<noinclude>(.*?)<\/noinclude>(.*?)<noinclude>(.*?)<\/noinclude>$/s";
		if( !preg_match( $page_regexp, $text, $m ) ) {
			self::loadIndex( $title );
			if ( $title->prpIndexPage !== null ) {
				list( $header, $footer, $css, $editWidth ) = $title->prpIndexPage->getIndexDataForPage( $title );
			} else {
				$header = '';
				$footer = '';
			}
			$new_text = "<noinclude><pagequality level=\"1\" user=\"$username\" /><div class=\"pagetext\">"
				."$header\n\n\n</noinclude>$text<noinclude>\n$footer</div></noinclude>";
			return array( -1, null, $new_text );
		}

		$header = $m[1];
		$body = $m[2];
		$footer = $m[3];

		$header_regexp = "/^<pagequality level=\"(0|1|2|3|4)\" user=\"(.*?)\" \/>/";
		if( preg_match( $header_regexp, $header, $m2 ) ) {
			return array( intval($m2[1]), $m2[2], null );
		}

		$old_header_regexp = "/^\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}/is";
		if( preg_match( $old_header_regexp, $header, $m3 ) ) {
			return array( intval($m3[1]), $m3[3], null );
		}

		$new_text = "<noinclude><pagequality level=\"1\" user=\"$username\" />"
			. "$header\n\n\n</noinclude>$body<noinclude>\n$footer</noinclude>";
		return array( -1, null, $new_text );
	}

	/**
	 * Updates index data for an index referencing the specified page.
	 * @param $title Title: page title object
	 * @param $deleted Boolean: indicates whether the page was deleted
	 */
	private static function updateIndexOfPage( $title, $deleted = false ) {
		self::loadIndex( $title );
		if ( $title->prpIndexPage !== null ) {
			$indexTitle = $title->prpIndexPage->getTitle();
			$indexTitle->invalidateCache();
			$index = new Article( $indexTitle );
			if ( $index ) {
				self::update_pr_index( $index, $deleted ? $title : null );
			}
		}
	}

	/**
	 * if I delete a page, I need to update the index table
	 * if I delete an index page too...
	 *
	 * @param $article Article object
	 * @return Boolean: true
	 */
	public static function onArticleDelete( $article ) {
		$title = $article->getTitle();

		// Process Index removal.
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			ProofreadIndexDbConnector::removeIndexData( $article->getId() );

		// Process Page removal.
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $title, true );
		}

		return true;
	}

	/**
	 * @param $title Title
	 * @param $create
	 * @return bool
	 */
	public static function onArticleUndelete( $title, $create ) {
		// Process Index restoration.
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			$index = new Article( $title );
			if ( $index ) {
				self::update_pr_index( $index );
			}

		// Process Page restoration.
		} elseif ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $title );
		}

		return true;
	}

	/**
	 * @param $form
	 * @param $ot Title
	 * @param $nt Title
	 * @return bool
	 */
	public static function onSpecialMovepageAfterMove( $form, $ot, $nt ) {
		if ( $ot->inNamespace( self::getPageNamespaceId() ) ) {
			self::updateIndexOfPage( $ot );
		} elseif ( $ot->inNamespace( self::getIndexNamespaceId() )
			  && !$nt->inNamespace( self::getIndexNamespaceId() ) ) {
			// The page is moved out of the Index namespace.
			// Remove all index data associated with that page.

			// $nt is used here on purpose, as we need to get the page id.
			// There is no page under the old title or it is a redirect.
			$article = new Article( $nt );
			if( $article ) {
				ProofreadIndexDbConnector::removeIndexData( $article->getId() );
			}
		}

		if ( $nt->inNamespace( self::getPageNamespaceId() ) ) {
			self::loadIndex( $nt );
			if( $nt->prpIndexPage !== null
				&& ( !isset( $ot->prpIndexPage ) || ( $nt->prpIndexPage->getTitle()->equals( $ot->prpIndexPage->getTitle() ) ) ) ) {
				self::updateIndexOfPage( $nt );
			}
		} elseif ( $nt->inNamespace( self::getIndexNamespaceId() ) ) {
			// Update index data.
			$article = new Article( $nt );
			if( $article ) {
				self::update_pr_index( $article );
			}
		}
		return true;
	}

	/**
	 * When an index page is created or purged, recompute pr_index values
	 * @param $article Article
	 * @return bool
	 */
	public static function onArticlePurge( $article ) {
		$title = $article->getTitle();
		if ( $title->inNamespace( self::getIndexNamespaceId() ) ) {
			self::update_pr_index( $article );
			return true;
		}
		return true;
	}

	/**
	 * Update the pr_index entry of an article
	 * @param $index Article
	 * @param $deletedpage Title|null
	 */
	public static function update_pr_index( $index, $deletedPage = null ) {
		$indexTitle = $index->getTitle();
		$indexId = $index->getID();
		$pageNamespaceId = self::getPageNamespaceId();

		$n = 0;

		// read the list of pages
		$pages = array();
		list( $links, $params ) = ProofreadIndexPage::newFromTitle( $indexTitle )->getPages();
		if( $links == null ) {
			$imageTitle = Title::makeTitleSafe( NS_IMAGE, $indexTitle->getText() );
			if ( $imageTitle ) {
				$image = wfFindFile( $imageTitle );
				if ( $image && $image->isMultipage() && $image->pageCount() ) {
					$n = $image->pageCount();
					for ( $i = 1; $i <= $n; $i++ ) {
						$page = self::getPageTitle( $indexTitle->getText(), $i );
						if( $page !== null && ( $deletedPage === null || !$page->equals( $deletedPage ) ) ) {
							array_push( $pages, $page->getDBKey() );
						}
					}
				}
			}
		} else {
			$n = count( $links );
			for ( $i = 0; $i < $n; $i++ ) {
				$page = $links[$i][0];
				if( $deletedPage === null || !$page->equals( $deletedPage ) ) {
					array_push( $pages, $page->getDBKey() );
				}
			}
		}

		if( !count( $pages ) ) {
			return;
		}

		$total = ProofreadPageDbConnector::getNumberOfExistingPagesFromPageTitle( $pages );

		if( $total === null ) {
			return;
		}

		// proofreading status of pages
		$queryArr = array(
			'tables' => array( 'page', 'categorylinks' ),
			'fields' => array( 'COUNT(page_id) AS count' ),
			'conds' => array( 'cl_to' => '', 'page_namespace' => self::getPageNamespaceId(), 'page_title' => $pages ),
			'joins' => array( 'categorylinks' => array( 'LEFT JOIN', 'cl_from=page_id' ) )
		);

		$n0 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality0_category' );
		$n2 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality2_category' );
		$n3 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality3_category' );
		$n4 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality4_category' );
		$n1 = $total - $n0 - $n2 - $n3 - $n4;

		$replace = ProofreadIndexDbConnector::setIndexData( $n, $n0, $n1, $n2, $n3, $n4, $indexId );
	}

	/**
	 * In main namespace, display the proofreading status of transcluded pages.
	 *
	 * @param $out OutputPage object
	 * @return bool
	 */
	private static function prepareArticle( $out ) {
		$id = $out->getTitle()->getArticleID();
		if( $id == -1 ) {
			return true;
		}
		$pageNamespaceId = self::getPageNamespaceId();
		$indexNamespaceId = self::getIndexNamespaceId();
		if( $pageNamespaceId == null || $indexNamespaceId == null ) {
			return true;
		}

		// find the index page
		$indextitle = ProofreadPageDbConnector::getIndexTitleForPageId( $id );

		if( isset( $out->is_toc ) && $out->is_toc ) {
			if ( $indextitle ) {
				$row = ProofreadIndexDbConnector::getIndexDataFromIndexTitle( $indextitle );
				if( $row ) {
					$n0 = $row->pr_q0;
					$n1 = $row->pr_q1;
					$n2 = $row->pr_q2;
					$n3 = $row->pr_q3;
					$n4 = $row->pr_q4;
					$n = $row->pr_count;
					$ne = $n - ( $n0 + $n1 + $n2 + $n3 + $n4 );
				}
			}
		} else {
			// count transclusions from page namespace
			$n = ProofreadPageDbConnector::countTransclusionFromPageId( $id );
			if( $n === null ) {
				return true;
			}

			// find the proofreading status of transclusions
			$queryArr = array(
				'tables' => array( 'templatelinks', 'page', 'categorylinks' ),
				'fields' => array( 'COUNT(page_id) AS count' ),
				'conds' => array( 'tl_from' => $id, 'tl_namespace' => $pageNamespaceId, 'cl_to' => '' ),
				'joins' => array(
					'page' => array( 'LEFT JOIN', 'page_title=tl_title AND page_namespace=tl_namespace' ),
					'categorylinks' => array( 'LEFT JOIN', 'cl_from=page_id' ),
				)
			);

			$n0 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality0_category' );
			$n2 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality2_category' );
			$n3 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality3_category' );
			$n4 = ProofreadPageDbConnector::queryCount( $queryArr, 'proofreadpage_quality4_category' );
			// quality1 is the default value
			$n1 = $n - $n0 - $n2 - $n3 - $n4;
			$ne = 0;
		}

		if( $n == 0 ) {
			return true;
		}

		if( $indextitle ) {
			$nt = Title::makeTitleSafe( $indexNamespaceId, $indextitle );
			$indexlink = Linker::link( $nt, $out->msg( 'proofreadpage_source' )->text(),
						array( 'title' => $out->msg( 'proofreadpage_source_message' )->text() ) );
			$out->addJsConfigVars( 'proofreadpage_source_href', $indexlink );
			$out->addModules( 'ext.proofreadpage.article' );
		}

		$q0 = $n0 * 100 / $n;
		$q1 = $n1 * 100 / $n;
		$q2 = $n2 * 100 / $n;
		$q3 = $n3 * 100 / $n;
		$q4 = $n4 * 100 / $n;
		$qe = $ne * 100 / $n;
		$void_cell = $ne ? "<td align=center style='border-style:dotted;border-width:1px;' width=\"{$qe}\"></td>" : '';
		$output = "<table class=\"pr_quality\" style=\"line-height:40%;\" border=0 cellpadding=0 cellspacing=0 ><tr>
<td align=center >&#160;</td>
<td align=center class='quality4' width=\"$q4\"></td>
<td align=center class='quality3' width=\"$q3\"></td>
<td align=center class='quality2' width=\"$q2\"></td>
<td align=center class='quality1' width=\"$q1\"></td>
<td align=center class='quality0' width=\"$q0\"></td>
$void_cell
</tr></table>";
		$out->setSubtitle( $out->getSubtitle() . $output );
		return true;
	}

	/**
	 * Add ProofreadPage preferences to the preferences menu
	 * @param $user
	 * @param $preferences array
	 * @return bool
	 */
	public static function onGetPreferences( $user, &$preferences ) {

		//Show header and footer fields when editing in the Page namespace
		$preferences['proofreadpage-showheaders'] = array(
			'type'           => 'toggle',
			'label-message'  => 'proofreadpage-preferences-showheaders-label',
			'section'        => 'editing/advancedediting',
		);

		//Use horizontal layout when editing in the Page namespace
		$preferences['proofreadpage-horizontal-layout'] = array(
			'type'           => 'toggle',
			'label-message'  => 'proofreadpage-preferences-horizontal-layout-label',
			'section'        => 'editing/advancedediting',
		);

		return true;
	}

	/**
	 * Adds an image link from pages in Page namespace, so they appear
	 * in the file usage.
	 * @param $linksUpdate LinksUpdate
	 * @return bool
	 */
	public static function onLinksUpdateConstructed( $linksUpdate ) {
		$title = $linksUpdate->getTitle();
		if ( $title->inNamespace( self::getPageNamespaceId() ) ) {
			// Extract title from multipaged documents
			$parts = explode( '/', $title->getText(), 2 );
			$imageTitle = Title::makeTitle( NS_FILE, $parts[0] );
			// Add to list of images
			$linksUpdate->mImages[$imageTitle->getDBkey()] = 1;
		}
		return true;
	}


	/**
	 * Adds canonical namespaces.
	 */
	public static function addCanonicalNamespaces( &$list ) {
		$list[self::getPageNamespaceId()] = 'Page';
		$list[self::getPageNamespaceId() + 1] = 'Page_talk';
		$list[self::getIndexNamespaceId()] = 'Index';
		$list[self::getIndexNamespaceId() + 1] = 'Index_talk';
		return true;
	}
}
