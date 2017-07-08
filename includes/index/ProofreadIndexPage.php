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

use ProofreadPage\Context;
use ProofreadPage\FileNotFoundException;
use ProofreadPage\Index\IndexContent;
use ProofreadPage\Pagination\PageList;

/**
 * An index page
 */
class ProofreadIndexPage {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var IndexContent|null content of the page
	 */
	protected $content;

	/**
	 * @var array configuration array
	 */
	protected $config = [];

	/**
	 * @param Title $title Reference to a Title object.
	 * @param array $config the configuration array (see ProofreadIndexPage::getDataConfig)
	 * @param IndexContent|null $content content of the page. Warning: only done for EditProofreadIndexPage use.
	 */
	public function __construct( Title $title, $config, IndexContent $content = null ) {
		$this->title = $title;
		$this->config = $config;
		$this->content = $content;
	}

	/**
	 * Create a new ProofreadIndexPage from a Title object
	 * @param Title $title
	 * @return ProofreadIndexPage
	 */
	public static function newFromTitle( Title $title ) {
		return new self( $title, self::getDataConfig() );
	}

	/**
	 * Return Title of the index page
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Check if two ProofreadIndexPage are equals
	 *
	 * @param ProofreadIndexPage $that
	 * @return boolean
	 */
	public function equals( ProofreadIndexPage $that ) {
		return $this->title->equals( $that->getTitle() );
	}

	/**
	 * @deprecated use FileProvider::getForIndexPage
	 *
	 * Return Scan of the book if it exist or false.
	 * @return File|false
	 */
	public function getImage() {
		try {
			return Context::getDefaultContext()->getFileProvider()->getForIndexPage( $this );
		} catch ( FileNotFoundException $e ) {
			return false;
		}
	}

	/**
	 * Return content of the page
	 * @return IndexContent
	 */
	private function getContent() {
		if ( $this->content === null ) {
			$rev = Revision::newFromTitle( $this->title );
			if ( $rev === null ) {
				$this->content = new IndexContent( [] );
			} else {
				$content = $rev->getContent();
				if ( $content instanceof IndexContent ) {
					$this->content = $content;
				} else {
					$this->content = new IndexContent( [] );
				}
			}
		}
		return $this->content;
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
				$config = [];
			}
		} else {
			$attributes = explode( "\n", wfMessage( 'proofreadpage_index_attributes' )->inContentLanguage()->text() );
			$headerAttributes = explode( ' ', wfMessage( 'proofreadpage_js_attributes' )->inContentLanguage()->text() );
			$config = [];
			foreach ( $attributes as $attribute ) {
				$m = explode( '|', $attribute );
				$params = [
					'type' => 'string',
					'size' => 1,
					'default' => '',
					'label' => $m[0],
					'help' => '',
					'values' => null,
					'header' => false
				];

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

			foreach ( $headerAttributes as $attribute ) {
				if ( isset( $config[$attribute] ) ) {
					$config[$attribute]['header'] = true;
				} else {
					$config[$attribute] = [
						'type' => 'string',
						'size' => 1,
						'default' => '',
						'label' => $attribute,
						'values' => null,
						'header' => true,
						'hidden' => true
					];
				}
			}
		}

		if ( !array_key_exists( 'Header', $config ) ) {
			$config['Header'] = [
				'default' => wfMessage( 'proofreadpage_default_header' )->inContentLanguage()->plain(),
				'header' => true,
				'hidden' => true
			];
		}
		if ( !array_key_exists( 'Footer', $config ) ) {
			$config['Footer'] = [
				'default' => wfMessage( 'proofreadpage_default_footer' )->inContentLanguage()->plain(),
				'header' => true,
				'hidden' => true
			];
		}

		return $config;
	}

	/**
	 * Return metadata from an index page.
	 * @return array of ProofreadIndexEntry
	 */
	public function getIndexEntries() {
		$contentFields = [];
		foreach ( $this->getContent()->getFields() as $key => $value ) {
			$contentFields[strtolower( $key )] = $value;
		}

		$values = [];
		foreach ( $this->config as $varName => $property ) {
			$key = strtolower( $varName );
			if ( array_key_exists( $key, $contentFields ) ) {
				$values[$varName] = new ProofreadIndexEntry( $varName, $contentFields[$key]->getNativeData(), $property );
			} else {
				$values[$varName] = new ProofreadIndexEntry( $varName, '', $property );
			}
		}
		return $values;
	}

	/**
	 * Return mime type of the file linked to the index page
	 * @return string|null
	 */
	public function getMimeType() {
		if ( preg_match( "/^.*\.(.{2,5})$/", $this->title->getText(), $m ) ) {
			$mimeMagic = MimeMagic::singleton();
			return $mimeMagic->guessTypesForExtension( $m[1] );
		} else {
			return null;
		}
	}

	/*
	 * Return metadata from the index page that have to be given to header template.
	 * @return array of ProofreadIndexEntry
	 */
	public function getIndexEntriesForHeader() {
		$entries = $this->getIndexEntries();
		$headerEntries = [];
		foreach ( $entries as $entry ) {
			if ( $entry->isHeader() ) {
				$headerEntries[$entry->getKey()] = $entry;
			}
		}
		return $headerEntries;
	}

	/*
	 * Return the index entry with the same name or null if it's not found
	 * Note: the comparison is case insensitive
	 * @return ProofreadIndexEntry|null
	 */
	public function getIndexEntry( $name ) {
		$name = strtolower( $name );
		$entries = $this->getIndexEntries();
		foreach ( $entries as $entry ) {
			if ( strtolower( $entry->getKey() ) === $name ) {
				return $entry;
			}
		}
		return null;
	}

	/**
	 * Return the ordered list of links to ns-0 from the index page.
	 * @return array of array( Title title of the pointed page, the label of the link )
	 */
	public function getLinksToMainNamespace() {
		return $this->getLinksToNamespaceFromContent( NS_MAIN, true );
	}

	/**
	 * @return array of array( Title title of the pointed page, the label of the link )
	 */
	public function getLinksToPageNamespace() {
		return $this->getLinksToNamespaceFromContent( Context::getDefaultContext()->getPageNamespaceId() );
	}

	/**
	 * @return PageList|null
	 */
	public function getPagelistTagContent() {
		$tagParameters = null;
		foreach ( $this->getContent()->getFields() as $field ) {
			preg_match_all( '/<pagelist([^<]*?)\/>/is', $field->serialize( CONTENT_FORMAT_WIKITEXT ), $m, PREG_PATTERN_ORDER );
			if ( $m[1] ) {
				if ( $tagParameters === null ) {
					$tagParameters = $m[1];
				} else {
					$tagParameters = array_merge( $tagParameters, $m[1] );
				}
			}
		}
		if ( $tagParameters === null ) {
			return $tagParameters;
		}

		return new PageList( Sanitizer::decodeTagAttributes( implode( $tagParameters ) ) );
	}

	/**
	 * Return all links in a given namespace
	 * @param integer $namespace the default namespace id
	 * @param bool $withPrepossessing apply preprocessor before looking for links
	 * @return array of array( Title title of the pointed page, the label of the link )
	 * @todo add an abstraction for links (Title + label)
	 */
	private function getLinksToNamespaceFromContent( $namespace, $withPrepossessing = false ) {
		$links = [];
		foreach ( $this->getContent()->getFields() as $field ) {
			$wikitext = $field->serialize( CONTENT_FORMAT_WIKITEXT );
			if ( $withPrepossessing ) {
				$wikitext = self::getParser()->preprocess( $wikitext, $this->title, new ParserOptions() );
			}
			$links = array_merge( $links, $this->getLinksToNamespaceFromWikitext( $wikitext, $namespace ) );
		}
		return $links;
	}

	private function getLinksToNamespaceFromWikitext( $wikitext, $namespace ) {
		preg_match_all( '/\[\[(.*?)(\|(.*?)|)\]\]/i', $wikitext, $textLinks, PREG_PATTERN_ORDER );
		$links = [];
		$num = 0;
		$textLinksCount = count( $textLinks[1] );
		for ( $i = 0; $i < $textLinksCount; $i++ ) {
			$title = Title::newFromText( $textLinks[1][$i] );
			if ( $title !== null && $title->inNamespace( $namespace ) ) {
				if ( $textLinks[3][$i] === '' ) {
					$links[$num] = [ $title, $title->getSubpageText() ];
				} else {
					$links[$num] = [ $title, $textLinks[3][$i] ];
				}
				$num++;
			}
		}
		return $links;
	}

	/**
	 * Return the value of an entry as wikitext with variable replaced with index entries and $otherParams
	 * Example: if 'header' entry is 'Page of {{title}} number {{pagenum}}' with $otherParams = array( 'pagenum' => 23 )
	 * the function called for 'header' will returns 'Page page my book number 23'
	 * @param string $name entry name
	 * @param array $otherParams associative array other possible values to replace
	 * @return string|null the value with variables replaced or null if the entry does not exists
	 */
	public function getIndexEntryWithVariablesReplacedWithIndexEntries( $name, $otherParams ) {
		$entry = $this->getIndexEntry( $name );
		if ( $entry === null ) {
			return null;
		}

		// we can't use the parser here because it replace tags like <references /> by strange UIDs
		$params = $this->getIndexEntriesForHeaderAsTemplateParams() + $otherParams;
		return preg_replace_callback( '/{\{\{(.*)(\|(.*))?\}\}\}/U', function ( $matches ) use ( $params ) {
			$paramKey = trim( strtolower( $matches[1] ) );
			if ( array_key_exists( $paramKey, $params ) ) {
				return $params[$paramKey];
			} elseif ( array_key_exists( 3, $matches ) ) {
				return trim( $matches[3] );
			} else {
				return $matches[0];
			}
		}, $entry->getStringValue() );
	}

	/**
	 * Returns the index entries formatted in order to be transcluded in templates
	 * @return string[]
	 */
	protected function getIndexEntriesForHeaderAsTemplateParams() {
		$indexEntries = $this->getIndexEntriesForHeader();
		$params = [];
		foreach ( $indexEntries as $entry ) {
			$params[strtolower( $entry->getKey() )] = $entry->getStringValue();
		}
		return $params;
	}

	/**
	 * Return the Parser object done to be used for Index pages internal use
	 * Needed to avoid side effects of $parser->replaceVariables
	 *
	 * @return Parser
	 */
	protected static function getParser() {
		global $wgParser;
		static $parser = null;

		if ( $parser === null ) {
			StubObject::unstub( $wgParser );
			$parser = clone $wgParser;
		}

		return $parser;
	}
}
