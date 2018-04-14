<?php

namespace ProofreadPage;

use Title;

/**
 * @license GPL-2.0-or-later
 *
 * A link to a MediaWiki page. It is composed by a target and a label
 */
class Link {

	private $target;

	private $label;

	/**
	 * @param Title $target
	 * @param string $label
	 */
	public function __construct( Title $target, $label ) {
		$this->target = $target;
		$this->label = $label;
	}

	/**
	 * @return Title
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}
}
