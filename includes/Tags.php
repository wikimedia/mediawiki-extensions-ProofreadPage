<?php

namespace ProofreadPage;

use ProofreadPage\Page\PageLevel;

class Tags {
	/*
	 * Quality level change tags
	 * If a revision changes the quality level or creates the pages,
	 * it will be tagged with the appropriate tag.
	 */
	public const WITHOUT_TEXT_TAG = 'proofreadpage-quality0';
	public const NOT_PROOFREAD_TAG = 'proofreadpage-quality1';
	public const PROBLEMATIC_TAG = 'proofreadpage-quality2';
	public const PROOFREAD_TAG = 'proofreadpage-quality3';
	public const VALIDATED_TAG = 'proofreadpage-quality4';

	/**
	 * Gets the revision tag for the give level
	 * @param PageLevel $level a page status level
	 * @return string|null the tag, or null if level doesn't have a known tag
	 */
	public static function getTagForPageLevel( PageLevel $level ): ?string {
		return match ( $level->getLevel() ) {
			PageLevel::WITHOUT_TEXT => self::WITHOUT_TEXT_TAG,
			PageLevel::NOT_PROOFREAD => self::NOT_PROOFREAD_TAG,
			PageLevel::PROBLEMATIC => self::PROBLEMATIC_TAG,
			PageLevel::PROOFREAD => self::PROOFREAD_TAG,
			PageLevel::VALIDATED => self::VALIDATED_TAG,
			default => null
		};
	}
}
