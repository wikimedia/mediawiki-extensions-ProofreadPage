<?php

namespace ProofreadPage\Parser;

use MediaWiki\Config\Config;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\DOM\Text;
use Wikimedia\Parsoid\Ext\DOMProcessor as ExtDOMProcessor;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;
use Wikimedia\Parsoid\Ext\Utils;

class DOMProcessor extends ExtDOMProcessor {

	private string $joiner;
	private string $separator;

	public function __construct( Config $mainConfig ) {
		$this->joiner = $mainConfig->get( 'ProofreadPagePageJoiner' );
		$this->separator = Utils::decodeWtEntities(
			$mainConfig->get( 'ProofreadPagePageSeparator' )
		);
	}

	private function findLastTextNode( ?Node $node ): ?Text {
		if ( $node instanceof Text ) {
			return $node;
		}
		// Recursively find the last node (where the joiner should be)
		if ( $node instanceof Element && $node->lastChild ) {
			return $this->findLastTextNode( $node->lastChild );
		}
		return null;
	}

	/**
	 * Attempts to replicate:
	 * $out = str_replace( $joiner . $placeholder, '', $out );
	 * $out = str_replace( $placeholder, $separator, $out );
	 * @inheritDoc
	 */
	public function wtPostprocess(
		ParsoidExtensionAPI $extApi, Node $root, array $options
	): void {
		'@phan-var Element|DocumentFragment $root';
		$nodes = DOMCompat::querySelectorAll( $root, '[typeof~=mw:Extension/pageseparator]' );
		foreach ( $nodes as $node ) {
			$pageLastNode = $node->previousSibling;
			if ( $pageLastNode !== null && !( $pageLastNode instanceof Text ) ) {
				$pageLastNode = $pageLastNode->lastChild;
			}
			if ( $pageLastNode instanceof Text ) {
				$pageLastNodeVal = ( $pageLastNode->nodeValue ?? '' );
				$lastCharOfPage = substr( $pageLastNodeVal, -1 );
				if ( $lastCharOfPage === $this->joiner ) {
					$pageLastNode->nodeValue = substr( $pageLastNodeVal, 0, -1 );
					$node->parentNode->removeChild( $node );
					continue;
				}
			}
			$node->parentNode->replaceChild(
				$node->ownerDocument->createTextNode( $this->separator ), $node
			);
		}
		// Join refs in the same way
		$follows = DOMCompat::querySelectorAll( $root, '[typeof~="mw:Cite/Follow"]' );
		foreach ( $follows as $followNode ) {
			// Removes the harcoded space introduced by Cite
			$firstChild = $followNode->firstChild;
			if ( $firstChild instanceof Text ) {
				$firstChild->nodeValue = preg_replace( '/^[\s\x{00A0}]+/u', '', $firstChild->nodeValue );
			}
			// Search the previous ref for the joiner
			$prev = $followNode->previousSibling;

			// Cite leaves the first ref as free text, and the next ones as spans
			$prevTextNode = $this->findLastTextNode( $prev );

			if ( $prevTextNode instanceof Text ) {
				$val = $prevTextNode->nodeValue;
				$len = strlen( $this->joiner );
				// If previous reference ends in joiner, remove it
				if ( $len > 0 && substr( $val, -$len ) === $this->joiner ) {
					$prevTextNode->nodeValue = substr( $val, 0, -$len );
					continue;
				}
			}
			// Else, add the separator
			$sepNode = $firstChild->ownerDocument->createTextNode( $this->separator );
			$firstChild->parentNode->insertBefore( $sepNode, $firstChild );
		}
	}
}
