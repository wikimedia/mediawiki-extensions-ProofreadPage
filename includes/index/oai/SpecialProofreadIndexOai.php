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
 * The content of this file use some code from OAIRepo Mediawiki extension.
 *
 * @file
 * @ingroup SpecialPage
 */


/**
 * A special page to return metadata of index pages in RDF/XML
 * @see http://www.openarchives.org/OAI/openarchivesprotocol.html
 */
class SpecialProofreadIndexOai extends UnlistedSpecialPage {

	/**
	 * @var array Parameters of the request
	 */
	protected $request = array();

	/**
	 * @var DatabaseBase Slave database
	 */
	protected $db;

	/**
	 * @var string URL of the API
	 */
	protected $baseUrl;

	public function __construct() {
		parent::__construct( 'ProofreadIndexOai' );
		$this->baseUrl = $this->getPageTitle()->getCanonicalUrl();
	}

	public function execute( $par ) {
		$this->getOutput()->disable();

		$this->db = wfGetDB( DB_SLAVE );
		$this->outputOai();
	}

	/**
	 * Return OAI datestamp
	 * @param $datestamp string MW Timestamp
	 * @param $granularity string OAI ganularity ('YYYY-MM-DDThh:mm:ssZ' or 'YYY-MM-DD')
	 * @throws MWException
	 * @return string
	 */
	public static function datestamp( $datestamp, $granularity = 'YYYY-MM-DDThh:mm:ssZ' ) {
		$lang = new Language();
		if ( $granularity == 'YYYY-MM-DDThh:mm:ssZ' ) {
			return $lang->sprintfDate( 'Y-m-d\TH:i:s\Z', $datestamp );
		} elseif ( $granularity == 'YYY-MM-DD' ) {
			return $lang->sprintfDate( 'Y-m-d', $datestamp );
		} else {
			throw new MWException( 'Unknown granularity' );
		}
	}

	/**
	 * Return parameters of the request
	 * @throws ProofreadIndexOaiError
	 * @return array
	 */
	protected function parseRequest() {
		$request = $this->getRequest();

		$verbs = array(
			'GetRecord' => array(
				'required'  => array( 'identifier', 'metadataPrefix' ) ),
			'Identify' => array(),
			'ListIdentifiers' => array(
				'exclusive' =>        'resumptionToken',
				'required'  => array( 'metadataPrefix' ),
				'optional'  => array( 'from', 'until', 'set' ) ),
			'ListMetadataFormats' => array(
				'optional'  => array( 'identifier' ) ),
			'ListRecords' => array(
				'exclusive' =>        'resumptionToken',
				'required'  => array( 'metadataPrefix' ),
				'optional'  => array( 'from', 'until', 'set' ) ),
			'ListSets' => array(
				'exclusive' => 'resumptionToken' )
			);

		$req = array();

		$verb = $request->getVal( 'verb' );
		$req['verb'] = $verb;

		if ( !isset( $verbs[$verb] ) ) {
			throw new ProofreadIndexOaiError( 'Unrecognized or no verb provided.', 'badVerb' );
		}

		$params = $verbs[$verb];

		/* If an exclusive parameter is set, it's the only one we'll see */
		if ( isset( $params['exclusive'] ) ) {
			$exclusive = $request->getVal( $params['exclusive'] );
			if ( !is_null( $exclusive ) ) {
				$req[$params['exclusive']] = $exclusive;
				return $req;
			}
		}

		/* Required parameters must all be present if no exclusive was found */
		if ( isset( $params['required'] ) ) {
			foreach( $params['required'] as $name ) {
				$val = $request->getVal( $name );
				if ( is_null( $val ) ) {
					throw new ProofreadIndexOaiError( 'Missing required argument "' . $name . '"', 'badArgument' );
				} else {
					$req[$name] = $val;
				}
			}
		}

		/* Optionals are, well, optional. */
		if ( isset( $params['optional'] ) ) {
			foreach( $params['optional'] as $name ) {
				$val = $request->getVal( $name );
				if ( !is_null( $val ) ) {
					$req[$name] = $val;
				}
			}
		}
		return $req;
	}

	/**
	 * Output OAI
	 */
	public function outputOai() {
		header( 'Content-type: text/xml; charset=utf-8' );
		echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
		echo Xml::openElement( 'OAI-PMH', array(
			'xmlns' => 'http://www.openarchives.org/OAI/2.0/',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd' ) )
			. "\n";
		$error = '';
		try {
			$this->request = $this->parseRequest();
		} catch (ProofreadIndexOaiError $e ) {
			$error = $e->getXML();
		}
		echo $this->responseDate();
		echo $this->regurgitateRequest();
		if ( $error === '') {
			try {
				$this->doResponse( $this->request['verb'] );
			} catch (ProofreadIndexOaiError $e ) {
				echo $e->getXML();
			}
		} else {
			echo $error;
		}
		echo Xml::closeElement( 'OAI-PMH');
	}

	/**
	 * Return the responseDate node
	 * @return string XML
	 */
	protected function responseDate() {
		return Xml::element( 'responseDate', null, wfTimestamp( TS_ISO_8601 ) ) . "\n";
	}

	/**
	 * Return the request node
	 * @return string XML
	 */
	protected function regurgitateRequest() {
		return Xml::element( 'request', $this->request, $this->baseUrl ) . "\n";
	}

	/**
	 * Output the main OAI content
	 * @param $verb string
	 * @throws MWException
	 */
	protected function doResponse( $verb ) {
		switch( $verb ) {
			case 'Identify':
				$this->identify();
				break;
			case 'ListIdentifiers':
			case 'ListRecords':
				$this->listRecords( $verb );
				break;
			case 'ListSets':
				$this->listSets();
				break;
			case 'ListMetadataFormats':
				$this->listMetadataFormats();
				break;
			case 'GetRecord':
				$this->GetRecord();
				break;
			default:
				throw new MWException( 'Verb not implemented' );
		}
	}

	/**
	 * Output the Identify action
	 */
	protected function identify() {
		echo Xml::openElement( 'Identify' ) . "\n";
		$this->identifyInfo();
		$this->identifierDescription();
		$this->eprintDescription();
		$this->brandingDescription();
		echo Xml::closeElement( 'Identify' ) . "\n";
	}

	/**
	 * Output the main informations about the repository
	 */
	protected function identifyInfo() {
		global $wgEmergencyContact;
		echo Xml::element( 'repositoryName', null, $this->msg( 'proofreadpage-indexoai-repositoryName' )->inContentLanguage()->text() ) . "\n";
		echo Xml::element( 'baseURL', null, $this->baseUrl ) . "\n";
		echo Xml::element( 'protocolVersion', null, '2.0' ) . "\n";
		echo Xml::element( 'adminEmail', null, $wgEmergencyContact ) . "\n";
		echo Xml::element( 'earliestDatestamp', null, self::datestamp( $this->earliestDatestamp() ) ) . "\n";
		echo Xml::element( 'deletedRecord', null, 'no' ) . "\n";
		echo Xml::element( 'granularity', null, 'YYYY-MM-DDThh:mm:ssZ' ) . "\n";
		if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false )
			echo Xml::element( 'compression', null, 'gzip' ) . "\n";
		if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate' ) !== false )
			echo Xml::element( 'compression', null, 'deflate' ) . "\n";
	}

	/**
	 * Output the indentifier sheme used by the repository
	 * @see http://www.openarchives.org/OAI/2.0/guidelines-oai-identifier.htm
	 */
	protected function identifierDescription() {
		echo Xml::openElement( 'description' ) . "\n";
		echo Xml::openElement( 'oai-identifier', array(
			'xmlns' => 'http://www.openarchives.org/OAI/2.0/oai-identifier',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd' ) )
			. "\n";
		echo Xml::element( 'scheme', null, 'oai' ) . "\n";
		echo Xml::element( 'repositoryIdentifier', null, $this->repositoryIdentifier() ) . "\n";
		echo Xml::element( 'delimiter', null, ':' ) . "\n";
		echo Xml::element( 'sampleIdentifier', null, 'oai:' . $this->repositoryIdentifier() . ':' . $this->repositoryBasePath() . '/La_Fontaine_-_The_Original_Fables_Of,_1913.djvu' ) . "\n";
		echo Xml::closeElement( 'oai-identifier' ) . "\n";
		echo Xml::closeElement( 'description' ) . "\n";
	}

	/*
	 * Output some informations about content of the repository
	 * @see http://www.openarchives.org/OAI/2.0/guidelines-eprints.htm
	 */
	protected function eprintDescription() {
		echo Xml::openElement( 'description' ) . "\n";
		echo Xml::openElement( 'eprints', array(
			'xmlns' => 'http://www.openarchives.org/OAI/1.1/eprints',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/1.1/eprints http://www.openarchives.org/OAI/1.1/eprints.xsd' ) )
			. "\n";

		$url = $this->msg( 'proofreadpage-indexoai-eprint-content-url' )->inContentLanguage()->text();
		$text = $this->msg( 'proofreadpage-indexoai-eprint-content-text' )->inContentLanguage()->text();
		$this->addEprintEntry( 'content', $url, $text );

		$about = Title::newFromText( $this->msg( 'Aboutpage' )->inContentLanguage()->text() );
		if ( $about !== null ) {
			$aboutUrl = $about->getCanonicalUrl();
			$this->addEprintEntry( 'metadataPolicy', $aboutUrl, null );
			$this->addEprintEntry( 'dataPolicy', $aboutUrl, null );
		}
		echo Xml::closeElement( 'eprints' ) . "\n";
		echo Xml::closeElement( 'description' ) . "\n";
	}

	/*
	 * Output an entry of the eprints description
	 */
	protected function addEprintEntry( $element, $url, $text ) {
		if ( $url || $text ) {
			echo Xml::openElement( $element ) . "\n";
			if ( $url ) {
				echo Xml::element( 'URL', null, $url ) . "\n";
			}
			if ( $text ) {
				echo Xml::element( 'text', null, $text ) . "\n";
			}
			echo Xml::closeElement( $element ) . "\n";
		} else {
			echo Xml::element( $element, null, '' ) . "\n";
		}
	}

	/**
	 * Output some information about presentation of the repository
	 * @see http://www.openarchives.org/OAI/2.0/guidelines-branding.htm
	 */
	protected function brandingDescription() {
		global $wgLogo, $wgSitename;
		if ( !isset( $wgLogo ) ) {
			return;
		}

		echo Xml::openElement( 'description' ) . "\n";
		echo Xml::openElement( 'branding', array(
			'xmlns' => 'http://www.openarchives.org/OAI/2.0/branding/',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/branding/ http://www.openarchives.org/OAI/2.0/branding.xsd' ) )
			. "\n";
		echo Xml::openElement( 'collectionIcon' ) . "\n";
		echo Xml::element( 'url', null, self::normalizeFullUrl( $wgLogo ) ) . "\n";
		echo Xml::element( 'link', null, Title::newMainPage()->getCanonicalUrl() ) . "\n";
		echo Xml::element( 'title', null, $wgSitename ) . "\n";
		echo Xml::closeElement( 'collectionIcon' ) . "\n";
		echo Xml::closeElement( 'branding' ) . "\n";
		echo Xml::closeElement( 'description' ) . "\n";
	}

	protected function stripIdentifier( $identifier ) {
		$prefix = 'oai:' . self::repositoryIdentifier() . ':' . self::repositoryBasePath() . '/';
		if ( substr( $identifier, 0, strlen( $prefix ) ) == $prefix ) {
			return substr( $identifier, strlen( $prefix ) );
		}
		return false;
	}

	/**
	 * Return the repositoryIndentifier ie the domain name of the website.
	 * @return string
	 */
	public static function repositoryIdentifier() {
		static $prefix;
		if ( $prefix ) {
			return $prefix;
		}

		global $wgServer;
		$prefix = parse_url( self::normalizeFullUrl( $wgServer ), PHP_URL_HOST );
		return $prefix;
	}

	/**
	 * Return the base path for all the records of the repository
	 * @return string
	 */
	public static function repositoryBasePath() {
		return 'prpIndex';
	}

	/**
	 * Add if needed the base URL or the sheme
	 * @param $url string
	 * @return string
	 */
	protected static function normalizeFullUrl( $url ) {
		global $wgServer;

		if ( $url[0] === '/' ) {
			if ( $url[1] === '/' ) {
				return 'http:' . $url;
			} else {
				return $wgServer . $url;
			}
		} else {
			return $url;
		}
	}

	/**
	 * Return the earliest last rev_timestamp of an index page
	 * @throws MWException
	 * @return string
	 */
	protected function earliestDatestamp() {
		global $wgMemc;
		$memcKey = wfMemcKey( 'proofreadindexoai-earliestdatestamp' );
		$datestamp = $wgMemc->get( $memcKey );

		if ( $datestamp === false ) {
			$row = $this->db->selectRow(
				array( 'revision', 'page' ),
				array( 'MIN(rev_timestamp) AS min' ),
				array( 'rev_id = page_latest' ) + array( 'page_namespace' => ProofreadPage::getIndexNamespaceId() ),
				__METHOD__
			);
			if ( $row->min ) {
				$datestamp = $row->min;
				$wgMemc->set( $memcKey, $datestamp, 60*60*24 );
			} else {
				throw new MWException( 'Bogus result.' );
			}
		}

		return $datestamp;
	}

	/**
	 * Output the ListSets action
	 */
	protected function listSets() {
		//try if the page exist (required by specification)
		if ( !ProofreadIndexOaiSets::withSets() ) {
			throw new ProofreadIndexOaiError( "This repository doesn't support sets.", 'noSetHierarchy' );
		}

		$sets = ProofreadIndexOaiSets::getSetsBySpec();
		echo Xml::openElement( 'ListSets' ) . "\n";
		foreach( $sets as $set ) {
			echo Xml::openElement( 'set' ) . "\n";
			echo Xml::element( 'setSpec', null, $set['spec'] ) . "\n";
			echo Xml::element( 'setName', null, $set['name'] ) . "\n";
			if ( isset( $set['description'] ) ) {
				echo Xml::openElement( 'setDescription' ) . "\n";
				echo Xml::openElement( 'oai_dc:dc', array(
					'xmlns:oai_dc' => 'http://www.openarchives.org/OAI/2.0/oai_dc/',
					'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
					'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
					'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd' ) )
					. "\n";
				echo Xml::element( 'dc:description', null, $set['description'] ) . "\n";
				echo Xml::closeElement( 'oai_dc:dc' ) . "\n";
				echo Xml::closeElement( 'setDescription' ) . "\n";
			}
			echo Xml::closeElement( 'set' ) . "\n";
		}
		echo Xml::closeElement( 'ListSets' ) . "\n";
	}

	/**
	 * Output the ListMetadataFormats action
	 */
	protected function listMetadataFormats() {
		//try if the page exist (required by specification)
		if ( isset( $this->request['identifier'] ) ) {
			$page = $this->getRecordPage( $this->request['identifier'] );
		}

		$formats = $this->metadataFormats();
		echo Xml::openElement( 'ListMetadataFormats' ) . "\n";
		foreach( $formats as $prefix => $format ) {
			echo Xml::openElement( 'metadataFormat' ) . "\n";
			echo Xml::element( 'metadataPrefix', null, $prefix ) . "\n";
			echo Xml::element( 'schema', null, $format['schema'] ) . "\n";
			echo Xml::element( 'metadataNamespace', null, $format['namespace'] ) . "\n";
			echo Xml::closeElement( 'metadataFormat' ) . "\n";
		}
		echo Xml::closeElement( 'ListMetadataFormats' ) . "\n";
	}

	/**
	 * Return the list of metadata formats available in the repository
	 * @return string
	 */
	protected function metadataFormats() {
		return array(
			'oai_dc' => array(
				'namespace' => 'http://www.openarchives.org/OAI/2.0/oai_dc/',
				'schema'    => 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd'
			),
			'prp_qdc' => array(
				'namespace' => 'http://mediawiki.org/xml/proofreadpage/qdc/',
				'schema'    => Title::makeTitle( NS_SPECIAL, 'ProofreadIndexOaiSchema/qdc' )->getFullURL()
			)
		);
	}

	/**
	 * check if the parameter is a valid metadata format and return it
	 * @param $var string Request parameter that contain a metadata format
	 * @throws ProofreadIndexOaiError
	 * @return string|null
	 */
	protected function validateMetadata( $var ) {
		if ( !isset( $this->request[$var] ) ) {
			return null;
		}
		$prefix = $this->request[$var];
		$formats = $this->metadataFormats();
		if ( isset( $formats[$prefix] ) ) {
			return $this->request[$var];
		} else {
			throw new ProofreadIndexOaiError( 'Requested unsupported metadata format.', 'cannotDisseminateFormat' );
		}
	}

	/**
	 * Output the GetRecord action
	 */
	protected function getRecord() {
		$metadataPrefix = $this->validateMetadata( 'metadataPrefix' );
		$page = $this->getRecordPage( $this->request['identifier'] );
		$item = new ProofreadIndexOaiRecord( $page, $this->getRecordDatestamp( $page->getTitle() ) );
		echo Xml::openElement( 'GetRecord' ) . "\n";
		echo $item->renderRecord( $metadataPrefix );
		echo Xml::closeElement( 'GetRecord' ) . "\n";
	}

	/**
	 * Get the Title page for a record
	 * @param $identifier string
	 * @throws ProofreadIndexOaiError
	 * @return ProofreadIndexPage
	 */
	protected function getRecordPage( $identifier ) {
		$pageid = urldecode( $this->stripIdentifier( $identifier ) );
		if ( $pageid ) {
			$title = Title::makeTitleSafe( ProofreadPage::getIndexNamespaceId(), $pageid );
			if ( $title !== null && $title->exists() ) {
				return ProofreadIndexPage::newFromTitle( $title );
			}
		}
		throw new ProofreadIndexOaiError( 'Requested identifier is invalid or does not exist.', 'idDoesNotExist' );
	}

	/**
	 * Return the datestamp for a record
	 * @param $title Title
	 * @throws MWException
	 * @return string rev_timestamp
	 */
	protected function getRecordDatestamp( Title $title ) {
		$row = $this->db->selectRow(
			array( 'page', 'revision' ),
			array( 'rev_timestamp' ),
			$title->pageCond(),
			__METHOD__,
			array(),
			array( 'page' => array( 'JOIN', 'rev_id = page_latest' ) )
		);
		if ( $row->rev_timestamp ) {
			return $row->rev_timestamp;
		} else {
			throw new MWException( 'Bogus result.' );
		}
	}

	/**
	 * Check if an OAI datestamp is valid
	 * @param $var string Request parameter that contain a datestamp
	 * @param $defaultTime string default time for a date-only datestamp as HHMMSS
	 * @throws ProofreadIndexOaiError
	 * @return string|null timestamp in MW format
	 */
	protected function validateDatestamp( $var, $defaultTime = '000000' ) {
		if ( !isset( $this->request[$var] ) ) {
			return null;
		}
		$time = $this->request[$var];
		$matches = array();
		if ( preg_match( '/^(\d\d\d\d)-(\d\d)-(\d\d)$/', $time, $matches ) ) {
			return wfTimestamp( TS_MW, $matches[1] . $matches[2] . $matches[3] . $defaultTime );
		} elseif ( preg_match( '/^(\d\d\d\d)-(\d\d)-(\d\d)T(\d\d):(\d\d):(\d\d)Z$/', $time, $matches ) ) {
			return wfTimestamp( TS_MW, $matches[1] . $matches[2] . $matches[3] . $matches[4] . $matches[5] . $matches[6] );
		} else {
			throw new ProofreadIndexOaiError( "Illegal timestamp format in '$var'", 'badArgument' );
		}
	}

	/**
	 * Check if a token is valid
	 * @param $var string Request parameter that contain a token
	 * @throws ProofreadIndexOaiError
	 * @return array|null the token content
	 */
	protected function validateToken( $var ) {
		if ( !isset( $this->request[$var] ) ) {
			return null;
		}
		$matches = array();
		if ( preg_match( '/^([a-z_]+):(\d+):(\d+)(?:|:(\d{14}))$/', $this->request[$var], $matches ) ) {
			$token = array();
			$token['metadataPrefix'] = $matches[1];
			$token['resume_id'] = isset( $matches[2] ) ? intval( $matches[2] ) : null;
			$token['cursor'] = isset( $matches[3] ) ? intval( $matches[3] ) : null;
			$token['until'] = isset( $matches[4] ) ? wfTimestamp( TS_MW, $matches[4] ) : null;
			$formats = $this->metadataFormats();
			if ( isset( $formats[$token['metadataPrefix']] ) ) {
				return $token;
			}
		}
		throw new ProofreadIndexOaiError( 'Invalid resumption token.', 'badResumptionToken' );
	}

	/**
	 * Return the max size of a record list
	 * @return int
	 */
	protected function getChunkSize() {
		return 50;
	}

	/**
	 * Output the ListRecords or ListIdentifiers action
	 */
	protected function listRecords( $verb ) {
		$withData = ($verb == 'ListRecords');

		$category = null;
		$startToken = $this->validateToken( 'resumptionToken' );
		if ( $startToken !== null ) {
			$metadataPrefix = $startToken['metadataPrefix'];
			$from = null;
			$until = $startToken['until'];
		} else {
			$metadataPrefix = $this->validateMetadata( 'metadataPrefix' );
			$from = $this->validateDatestamp( 'from', '000000' );
			$until = $this->validateDatestamp( 'until', '235959' );
			if ( isset( $this->request['set'] ) ) {
				$category = ProofreadIndexOaiSets::getCategoryForSpec( $this->request['set'] );
				if ( $category === null ) {
					throw new ProofreadIndexOaiError( 'No records available match the request.', 'noRecordsMatch' );
				}
			}
		}

		# Fetch one extra row to check if we need a resumptionToken
		$chunk = $this->getChunkSize();
		$resultSet = $this->getIndexRows( $from, $until, $chunk + 1, $startToken, $category );
		$count = min( $resultSet->numRows(), $chunk );
		if ( !$count ) {
			throw new ProofreadIndexOaiError( 'No records available match the request.', 'noRecordsMatch' );
		}

		// buffer everything up
		$rows = array();
		for( $i = 0; $i < $count; $i++ ) {
			$row = $resultSet->fetchObject();
			$rows[] = $row;
		}
		$row = $resultSet->fetchObject();
		if ( $row ) {
			$limit = $until;
			if ( $until ) {
				$nextToken = $metadataPrefix . ':' . ':' . $row->page_id . ':' . ($startToken['cursor'] + $count) . $limit;
			} else {
				$nextToken = $metadataPrefix . ':' . $row->page_id . ':' . ($startToken['cursor'] + $count);
			}
		}
		$resultSet->free();

		// render
		echo Xml::openElement( $verb ) . "\n";
		foreach( $rows as $row ) {
			$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
			if ( $title !== null ) {
				$item = new ProofreadIndexOaiRecord( ProofreadIndexPage::newFromTitle( $title ), $row->rev_timestamp );
				if ( $withData ) {
					echo $item->renderRecord( $metadataPrefix );
				} else {
					echo $item->renderHeader();
				}
			}
		}

		if ( isset( $nextToken ) ) {
			$cursor = isset( $startToken['cursor'] ) ? $startToken['cursor'] : 0;
			echo Xml::element( 'resumptionToken', array( 'cursor' => $cursor ), $nextToken ) . "\n";
		} //TODO Add <resumptionToken completeListSize="6" cursor="4"/> http://www.openarchives.org/OAI/openarchivesprotocol.html#ListIdentifiers
		echo Xml::closeElement( $verb ) . "\n";
	}

	protected function getIndexRows( $from, $until, $chunk, $token = null, $category = null ) {
		$tables = array( 'page', 'revision' );
		$fields = array( 'page_namespace', 'page_title', 'page_id', 'rev_timestamp', 'rev_id' );
		$conds = array( 'rev_id = page_latest' );
		$conds['page_namespace'] = ProofreadPage::getIndexNamespaceId();
		$options = array( 'LIMIT' => $chunk, 'ORDER BY' => array( 'page_id ASC' ) );

		if ( $token !== null ) {
			$conds[] = 'page_id >= ' . $this->db->addQuotes( $token['resume_id'] );
		}

		if ( $from !== null ) {
			$conds[] = 'rev_timestamp >= ' . $this->db->addQuotes( $from );
		}
		if ( $until !== null ) {
			$conds[] = 'rev_timestamp <= ' . $this->db->addQuotes( $until );
		}

		if ( $category !== null ) {
			$tables[] = 'categorylinks';
			$conds['cl_to'] = $category;
			$conds[] = 'cl_from = page_id';
		}

		return $this->db->select( $tables, $fields, $conds, __METHOD__, $options );
	}
}
