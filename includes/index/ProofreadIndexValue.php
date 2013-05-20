<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * A value of an index entry
 */
abstract class ProofreadIndexValue {

	/**
	 * The config of the entry
	 * @var array
	 */
	protected $config;

	/**
	 * @param $value string
	 * @param $config array
	 * @throws MWException
	 */
	public function __construct( $value, $config ) {
		if( !$this->isValid( $value ) ) {
			throw new MWException( 'Initialisation value ' . $value . ' is not valid for type ' . $this->getType() );
		}
		$this->setValue( $value );
		$this->config = $config;
	}

	/**
	 * Set the value
	 * @param $value string
	 */
	protected abstract function setValue( $value );

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public abstract function __toString();

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public abstract function getType();

	/**
	 * Return the "main" type of the entry (all identifiers are regrouped under the name identifier)
	 * @return string
	 */
	public function getMainType() {
		return $this->getType();
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return true;
	}

	/**
	 * Return name of the ProofreadIndexValue class for a type
	 * @param $type string
	 * @throws MWException
	 * @return string
	 */
	public static function getIndexValueClassNameForType( $type ) {
		switch( $type ) {
			case 'string':
				return 'ProofreadIndexValueString';
			case 'page':
				return 'ProofreadIndexValuePage';
			case 'number':
				return 'ProofreadIndexValueNumber';
			case 'langcode':
				return 'ProofreadIndexValueLangcode';
			case 'uri':
				return 'ProofreadIndexValueUri';
			case 'isbn':
				return 'ProofreadIndexValueIsbn';
			case 'issn':
				return 'ProofreadIndexValueIssn';
			case 'lccn':
				return 'ProofreadIndexValueLccn';
			case 'oclc':
				return 'ProofreadIndexValueOclc';
			case 'arc':
				return 'ProofreadIndexValueArc';
			case 'ark':
				return 'ProofreadIndexValueArk';
			default:
				throw new MWException( 'Wrong type identifier: ' . $type );
		}
	}
}


/**
 * A string value of an index entry
 */
class ProofreadIndexValueString extends ProofreadIndexValue {

	/**
	 * The wiki text value
	 * @var string
	 */
	protected $wikiValue;

	/**
	 * The value with wikitags removed
	 * @var string
	 */
	protected $value;

	/**
	 * Set the value
	 * @param $value string
	 */
	protected function setValue( $value ) {
		$this->wikiValue = (string) $value;
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() { //TODO improve by removing all tags.
		if( $this->value !== null ) {
			return $this->value;
		}

		$value = $this->wikiValue;
		$value = trim( $value, " '\t\n\r\0\x0B" );
		if( preg_match( "/^\[\[([^\|]*)\|?(.*)\]\]$/", $value, $m ) ) {
			if( $m[2] ) {
				$value = $m[2];
			} else {
				$value = $m[1];
			}
		}
		$this->value = $value;
		return $this->value;
	}

	/**
	 * Return the value of the entry as string with wiki tags
	 * @return string
	 */
	public function getWiki() {
		return $this->wikiValue;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'string';
	}
}


/**
 * A number value of an index entry
 */
class ProofreadIndexValueNumber extends ProofreadIndexValue {

	/**
	 * The value
	 * @var float
	 */
	protected $value;

	/**
	 * Set the value
	 * @param $value string
	 */
	protected function setValue( $value ) {
		$this->value = (float) $value;
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return (string) $this->value;
	}

	/**
	 * Return if the value is an integer
	 * @return bool
	 */
	public function isInteger() {
		return floor( $this->value ) == $this->value;
	}

	/**
	 * Return the value of the entry as integer
	 * @return integer
	 */
	public function getInteger() {
		return (int) $this->value;
	}

	/**
	 * Return the value of the entry as float
	 * @return float
	 */
	public function getFloat() {
		return $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'number';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return is_numeric( $value );
	}
}


/**
 * A page value of an index entry
 */
class ProofreadIndexValuePage extends ProofreadIndexValue {

	/**
	 * The value
	 * @var Title
	 */
	protected $value;

	/**
	 * Set the value
	 * @param $value string
	 */
	protected function setValue( $value ) {
		$value = trim( $value, " '\t\n\r\0\x0B" );
		if( preg_match( "/^\[\[([^\|]*)\|?(.*)\]\]$/", $value, $m ) ) {
			$value = $m[1];
		}
		$this->value = Title::newFromText( $value );
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return $this->value->getFullText();
	}

	/**
	 * Return the base title of the page without namespace
	 * @return string
	 */
	public function getMainText() {
		$val = $this->value->getBaseText();
		$parts = explode( ':', $this->value->getBaseText() );
        if( count( $parts ) > 1 && $parts[0] == $this->value->getNsText() ) {
			unset( $parts[0] );
			return implode( '/', $parts );
		} else {
			return $val;
		}
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return $this->value->getCanonicalURL();
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'page';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		$value = trim( $value, " '\t\n\r\0\x0B" );
		if( preg_match( "/^\[\[([^\|]*)\|?(.*)\]\]$/", $value, $m ) ) {
			$value = $m[1];
		}
		$title = Title::newFromText( $value );
		return $title !== null;
	}
}


/**
 * A Langcode value of an index entry
 */
class ProofreadIndexValueLangcode extends ProofreadIndexValue {

	/**
	 * The value
	 * @var string
	 */
	protected $value;


	/**
	 * Set the value
	 * @param $value string
	 */
	protected function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'langcode';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return Language::isValidBuiltInCode( $value );
	}
}


/**
 * An identifier value of an index entry
 */
abstract class ProofreadIndexValueIdentifier extends ProofreadIndexValue {

	/**
	 * The value
	 * @var string
	 */
	protected $value;

	/**
	 * Set the value
	 * @param $value string
	 */
	protected function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public abstract function getUri();

	/**
	 * Return the "main" type of the entry (all identifiers are regrouped under the name identifier)
	 * @return string
	 */
	public function getMainType() {
		return 'identifier';
	}
}

/**
 * An ISBN value of an index entry
 */
class ProofreadIndexValueIsbn extends ProofreadIndexValueIdentifier {

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'ISBN ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return 'urn:ISBN:' . $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'isbn';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return SpecialBookSources::isValidISBN( $value );
	}
}


/**
 * An ISSN value of an index entry
 */
class ProofreadIndexValueIssn extends ProofreadIndexValueIdentifier {
	const VALIDATION_REGEX = "/^\d{4}-\d{4}$/";

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'ISSN ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return 'urn:ISSN:' . $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'issn';
	}

	/**
	 * Return if $value is valid string
	 * @return bool
	 */
	public function isValid( $value ) {
		return preg_match( self::VALIDATION_REGEX, $value );
	}
}


/**
 * An LCCN value of an index entry
 */
class ProofreadIndexValueLccn extends ProofreadIndexValueIdentifier {

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'LCCN ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return 'http://lccn.loc.gov/' . $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'lccn';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		$length = strlen( $value );
		return is_numeric( $value ) && $length >= 8 && $length <= 10;
	}
}


/**
 * An LCCN value of an index entry
 */
class ProofreadIndexValueOclc extends ProofreadIndexValueIdentifier {

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'OCLC ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return 'http://www.worldcat.org/oclc/' . $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'oclc';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return is_numeric( $value );
	}
}


/**
 * An ARC value of an index entry
 */
class ProofreadIndexValueArc extends ProofreadIndexValueIdentifier {

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'ARC ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() {
		return 'http://research.archives.gov/description/' . $this->value;
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'arc';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) {
		return is_numeric( $value );
	}
}


/**
 * An ARK value of an index entry
 */
class ProofreadIndexValueArk extends ProofreadIndexValueIdentifier {

	/**
	 * The naan
	 * @var integer
	 */
	protected $naan = 0;

	/**
	 * @param $value string
	 * @param $config array
	 */
	public function __construct( $value, $config ) {
		if( isset( $config['naan'] ) &&  $config['naan'] ) {
			$this->naan = $config['naan'];
		}
		parent::__construct( $value, $config );
	}

	/**
	 * Return the value of the entry as string
	 * @return string
	 */
	public function __toString() {
		return 'ARK ' . $this->value;
	}

	/**
	 * Return the value of the entry as URI
	 * @return string
	 */
	public function getUri() { //TODO add the canonical NMA
		if( $this->naan ) {
			return 'ark:/' . $this->naan . '/' . $this->value;
		} else {
			return 'ark:/' . $this->value;
		}
	}

	/**
	 * Return the type of the entry
	 * @return string
	 */
	public function getType() {
		return 'ark';
	}

	/**
	 * Return if $value is valid string
	 * @param $value string
	 * @return bool
	 */
	public function isValid( $value ) { //TODO to improve
		if( $this->naan ) {
			return true;
		} else {
			return preg_match( "/^\d{5}\/.+$/", $value );
		}
	}
}
