<?php

namespace ProofreadPage;

use MWContentSerializationException;

/**
 * Serializer that supports Wikitext and JSON, which is something supported
 * by both Index and Page content models.
 */
trait MultiFormatSerializerUtils {

	/**
	 * @param string $key
	 * @param array $serialization
	 * @throws MWContentSerializationException
	 */
	protected static function assertArrayKeyExistsInSerialization( $key, array $serialization ) {
		if ( !array_key_exists( $key, $serialization ) ) {
			throw new MWContentSerializationException(
				"The serialization should contain a '$key' entry."
			);
		}
	}

	/**
	 * @param array $serialization
	 * @param string $key
	 * @throws MWContentSerializationException
	 */
	protected static function assertArrayValueIsArray( array $serialization, $key ) {
		if ( !is_array( $serialization[ $key ] ) ) {
			throw new MWContentSerializationException(
				"The serialization key '$key' should be an array."
			);
		}
	}

	/**
	 * Check if an array has only sequential integer keys
	 * @param array $array the array to check
	 * @return bool
	 */
	protected static function arrayIsSequential( array $array ): bool {
		if ( $array === [] ) {
			return true;
		}

		// The array isn't empty, but has no '0' key, so it cannot be sequential
		if ( !isset( $array[0] ) ) {
			return false;
		}

		return array_keys( $array ) === range( 0, count( $array ) - 1 );
	}

	/**
	 * @param array $array the array to check
	 * @param string $name name to use in the error
	 * @throws MWContentSerializationException
	 */
	protected static function assertArrayIsSequential( array $array, string $name ) {
		if ( !self::arrayIsSequential( $array ) ) {
			throw new MWContentSerializationException(
				"The array '$name' should be a sequential array."
			);
		}
	}

	/**
	 * Check if an array has only string values
	 * @param array $array the array to check
	 * @param bool $emptyAllowed true if the array may contain empty strings
	 * @return bool
	 */
	protected static function arrayContainsOnlyStrings( array $array, bool $emptyAllowed ): bool {
		foreach ( $array as $key => $value ) {
			if ( !is_string( $value ) || ( !$emptyAllowed && $value === '' ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param array $array the array to check
	 * @param bool $emptyAllowed true if the array may contain empty strings
	 * @param string $name name to use in the error
	 * @throws MWContentSerializationException
	 */
	protected static function assertContainsOnlyStrings( array $array, bool $emptyAllowed, string $name ) {
		if ( !self::arrayContainsOnlyStrings( $array, $emptyAllowed ) ) {
			$strType = $emptyAllowed ? '' : 'non-empty ';
			$msg = "The array '$name' should contain only {$strType}strings.";
			throw new MWContentSerializationException( $msg );
		}
	}

	/**
	 * Detect if a string contains valid json or not
	 *
	 * @param string $text the string to detect the format of
	 * @param bool $expectJsonArray true if the JSON data should look like an array
	 * @return string
	 */
	protected static function guessDataFormat( string $text, bool $expectJsonArray ): string {
		$jsonData = json_decode( $text, true );

		$suitableJson = $expectJsonArray ?
			is_array( $jsonData ) :
			$jsonData !== null;

		return $suitableJson
			? CONTENT_FORMAT_JSON
			: CONTENT_FORMAT_WIKITEXT;
	}

	/**
	 * Throw an exception is a redirect is being serialised in a format that
	 * doesn't support it.
	 * @param string $format the desired format
	 * @throws MWContentSerializationException
	 */
	protected static function assertFormatSuitableForRedirect( string $format ) {
		if ( $format !== CONTENT_FORMAT_WIKITEXT ) {
			throw new MWContentSerializationException(
				"Redirects cannot be serialised as $format"
			);
		}
	}
}
