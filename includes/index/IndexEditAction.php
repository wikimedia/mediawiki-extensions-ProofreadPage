<?php

namespace ProofreadPage\Index;

use EditAction;

/**
 * @licence GNU GPL v2+
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
