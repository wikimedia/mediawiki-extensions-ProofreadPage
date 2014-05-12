<?php

namespace ProofreadPage\Page;

use ProofreadPage\Context;
use ProofreadPagePage;
use Revision;
use ViewAction;

/**
 * @licence GNU GPL v2+
 *
 * ViewAction for a Page: page
 */
class PageViewAction extends ViewAction {

	/**
	 * @see FormlessAction::show()
	 */
	public function show() {
		$out = $this->getOutput();
		$title = $this->page->getTitle();
		if ( !$title->inNamespace( Context::getDefaultContext()->getPageNamespaceId() ) || $out->isPrintable() || $this->getContext()->getRequest()->getCheck( 'diff' ) ) {
			$this->page->view();

			return;
		}

		$wikiPage = $this->page->getPage();
		$content = $wikiPage->getContent( Revision::FOR_THIS_USER, $this->getUser() );
		if ( $content === null || $content->getModel() !== CONTENT_MODEL_PROOFREAD_PAGE || $content->isRedirect()
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
			'ext.proofreadpage.base', 'ext.proofreadpage.page'
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