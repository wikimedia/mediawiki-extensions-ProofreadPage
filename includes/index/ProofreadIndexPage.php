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
 *
 * @file
 * @ingroup ProofreadPage
 */

/**
 * An index page
 */
class ProofreadIndexPage {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var string Content of the page
	 */
	protected $text;

	/**
	 * Constructor
	 * @param $title Title Reference to a Title object.
	 * @param $text string content of the page. Warning: only done for EditProofreadIndexPage use.
	 */
	public function __construct( Title $title, $text = null ) {
		$this->title = $title;
		$this->text = $text;
	}

	/**
	 * Return content of the page
	 * @return string
	 */
	protected function getText() {
		if ( $this->text === null ) {
			$rev = Revision::newFromTitle( $this->title );
			if ( $rev === null ) {
				$this->text = '';
			} else {
				$this->text = $rev->getText();
			}
		}
		return $this->text;
	}

	/**
	 * @return array the configuration
	 * The configuration is a list of properties like this :
	 * array(
	 * 	'ID' => array( //the property id
	 *		'type' => 'string', //the property type (for compatibility reasons the values have not to be of this type). Possible values: string, number, page
	 *		'size' => 1, //for type = string : the size of the form input
	 *		'default' => '', //the default value
	 *		'label' => 'ID', //the label of the property
	 *		'help' => '', //a short help text
	 *		'values' => null, //an array value => label that list the possible values (for compatibility reasons the stored values have not to be one of these)
	 *		'header' => false, //give the content of this property to Mediawiki:Proofreadpage_header_template as template parameter
	 *		'hidden' => false //don't show the property in the index pages form. Useful for data that have always the same value (as language=en for en Wikisource) or are only set at the <pages> tag level.
	 *		)
	 * );
	 *  NB: The values set are the default values
	 */
	public static function getDataConfig() {
		static $config = null;
		if ( $config !== null ) {
			return $config;
		}

		$data = wfMessage( 'proofreadpage_index_data_config' )->inContentLanguage();
		if ( $data->exists() &&	$data->plain() != '' ) {
			$config = FormatJson::decode( $data->plain(), true );
			if ( $config === null ) {
				global $wgOut;
				$wgOut->showErrorPage( 'proofreadpage_dataconfig_badformatted', 'proofreadpage_dataconfig_badformattedtext' );
				$config = array();
			}
		} else {
			$attributes = explode( "\n", wfMessage( 'proofreadpage_index_attributes' )->inContentLanguage()->text() );
			$indexAttributes = explode( ' ', wfMessage( 'proofreadpage_js_attributes' )->inContentLanguage()->text() );
			$config = array();
			foreach( $attributes as $attribute ) {
				$m = explode( '|', $attribute );
				$params = array(
					'type' => 'string',
					'size' => 1,
					'default' => '',
					'label' => $m[0],
					'help' => '',
					'values' => null,
					'header' => false
				);

				if ( $m[0] == 'Header' ) {
					$params['default'] = wfMessage( 'proofreadpage_default_header' )->inContentLanguage()->plain();
				}
				if ( $m[0] == 'Footer' ) {
					$params['default'] = wfMessage( 'proofreadpage_default_footer' )->inContentLanguage()->plain();
				}
				if ( isset( $m[1] ) && $m[1] !== '' ) {
					$params['label'] = $m[1];
				}
				if ( isset( $m[2] ) ) {
					$params['size'] = intval( $m[2] );
				}
				$config[$m[0]] = $params;
			}

			foreach( $indexAttributes as $attribute ) {
				if ( isset( $config[$attribute] ) ) {
					$config[$attribute]['header'] = true;
				} else {
					$config[$attribute] = array(
						'type' => 'string',
						'size' => 1,
						'default' => '',
						'label' => $attribute,
						'values' => null,
						'header' => true,
						'hidden' => true
					);
				}
			}
		}

		return $config;
	}

	/**
	 * Return metadata from an index page.
	 * @param $values array key => value
	 * @return array of ProofreadIndexEntry
	 */
	protected static function getIndexEntriesFromIndexContent( $values ) {
		$config = self::getDataConfig();
		$metadata = array();
		foreach( $config as $varName => $property ) {
			if ( isset( $values[$varName] ) ) {
				$metadata[$varName] = new ProofreadIndexEntry( $varName, $values[$varName], $property );
			} else {
				$metadata[$varName] = new ProofreadIndexEntry( $varName, '', $property );
			}
		}
		return $metadata;
	}

	/**
	 * Return metadata from an index page.
	 * @return array of ProofreadIndexEntry
	 */
	public function getIndexEntries() {
		$text = $this->getText();
		$values = array();
		$config = self::getDataConfig();
		foreach( $config as $varName => $property ) {
			$tagPattern = "/\n\|" . $varName . "=(.*?)\n(\||\}\})/is";
			if ( preg_match( $tagPattern, $text, $matches ) ) {
				$values[$varName] = $matches[1];
			}
		}
		return self::getIndexEntriesFromIndexContent( $values );
	}
}
