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
		switch ( $level->getLevel() ) {
			case PageLevel::WITHOUT_TEXT:
				return self::WITHOUT_TEXT_TAG;
			case PageLevel::NOT_PROOFREAD:
				return self::NOT_PROOFREAD_TAG;
			case PageLevel::PROBLEMATIC:
				return self::PROBLEMATIC_TAG;
			case PageLevel::PROOFREAD:
				return self::PROOFREAD_TAG;
			case PageLevel::VALIDATED:
				return self::VALIDATED_TAG;
		}
		return null;
	}
}
