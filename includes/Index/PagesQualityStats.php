<?php

namespace ProofreadPage\Index;

/**
 * @license GPL-2.0-or-later
 *
 * Statistics on the proofreading quality of pages of an Index page
 */
class PagesQualityStats {

	/** @var int */
	private $numberOfPages;

	/** @var int[] */
	private $numberOfPagesByLevel;

	/**
	 * @param int $numberOfPages
	 * @param int[] $numberOfPagesByLevel
	 */
	public function __construct( int $numberOfPages, array $numberOfPagesByLevel ) {
		$this->numberOfPages = $numberOfPages;
		$this->numberOfPagesByLevel = $numberOfPagesByLevel;
	}

	/**
	 * @param PagesQualityStats $other
	 * @return bool
	 */
	public function equals( PagesQualityStats $other ): bool {
		return $this->numberOfPages == $other->numberOfPages &&
			$this->numberOfPagesByLevel == $other->numberOfPagesByLevel;
	}

	/**
	 * @return int
	 */
	public function getNumberOfPages(): int {
		return $this->numberOfPages;
	}

	/**
	 * @param int $level
	 * @return int
	 */
	public function getNumberOfPagesForQualityLevel( int $level ): int {
		if ( !array_key_exists( $level, $this->numberOfPagesByLevel ) ) {
			return 0;
		}
		return $this->numberOfPagesByLevel[$level];
	}

	/**
	 * @return int
	 */
	public function getNumberOfPagesWithoutQualityLevel(): int {
		return $this->numberOfPages - array_sum( $this->numberOfPagesByLevel );
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

	/**
	 * @param int $newLevel
	 * @return self
	 */
	public function withPageCreation( int $newLevel ): self {
		$newNumberOfPagesByLevel = $this->numberOfPagesByLevel;
		$newNumberOfPagesByLevel[$newLevel]++;
		return new PagesQualityStats( $this->numberOfPages, $newNumberOfPagesByLevel );
	}

	/**
	 * @param int $oldLevel
	 * @return self
	 */
	public function withPageDeletion( int $oldLevel ): self {
		$newNumberOfPagesByLevel = $this->numberOfPagesByLevel;
		$newNumberOfPagesByLevel[$oldLevel]--;
		return new PagesQualityStats( $this->numberOfPages, $newNumberOfPagesByLevel );
	}
}
