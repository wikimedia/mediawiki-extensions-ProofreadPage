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
		if ( !$title->inNamespace( Context::getDefaultContext()->getPageNamespaceId() ) ||
			$out->isPrintable() || $this->getContext()->getRequest()->getCheck( 'diff' )
		) {
			$this->page->view();

			return;
		}

		$wikiPage = $this->page->getPage();
		$content = $wikiPage->getContent( Revision::FOR_THIS_USER, $this->getUser() );
		if ( $content === null || $content->getModel() !== CONTENT_MODEL_PROOFREAD_PAGE ||
			$content->isRedirect()
		) {
			$this->page->view();

			return;
		}
		$page = ProofreadPagePage::newFromTitle( $wikiPage->getTitle() );
		$pageDisplayHandler = new PageDisplayHandler( Context::getDefaultContext() );

		// render HTML
		$out->addHTML( $pageDisplayHandler->buildPageContainerBegin() );
		$this->page->view();
		$out->addHTML( $pageDisplayHandler->buildPageContainerEnd( $page ) );

		// add modules
		$out->addModules( 'ext.proofreadpage.ve.pageTarget.init' );
		$out->addModuleStyles( [ 'ext.proofreadpage.base', 'ext.proofreadpage.page' ] );
		$out->addJsConfigVars( [
			'prpPageQuality' => $content->getLevel()->getLevel()
		] );

		// custom CSS
		$css = $pageDisplayHandler->getCustomCss( $page );
		if ( $css !== '' ) {
			$out->addInlineStyle( $css );
		}
	}
}
