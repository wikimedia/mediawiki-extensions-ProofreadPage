<?php

namespace ProofreadPage\Page;

use EditAction;
use ProofreadPage\Context;

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
		$editor = new EditPagePage( $this->page, Context::getDefaultContext() );
		$editor->edit();
	}
}
