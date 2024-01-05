<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use ProofreadPage\Link;

/**
 * @license GPL-2.0-or-later
 *
 * Utility class to extract links for wikitext pages
 */
class WikitextLinksExtractor {

	/**
	 * @param string $wikitext unparsed wikitext
	 * @param int $namespace namespace of the page from which the wikitext was passed
	 * @return Link[] array of links in the text
	 */
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
