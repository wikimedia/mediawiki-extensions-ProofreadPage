<?php

namespace ProofreadPage\Parser;

use MediaWiki\Config\Config;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Ext\DOMProcessor as ExtDOMProcessor;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;
use Wikimedia\Parsoid\Ext\Utils;
use Wikimedia\Parsoid\Utils\DOMCompat;

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
			$pageLastNode = $node->previousSibling->lastChild ?? null;
			$lastCharOfPage = substr( $pageLastNode->nodeValue ?? '', -1 );
			if ( $lastCharOfPage === $this->joiner ) {
				$pageLastNode->nodeValue = substr( $pageLastNode->nodeValue, 0, -1 );
				$node->parentNode->removeChild( $node );
			} else {
				$node->parentNode->replaceChild(
					$node->ownerDocument->createTextNode( $this->separator ), $node
				);
			}
		}
	}

}
