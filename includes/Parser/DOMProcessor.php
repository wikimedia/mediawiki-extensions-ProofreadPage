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
	}

}
