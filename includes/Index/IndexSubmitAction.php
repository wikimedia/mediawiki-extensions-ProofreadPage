<?php

namespace ProofreadPage\Index;

use SubmitAction;

/**
 * @license GNU GPL v2+
 *
 * SubmitAction for a Index: page
 */
class IndexSubmitAction extends SubmitAction {

	/**
	 * @see FormlessAction:show
	 */
	public function show() {
		$editor = new EditIndexPage( $this->page );
		$editor->edit();
	}
}
