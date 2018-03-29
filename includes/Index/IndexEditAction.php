<?php

namespace ProofreadPage\Index;

use EditAction;

/**
 * @license GNU GPL v2+
 *
 * EditAction for a Index: page
 */
class IndexEditAction extends EditAction {

	/**
	 * @see FormlessAction:show
	 */
	public function show() {
		$editor = new EditIndexPage( $this->page );
		$editor->edit();
	}
}
