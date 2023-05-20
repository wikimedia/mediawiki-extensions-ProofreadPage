<?php

namespace ProofreadPage\Index;

use ProofreadPage\Link;
use ProofreadPageTestCase;
use Title;

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
				[ new Link( Title::newFromText( 'Foo' ), 'Foo' ) ]
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
				[ new Link( Title::newFromText( 'Template:Foo' ), 'Foo' ) ]
			],
			[
				'[[Foo|Bar]]',
				NS_MAIN,
				[ new Link( Title::newFromText( 'Foo' ), 'Bar' ) ]
			],
			[
				'[[Foo]][[Bar]][[<nowiki>]]',
				NS_MAIN,
				[
					new Link( Title::newFromText( 'Foo' ), 'Foo' ),
					new Link( Title::newFromText( 'Bar' ), 'Bar' )
				]
			],
		];
	}

	/**
	 * @dataProvider getLinksToNamespaceProvider
	 */
	public function testGetLinksToNamespace( $wikitext, $namespace, $links ) {
		$this->assertArrayEquals(
			$links,
			( new WikitextLinksExtractor() )->getLinksToNamespace( $wikitext, $namespace )
		);
	}
}
