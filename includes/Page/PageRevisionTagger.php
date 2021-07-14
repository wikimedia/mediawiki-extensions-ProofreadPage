<?php

namespace ProofreadPage\Page;

use ProofreadPage\Tags;
use RecentChange;

class PageRevisionTagger {

	/**
	 * Get the tags for a given recent change
	 * @param RecentChange $rc the recent change
	 * @return array array of tags
	 */
	public function getTagsForChange( RecentChange $rc ): array {
		$newId = $rc->getAttribute( 'rc_this_oldid' );
		$oldId = $rc->getAttribute( 'rc_last_oldid' );

		return $this->getTagsForIds( $oldId, $newId );
	}

	/**
	 * Get the tags for a given revision ID.
	 * @param int $oldId parent rev ID of the change (0 if new page or don't need
	 *                   to compare with the previous rev)
	 * @param int $newId rev ID of the change
	 * @return array array of tags
	 */
	public function getTagsForIds( int $oldId, int $newId ): array {
		$tags = [];
		$newPageContent = PageContent::getContentForRevId( $newId );

		// this is not ProofreadPage content
		if ( !( $newPageContent instanceof PageContent ) ) {
			return [];
		}

		$newLevel = $newPageContent->getLevel();
		$newLevelTag = Tags::getTagForPageLevel( $newLevel );

		// couldn't find a suitable new tag for the page
		if ( $newLevelTag === null ) {
			return [];
		}

		if ( $oldId !== 0 ) {
			$oldPageContent = PageContent::getContentForRevId( $oldId );

			# if the page has had its content model changed to PRP page
			# or it was already a PRP page, but the status changed
			if ( !( $oldPageContent instanceof PageContent )
					|| !$oldPageContent->getLevel()->equals( $newLevel ) ) {
				$tags[] = $newLevelTag;
			}
		} else {
			// new page - always tag with the initial level
			$tags[] = $newLevelTag;
		}
		return $tags;
	}
}
