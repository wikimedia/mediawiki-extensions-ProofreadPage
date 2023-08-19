<?php

namespace ProofreadPage\Index;

use MediaWiki\Title\Title;
use ProofreadPage\Link;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\WikitextLinksExtractor
 */
class WikitextLinksExtractorTest extends ProofreadPageTestCase {

	public static function getLinksToNamespaceProvider() {
		return [
			[
				'[[Foo]]',
				NS_MAIN,
				[ [ 'Foo', 'Foo' ] ]
			],
			[
				'[[Foo]]',
				NS_TEMPLATE,
				[]
			],
			[
				'[[Template:Foo]]',
				NS_MAIN,
				[]
			],
			[
				'[[Template:Foo]]',
				NS_TEMPLATE,
				[ [ 'Template:Foo', 'Foo' ] ]
			],
			[
				'[[Foo|Bar]]',
				NS_MAIN,
				[ [ 'Foo', 'Bar' ] ]
			],
			[
				'[[Foo]][[Bar]][[<nowiki>]]',
				NS_MAIN,
				[
					[ 'Foo', 'Foo' ],
					[ 'Bar', 'Bar' ],
				]
			],
		];
	}

	/**
	 * @dataProvider getLinksToNamespaceProvider
	 */
	public function testGetLinksToNamespace( $wikitext, $namespace, $expectedLinks ) {
		$links = [];
		foreach ( $expectedLinks as [ $link, $label ] ) {
			$links[] = new Link( Title::newFromText( $link ), $label );
		}
		$this->assertArrayEquals(
			$links,
			( new WikitextLinksExtractor() )->getLinksToNamespace( $wikitext, $namespace )
		);
	}
}
