<?php

namespace ProofreadPage\Page;

use MediaWiki\Actions\EditAction;
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
		$editor = new EditPagePage(
			$this->getArticle(),
			Context::getDefaultContext()
		);
		$editor->setContextTitle( $this->getTitle() );
		$editor->edit();
	}
}
