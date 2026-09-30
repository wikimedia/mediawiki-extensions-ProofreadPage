<?php

namespace ProofreadPage;

use MediaWiki\Title\Title;

/**
 * @license GPL-2.0-or-later
 *
 * A link to a MediaWiki page. It is composed by a target and a label
 */
class Link {

	public function __construct(
		private readonly Title $target,
		private readonly string $label,
	) {
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

	/**
	 * @param Link $other
	 * @return bool
	 */
	public function equals( Link $other ) {
		return $this->target->equals( $other->target ) && $this->label === $other->label;
	}
}
