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
  * A value of a page entry
  */

class ProofreadPageContent {

	/**
	 * @var string header of the page
	 */
	private $header;

	/**
	 * @var string body of the page
	 */
	private $body;

	/**
	 * @var string footer of the page
	 */
	private $footer;

	/**
	 * @var User|null last proofreader of the page
	 */
	protected $proofreader;

	/**
	 * @var integer proofreading level of the page
	 */
	protected $level = 1;

	/**
	 * Constructor
	 * @param $header string
	 * @param $body string
	 * @param $footer string
	 * @param $level integer
	 * @param $proofreader User|string
	 */
	public function __construct( $header = '', $body = '', $footer = '', $level = 1, $proofreader = '' ) {
		$this->setHeader( $header );
		$this->setBody( $body );
		$this->setFooter( $footer );
		$this->setLevel( $level );
		if ( is_string( $proofreader ) ) {
			$this->setProofreaderFromName( $proofreader );
		} else {
			$this->setProofreader( $proofreader );
		}
	}

	/**
	 * returns the header of the page
	 * @return string
	 */
	public function getHeader() {
		return $this->header;
	}

	/**
	 * returns the body of the page
	 * @return string
	 */
	public function getBody(){
		return $this->body;
	}

	/**
	 * returns the footer of the page
	 * @return string
	 */
	public function getFooter() {
		return $this->footer;
	}

	/**
	 * returns the proofreading level of the page.
	 * @return integer
	 */
	public function getProofreadingLevels() {
		return $this->level;
	}

	/**
	 * returns last proofreader of the page
	 * @return User
	 */
	public function getProofreader() {
		return $this->proofreader;
	}

	/**
	 * Sets value of the header
	 * @param $header string
	 * @throws MWException
	 */
	public function setHeader( $header ) {
		if ( !is_string( $header ) ) {
			throw new MWException( 'header must be a string.' );
		}
		$this->header = $header;
	}

	/**
	 * Sets value of the body
	 * @param $body string
	 * @throws MWException
	 */
	public function setBody( $body ) {
		if ( !is_string( $body ) ) {
			throw new MWException( 'body must be a string.' );
		}
		$this->body = $body;
	}

	/**
	 * Sets value of the footer
	 * @param $footer string
	 * @throws MWException
	 */
	public function setFooter( $footer ) {
		if ( !is_string( $footer ) ) {
			throw new MWException( 'footer must be a string.' );
		}
		$this->footer = $footer;
	}

	/**
	 * Sets the last proofreader
	 * @param $proofreader User
	 */
	public function setProofreader( User $user ) {
		$this->proofreader = $user;
	}

	/**
	* Sets the last proofreader from his name
	* @param $name string
	* @throws MWException
	*/
	public function setProofreaderFromName( $name ) {
		if ( $name === '' ) {
			$this->proofreader = null;
		} elseif ( IP::isValid( $name ) ) {
			$this->proofreader = User::newFromName( IP::sanitizeIP( $name ), false );
		} else {
			$name = User::newFromName( $name );
			if ( $name === false ) {
				throw new MWException( 'Name is an invalid username.' );
			} else {
				$this->proofreader = $name;
			}
		}
	}

	/**
	 * Sets level
	 * @param $level integer
	 * @throws MWException
	 */
	public function setLevel( $level ) {
		if ( !is_integer( $level ) || $level < 0 || $level > 4 ) {
			throw new MWException( 'level must be an integer between 0 and 4.' );
		}
		$this->level = $level;
	}

	/**
	 * Serialize the content of the ProofreadPageValue to wikitext
	 * @return string
	 */
	public function serialize() {
		$text = '<noinclude><pagequality level="' . $this->level . '" user="';
		if ( $this->proofreader !== null ) {
			$text .= $this->proofreader->getName();
		}
		$text .= '" /><div class="pagetext">' . $this->header. "\n\n\n" . '</noinclude>';
		$text .= $this->body;
		$text .= '<noinclude>' . $this->footer . '</div></noinclude>';
		return $text;
	}

	public function unserialize( $text ) {
		if( preg_match( '/^<noinclude>(.*?)\n\n\n<\/noinclude>(.*?)<noinclude>(.*?)<\/noinclude>$/s', $text, $m ) ) {
			$body = $m[2];
			$footer = $this->cleanTrailingDivTag( $m[3] );
		} elseif ( preg_match( '/^<noinclude>(.*?)\n\n\n<\/noinclude>(.*?)$/s', $text, $m ) ) {
			$footer = '';
			$body = $this->cleanTrailingDivTag( $m[2] );
		} else {
			throw new MWException( 'The serialize value of the page is not valid.' );
		}
		$header = $m[1];
		if ( preg_match( '/^<pagequality level="(0|1|2|3|4)" user="(.*?)" \/>(.*?)$/s', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[2];
			$header = $this->cleanHeader( $m[3] );
		} elseif( preg_match( '/^\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}(.*)/is', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[3];
			$header = $this->cleanHeader( $m[4] );
		}
		$this->setHeader( $header );
		$this->setBody( $body );
		$this->setFooter( $footer );
		$this->setLevel( $level );
		$this->setProofreaderFromName( $proofreader );
	}

	public function cleanTrailingDivTag( $text ) {
		if ( preg_match( '/^(.*?)<\/div>$/s', $text, $m2 ) ) {
			return  $m2[1];
		} else {
			return $text;
		}
	}
	public function cleanHeader( $header ) {
		if( preg_match('/^(.*?)<div class="pagetext">(.*?)$/s', $header, $mt) ) {
			$header = $mt[2];
		} elseif ( preg_match('/^(.*?)<div>(.*?)$/s', $header, $mt) ) {
			$header = $mt[2];
		}
		return $header;
	}

	/**
	 * Create a new empty ProofreadPageValue
	 * @return ProofreadPageValue
	 */
	public static function newEmpty() {
		return new self();
	}

	/**
	 * Create a new ProofreadPageContent from a ProofreadIndexPage
	 * @param $text string
	 * @return ProofreadPageValue
	 */
	public static function newFromWikitext( $text ) {
		$value = new self();
		try {
			$value->unserialize( $text );
		} catch( MWExeption $e ) {
			$value->setBody( $text );
		}
		return $value;
	}

	/**
	 * Returns if the value is empty
	 * @return bool
	 */
	public function isEmpty() {
		return $this->body === '';
	}
}
