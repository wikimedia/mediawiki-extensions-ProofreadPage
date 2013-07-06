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

class EditProofreadPagePage {

	/**
	 * Preload text layer from multipage formats
	 * @param $textbox1
	 * @param $mTitle Title
	 * @return bool
	 */
	public static function onEditFormPreloadText( &$textbox1, $mTitle ) {
		global $wgContLang;

		list( $pageNamespaceId, $indexNamespaceId ) = ProofreadPage::getPageAndIndexNamespace();
		if ( preg_match( "/^$pageNamespaceId:(.*?)\/(.*?)$/", $mTitle->getPrefixedText(), $m ) ) {
			$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
			if ( !$imageTitle ) {
				return true;
			}

			$image = wfFindFile( $imageTitle );
			if ( $image && $image->exists() ) {
				$text = $image->getHandler()->getPageText( $image, $wgContLang->parseFormattedNumber( $m[2] ) );
				if ( $text ) {
					$text = preg_replace( "/(\\\\n)/", "\n", $text );
					$text = preg_replace( "/(\\\\\d*)/", '', $text );
					$textbox1 = $text;
				}
			}
		}
		return true;
	}

	/**
	 * @param $editpage EditPage
	 * @param $request WebRequest
	 * @return bool
	 */
	public static function onEditPageImportFormData( $editpage, $request ) {
		$title = $editpage->getTitle();
		// abort if we are not a page
		if ( !$title->inNamespace( ProofreadPage::getPageNamespaceId() ) ) {
			return true;
		}
		if ( !$request->wasPosted() ) {
			return true;
		}
		$editpage->quality = $request->getVal( 'wpQuality' );
		$editpage->username = $editpage->safeUnicodeInput( $request, 'wpProofreader' );
		$editpage->header = $editpage->safeUnicodeInput( $request, 'wpHeaderTextbox' );
		$editpage->footer = $editpage->safeUnicodeInput( $request, 'wpFooterTextbox' );

		// we want to keep ordinary spaces at the end of the main textbox
		$text = rtrim( $request->getText( 'wpTextbox1' ), "\t\n\r\0\x0B" );
		$editpage->textbox1 = $request->getBool( 'safemode' )
			? $editpage->unmakesafe( $text )
			: $text;

		if( in_array( $editpage->quality, array( '0', '1', '2', '3', '4' ) ) ) {
			// format the page
			$text = '<noinclude><pagequality level="' . $editpage->quality . '" user="' . $editpage->username . '" />' .
				'<div class="pagetext">' . $editpage->header."\n\n\n</noinclude>" .
				$editpage->textbox1 .
				"<noinclude>" . $editpage->footer . '</div></noinclude>';
			$editpage->textbox1 = $text;
		} else {
			// replace deprecated template
			$text = $editpage->textbox1;
			$text = preg_replace(
				"/\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}/is",
				"<pagequality level=\"\\1\" user=\"\\3\" />",
				$text
			);
			$editpage->textbox1 = $text;
		}
		return true;
	}

	/**
	 * Check the format of pages in "Page" namespace.
	 *
	 * @param $editpage EditPage
	 * @return Boolean
	 */
	public static function onEditPageAttemptSave( $editpage ) {
		global $wgOut, $wgUser;

		$title = $editpage->mTitle;

		// abort if we are not a page
		if ( !$title->inNamespace( ProofreadPage::getPageNamespaceId() ) ) {
			return true;
		}

		$text = $editpage->textbox1;
		// parse the page
		list( $q, $username, $ptext ) = ProofreadPageParser::parsePage( $text, $title );
		if( $q == -1 ) {
			$editpage->textbox1 = $ptext;
			$q = 1;
		}

		// read previous revision, so that I know how much I need to add to pr_index
		$rev = Revision::newFromTitle( $title, false, Revision::READ_LATEST );
		if( $rev ) {
			$old_text = $rev->getText();
			list( $old_q, $old_username, $old_ptext ) = ProofreadPageParser::parsePage( $old_text, $title );
			if( $old_q != -1 ) {
				// check usernames
				if( ( $old_q != $q ) && !$wgUser->isAllowed( 'pagequality' ) ) {
					$wgOut->showErrorPage( 'proofreadpage_nologin', 'proofreadpage_nologintext' );
					return false;
				}
				if ( ( ( $old_username != $username ) || ( $old_q != $q ) ) && ( $wgUser->getName() != $username ) ) {
					$wgOut->showErrorPage( 'proofreadpage_notallowed', 'proofreadpage_notallowedtext' );
					return false;
				}
				if( ( ( $q == 4 ) && ( $old_q < 3 ) ) || ( ( $q == 4 ) && ( $old_q == 3 ) && ( $old_username == $username ) ) ) {
					$wgOut->showErrorPage( 'proofreadpage_notallowed', 'proofreadpage_notallowedtext' );
					return false;
				}
			} else {
				$old_q = 1;
			}
		} else {
			if( $q == 4 ) {
				$wgOut->showErrorPage( 'proofreadpage_notallowed', 'proofreadpage_notallowedtext' );
				return false;
			}
			$old_q = -1;
		}

		$editpage->getArticle()->new_q = $q;
		$editpage->getArticle()->old_q = $old_q;

		return true;
	}

	/**
	 * @param $article WikiPage
	 * @return bool
	 */
	public static function onArticleSaveComplete( WikiPage &$article ) {
		$title = $article->getTitle();

		// if it's an index, update pr_index table
		if ( $title->inNamespace( ProofreadPage::getIndexNamespaceId() ) ) {	//Move this part to EditProofreadIndexPage
			ProofreadPage::updatePrIndex( $article );
			return true;
		}

		// return if it is not a page
		if ( !$title->inNamespace( ProofreadPage::getPageNamespaceId() ) ) {
			return true;
		}

		/* check if there is an index */
		if ( !isset( $title->prpIndexPage ) ) {
			ProofreadPage::loadIndex( $title );
		}
		if( $title->prpIndexPage === null ) {
			return true;
		}

		/**
		 * invalidate the cache of the index page
		 */
		$title->prpIndexPage->getTitle()->invalidateCache();

		/**
		 * update pr_index iteratively
		 */
		$indexId = $title->prpIndexPage->getTitle()->getArticleID();
		$x = ProofreadIndexDbConnector::getIndexDataFromIndexPageId( $indexId );
		if( $x ) {
			$a = ProofreadIndexDbConnector::replaceIndexById( $x, $indexId, $article );
		}

		return true;
	}
}
