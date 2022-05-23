<?php

namespace ProofreadPage;

use MWContentSerializationException;
use ProofreadPageTestCase;

/**
 * @group ProofreadPage
 * @covers \ProofreadPage\MultiFormatSerializerUtils
 */
class MultiFormatSerializerUtilsTest extends ProofreadPageTestCase {
	use MultiFormatSerializerUtils;

	public function isArrayValuesProvider() {
		return [
			[
				[
					'foo' => [],
				],
				true,
			],
			[
				[
					'foo' => ''
				],
				false,
			],
			[
				[
					'foo' => 42
				],
				false,
			]
		];
	}

	/**
	 * @dataProvider isArrayValuesProvider
	 */
	public function testAssertArrayValueIsArray( $value, bool $isArray ) {
		if ( !$isArray ) {
			$this->expectException( MWContentSerializationException::class );
			$this->expectExceptionMessage( "The serialization key 'foo' should be an array." );
		}
		self::assertArrayValueIsArray( $value, "foo" );

		// make sure we don't get here for a failure (and avoid a zero-assertion test)
		$this->assertTrue( $isArray );
	}

	public function sequentialArrayProvider() {
		return [
			[
				[],
				true,
			],
			[
				[ 'foo' ],
				true,
			],
			[
				[ 'foo', 'bar' ],
				true,
			],
			[
				[
					'foo' => 'bar'
				],
				false,
			],
			[
				// check presence of zero doesn't trigger a false positive
				[
					0 => 'zero',
					'foo' => 'bar'
				],
				false,
			],
		];
	}

	/**
	 * @dataProvider sequentialArrayProvider
	 */
	public function testArrayIsSequential( $value, bool $expectSequential ) {
		$this->assertEquals(
			self::arrayIsSequential( $value ),
			$expectSequential
		);
	}

	public function arrayKeyExistsProvider() {
		return [
			[
				[],
				false,
			],
			[
				[ 'foo' ],
				false,
			],
			[
				[
					'foo' => 'bar',
				],
				true,
			],
			[
				[
					'fuu' => 'bar',
				],
				false,
			],
			[
				[
					'foo' => 'bar',
					'fuu' => 'bar'
				],
				true,
			],
		];
	}

	/**
	 * @dataProvider arrayKeyExistsProvider
	 */
	public function testAssertArrayKeyExists( $value, bool $expectExists ) {
		$key = 'foo';
		if ( !$expectExists ) {
			$this->expectException( MWContentSerializationException::class );
			$this->expectExceptionMessage( "The serialization should contain a '{$key}' entry." );
		}
		self::assertArrayKeyExistsInSerialization( $key, $value );

		// make sure we don't get here for a failure (and avoid a zero-assertion test)
		$this->assertTrue( $expectExists );
	}

	public function stringArrayProvider() {
		return [
			[
				[],
				false,
				true,
				null
			],
			[
				[ 'a' ],
				false,
				true,
				null
			],
			[
				[ '' ],
				false,
				false,
				'The array \'foo\' should contain only non-empty strings.'
			],
			[
				[ '' ],
				true,
				true,
				null
			],
			[
				[ 2 ],
				true,
				false,
				'The array \'foo\' should contain only strings.'
			],
			[
				[ [] ],
				true,
				false,
				'The array \'foo\' should contain only strings.'
			],
			[
				[ [ 'a' ] ],
				true,
				false,
				'The array \'foo\' should contain only strings.'
			],
			[
				[ 'a', 2 ],
				true,
				false,
				'The array \'foo\' should contain only strings.'
			],
		];
	}

	/**
	 * @dataProvider stringArrayProvider
	 */
	public function testArrayContainsOnlyStrings( array $array, bool $emptyAllowed, bool $expectedOk, $msg ) {
		$key = 'foo';
		if ( !$expectedOk ) {
			$this->expectException( MWContentSerializationException::class );
			$this->expectExceptionMessage( $msg );
		}
		self::assertContainsOnlyStrings( $array, $emptyAllowed, $key );

		// make sure we don't get here for a failure (and avoid a zero-assertion test)
		$this->assertTrue( $expectedOk );
	}

	public function guessDataFormatProvider() {
		return [
			[
				'',
				false,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				'Wikitext',
				true,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				'{{Wikitext}}',
				true,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				'{{Wikitext}}',
				false,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				'"Foo"',
				true,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				'"Foo"',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				'[]',
				true,
				CONTENT_FORMAT_JSON,
			],
			[
				'[]',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				'{}',
				true,
				CONTENT_FORMAT_JSON,
			],
			[
				'{}',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				'{"foo": "bar"}',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				// invalid JSON -> wikitext
				'{foo: "bar"}',
				false,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				// invalid JSON -> wikitext
				'{"foo": "bar",}',
				false,
				CONTENT_FORMAT_WIKITEXT,
			],
			[
				// leading spaces (allowed in JSON syntax)
				'  {"foo": "bar"}',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				// trailing spaces
				'{"foo": "bar"}  ',
				false,
				CONTENT_FORMAT_JSON,
			],
			[
				// leading and trailing spaces
				' {"foo": "bar"} ',
				false,
				CONTENT_FORMAT_JSON,
			],
		];
	}

	/**
	 * @dataProvider guessDataFormatProvider
	 */
	public function testGuessDataFormat( string $text, bool $expectJsonArray,
		string $expectedFormat ) {
		$format = self::guessDataFormat( $text, $expectJsonArray );
		$this->assertEquals( $expectedFormat, $format );
	}

	public function redirectFormatProvider() {
		return [
			[
				CONTENT_FORMAT_WIKITEXT,
				true,
			],
			[
				CONTENT_FORMAT_JSON,
				false,
			],
			[
				CONTENT_FORMAT_XML,
				false,
			],
		];
	}

	/**
	 * @dataProvider redirectFormatProvider
	 */
	public function testAssertRedirectFormat( string $format, bool $expectSuitable ) {
		if ( !$expectSuitable ) {
			$this->expectException( MWContentSerializationException::class );
			$this->expectExceptionMessage( "Redirects cannot be serialised as $format" );
		}
		self::assertFormatSuitableForRedirect( $format );

		// make sure we don't get here for a failure (and avoid a zero-assertion test)
		$this->assertTrue( $expectSuitable );
	}
}
