<?php

namespace ProofreadPage\Page;

use ProofreadPagePage;
use SubmitAction;

/**
 * @licence GNU GPL v2+
 *
 * SubmitAction for a Page: page
 */
class PageSubmitAction extends SubmitAction {

	/**
	 * @see FormlessAction:show
	 */
	public function show() {
		$pagePage = ProofreadPagePage::newFromTitle( $this->getTitle() );
		$editor = new EditPagePage( $this->page, $pagePage );
		$editor->edit();
	}
}