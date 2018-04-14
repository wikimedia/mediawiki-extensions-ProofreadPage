<?php

namespace ProofreadPage\Index;

use SubmitAction;

/**
 * @license GPL-2.0-or-later
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
