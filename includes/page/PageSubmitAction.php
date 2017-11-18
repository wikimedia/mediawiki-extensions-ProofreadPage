<?php

namespace ProofreadPage\Page;

use ProofreadPage\Context;
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
		$editor = new EditPagePage( $this->page, Context::getDefaultContext() );
		$editor->edit();
	}
}
