<?php

namespace ProofreadPage\Page;

use MediaWiki\Actions\SubmitAction;
use ProofreadPage\Context;

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
		$editor = new EditPagePage(
			$this->getArticle(),
			Context::getDefaultContext()
		);
		$editor->setContextTitle( $this->getTitle() );
		$editor->edit();
	}
}
