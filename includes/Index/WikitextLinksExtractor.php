<?php

namespace ProofreadPage\Index;

use MalformedTitleException;
use ProofreadPage\Link;
use Title;

/**
 * @license GPL-2.0-or-later
 *
 * Utility class to extract links for wikitext pages
 */
class WikitextLinksExtractor {
	public function getLinksToNamespace( $wikitext, $namespace ) {
		preg_match_all( '/\[\[(.*?)(\|(.*?)|)\]\]/i', $wikitext, $textLinks, PREG_PATTERN_ORDER );
		$links = [];
		$textLinksCount = count( $textLinks[1] );
		for ( $i = 0; $i < $textLinksCount; $i++ ) {
			try {
				$title = Title::newFromTextThrow( $textLinks[1][$i] );
				if ( $title->inNamespace( $namespace ) ) {
					if ( $textLinks[3][$i] === '' ) {
						$links[] = new Link( $title, $title->getSubpageText() );
					} else {
						$links[] = new Link( $title, $textLinks[3][$i] );
					}
				}
			} catch ( MalformedTitleException $e ) {
				// We ignore invalid links
			}
		}
		return $links;
	}
}
