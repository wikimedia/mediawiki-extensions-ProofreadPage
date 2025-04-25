<?php

namespace ProofreadPage\Index;

use MediaWiki\Actions\EditAction;

/**
 * @license GPL-2.0-or-later
 *
 * EditAction for a Index: page
 */
class IndexEditAction extends EditAction {

	/**
	 * @see FormlessAction:show
	 */
	public function show() {
		$editor = new EditIndexPage( $this->getArticle() );
		$editor->setContextTitle( $this->getTitle() );
		$editor->edit();
	}
}
