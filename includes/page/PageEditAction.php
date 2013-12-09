<?php

namespace ProofreadPage\Page;

use EditAction;
use ProofreadPagePage;

/**
 * @licence GNU GPL v2+
 *
 * EditAction for a Page: page
 */
class PageEditAction extends EditAction {

	/**
	 * @see FormlessAction:show
	 */
	public function show() {
		$pagePage = ProofreadPagePage::newFromTitle( $this->getTitle() );
		$editor = new EditPagePage( $this->page, $pagePage );
		$editor->edit();
	}
}