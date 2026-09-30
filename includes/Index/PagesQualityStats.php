<?php

namespace ProofreadPage\Index;

/**
 * @license GPL-2.0-or-later
 *
 * Statistics on the proofreading quality of pages of an Index page
 */
class PagesQualityStats {

	/**
	 * @param int $numberOfPages
	 * @param int[] $numberOfPagesByLevel
	 */
	public function __construct(
		private readonly int $numberOfPages,
		private readonly array $numberOfPagesByLevel,
	) {
	}

	public function equals( PagesQualityStats $other ): bool {
		return $this->numberOfPages == $other->numberOfPages &&
			$this->numberOfPagesByLevel == $other->numberOfPagesByLevel;
	}

	public function getNumberOfPages(): int {
		return $this->numberOfPages;
	}

	public function getNumberOfPagesForQualityLevel( int $level ): int {
		if ( !array_key_exists( $level, $this->numberOfPagesByLevel ) ) {
			return 0;
		}
		return $this->numberOfPagesByLevel[$level];
	}

	public function getNumberOfPagesWithAnyQualityLevel(): int {
		return array_sum( $this->numberOfPagesByLevel );
	}

	public function getNumberOfPagesWithoutQualityLevel(): int {
		return $this->numberOfPages - $this->getNumberOfPagesWithAnyQualityLevel();
	}

	/**
	 * @param int $oldLevel
	 * @param int $newLevel
	 * @return self
	 */
	public function withLevelChange( int $oldLevel, int $newLevel ): self {
		$newNumberOfPagesByLevel = $this->numberOfPagesByLevel;
		$newNumberOfPagesByLevel[$oldLevel]--;
		$newNumberOfPagesByLevel[$newLevel]++;
		return new PagesQualityStats( $this->numberOfPages, $newNumberOfPagesByLevel );
	}

	public function withPageCreation( int $newLevel ): self {
		$newNumberOfPagesByLevel = $this->numberOfPagesByLevel;
		$newNumberOfPagesByLevel[$newLevel]++;
		return new PagesQualityStats( $this->numberOfPages, $newNumberOfPagesByLevel );
	}

	public function withPageDeletion( int $oldLevel ): self {
		$newNumberOfPagesByLevel = $this->numberOfPagesByLevel;
		$newNumberOfPagesByLevel[$oldLevel]--;
		return new PagesQualityStats( $this->numberOfPages, $newNumberOfPagesByLevel );
	}
}
