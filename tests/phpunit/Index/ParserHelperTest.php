<?php

namespace ProofreadPage\Index;

use ParserOptions;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\Index\ParserHelper
 */
class ParserHelperTest extends ProofreadPageTestCase {

	public function expandTemplateArgsProvider() {
		return [
			[
				'{{{foo}}}',
				[ 'bar' => 'baz' ],
				'{{{foo}}}'
			],
			[
				'{{{foo|}}}',
				[ 'bar' => 'baz' ],
				''
			],
			[
				'{{{bar|}}}',
				[ 'bar' => 'baz' ],
				'baz'
			],
			[
				'<indicator name="foo"/>{{{bar|}}}',
				[ 'bar' => 'baz' ],
				'<indicator name="foo"/>baz'
			],
			[
				'{{{bar|}}}',
				[ 'bar' => '<indicator name="foo"/>baz' ],
				'<indicator name="foo"/>baz'
			],
			[
				'<noinclude>foo</noinclude>',
				[ 'bar' => 'baz' ],
				''
			],
			[
				'<includeonly>foo</includeonly>',
				[ 'bar' => 'baz' ],
				'foo'
			],
		];
	}

	/**
	 * @dataProvider expandTemplateArgsProvider
	 */
	public function testExpandTemplateArgsProvider( $inputText, $args, $outputText ) {
		$parserHelper = new ParserHelper( null, ParserOptions::newCanonical() );
		$this->assertSame( $outputText, $parserHelper->expandTemplateArgs( $inputText, $args ) );
	}
}
