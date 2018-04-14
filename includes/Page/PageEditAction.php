<?php

namespace ProofreadPage\Page;

use EditAction;
use ProofreadPage\Context;

/**
 * @license GPL-2.0-or-later
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
