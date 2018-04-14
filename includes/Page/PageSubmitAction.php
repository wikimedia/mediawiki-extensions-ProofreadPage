<?php

namespace ProofreadPage\Page;

use ProofreadPage\Context;
use SubmitAction;

/**
 * @license GPL-2.0-or-later
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
