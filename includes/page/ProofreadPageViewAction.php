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

/**
 * ViewAction for a Page: page
 */
class ProofreadPageViewAction extends ViewAction {

	/**
	 * @see FormlessAction::show()
	 */
	public function show() {
		$out = $this->getOutput();
		$title = $this->page->getTitle();
		if ( !$title->inNamespace( ProofreadPage::getPageNamespaceId() ) ||
			$out->isPrintable() ||
			$this->getContext()->getRequest()->getCheck( 'diff' )
		) {
			$this->page->view();
			return;
		}

		$wikiPage = $this->page->getPage();
		$content = $wikiPage->getContent( Revision::FOR_THIS_USER, $this->getUser() );
		if ( $content === null ||
			$content->getModel() !== CONTENT_MODEL_PROOFREAD_PAGE ||
			$content->isRedirect()
		) {
			$this->page->view();
			return;
		}
		$page = ProofreadPagePage::newFromTitle( $wikiPage->getTitle() );
		$out = $this->getOutput();

		//render HTML
		$out->addHTML( $page->getPageContainerBegin() );
		$this->page->view();
		$out->addHTML( $page->getPageContainerEnd() );

		//add modules
		$out->addModules( 'ext.proofreadpage.page' );
		$out->addModuleStyles( array(
			'ext.proofreadpage.base',
			'ext.proofreadpage.page'
		) );
		$out->addJsConfigVars( array(
			'prpPageQuality' => $content->getLevel()->getLevel()
		) );

		//custom CSS
		$css = $page->getCustomCss();
		if ( $css !== '' ) {
			$out->addInlineStyle( $css );
		}
	}
}