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
 * Provide OAI record of an index page
 */
class ProofreadIndexOaiRecord {
	protected $index;
	protected $lang;
	protected $entries = array();
	protected $lastEditionTimestamp;

	/**
	 * @param $index ProofreadIndexPage
	 * @param $lastEditionTimestamp string MW timestamp of the last edition
	 */
	public function __construct( ProofreadIndexPage $index, $lastEditionTimestamp ) {
		$this->index = $index;
		$this->lang = $this->index->getTitle()->getPageLanguage();
		$this->lastEditionTimestamp = $lastEditionTimestamp;
	}

	/**
	 * Return OAI record of an index page.
	 * @param $format string
	 * @throws MWException
	 * @return string
	 */
	public function renderRecord( $format ) {
		$record = Xml::openElement( 'record' ) . "\n";
		$record .= $this->renderHeader();
		$record .= Xml::openElement( 'metadata' ) . "\n";
		switch( $format ) {
			case 'oai_dc':
				$record .= $this->renderOaiDc();
				break;
			case 'prp_qdc':
				$record .= $this->renderDcQdc();
				break;
			default:
				throw new MWException( 'Unsupported metadata format.' );
		}
		$record .= Xml::closeElement( 'metadata' ) . "\n";
		$record .= Xml::closeElement( 'record' ) . "\n";
		return $record;
	}

	/**
	 * Return header of an OAI record of an index page.
	 * @return string
	 */
	public function renderHeader() {
		$text = Xml::openElement('header') . "\n";
		$text .= Xml::element( 'identifier', null, $this->getIdentifier() ) . "\n";
		$text .= Xml::element( 'datestamp',  null, SpecialProofreadIndexOai::datestamp( $this->lastEditionTimestamp ) ) . "\n";
		$sets = ProofreadIndexOaiSets::getSetSpecsForTitle( $this->index->getTitle() );
		foreach( $sets as $set ) {
			$text .= Xml::element( 'setSpec',  null, $set ) . "\n";
		}
		$text .= Xml::closeElement( 'header' ) . "\n";
		return $text;
	}

	/**
	 * Return identifier of the record.
	 * @return string
	 */
	protected function getIdentifier() {
		return 'oai:' . SpecialProofreadIndexOai::repositoryIdentifier() . ':' . SpecialProofreadIndexOai::repositoryBasePath() . '/' . wfUrlencode( $this->index->getTitle()->getDBkey() );
	}

	/**
	 * Return OAI DC record of an index page.
	 * @return string
	 */
	protected function renderOaiDc() {
		global $wgMimeType;

		$record = Xml::openElement( 'oai_dc:dc', array(
			'xmlns:oai_dc' => 'http://www.openarchives.org/OAI/2.0/oai_dc/',
			'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd' ) )
			. "\n";

		$record .= Xml::element( 'dc:type', null, 'Text' );
		$record .= Xml::element( 'dc:format', null, $wgMimeType );

		$mime = $this->index->getMimeType();
		if ( $mime ) {
			$record .= Xml::element( 'dc:format', null, $mime );
		}

		$metadata = $this->index->getIndexEntries();
		foreach( $metadata as $entry ) {
			$record .= $this->getOaiDcEntry( $entry );
		}
		$record .= Xml::closeElement( 'oai_dc:dc' );
		return $record;
	}

	/**
	 * Return Dublin Core entry
	 * @param $entry ProofreadIndexEntry
	 * @throws MWException
	 * @return string
	 */
	protected function getOaiDcEntry( ProofreadIndexEntry $entry ) {
		$key = $entry->getSimpleDublinCoreProperty();
		if ( !$key ) {
			return '';
		}

		$text = '';
		$values = $entry->getTypedValues();
		foreach( $values as $value ) {
			switch( $value->getMainType() ) {
				case 'string':
					$text .= Xml::element( $key, array( 'xml:lang' => $this->lang->getHtmlCode() ), $value ) . "\n";
					break;
				case 'page':
					$text .= Xml::element( $key, null, $value->getMainText() ) . "\n";
					break;
				case 'number':
					$text .= Xml::element( $key, null, $value ) . "\n";
					break;
				case 'identifier':
					$text .= Xml::element( $key, null, $value->getUri() ) . "\n";
					break;
				case 'langcode':
					$text .= Xml::element( $key, null, $value ) . "\n";
					break;
				default:
					throw new MWException( 'Unknown type: ' . $entry->getType() );
			}
		}
		return $text;
	}

	/**
	 * Return Qualified Dublin Core record of an index page.
	 * @return string
	 */
	protected function renderDcQdc() {
		global $wgMimeType;
		$record = Xml::openElement( 'prp_qdc:qdc', array(
			'xmlns:prp_qdc' => 'http://mediawiki.org/xml/proofreadpage/qdc/',
			'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
			'xmlns:dcterms' => 'http://purl.org/dc/terms/',
			'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
			'xsi:schemaLocation' => 'http://mediawiki.org/xml/proofreadpage/qdc/ ' . Title::makeTitle( NS_SPECIAL, 'ProofreadIndexOaiSchema/qdc')->getFullURL() ) )
			. "\n";

		$record .= Xml::element( 'dc:type', array( 'xsi:type' => 'dcterms:DCMIType' ), 'Text' );
		$record .= Xml::element( 'dc:format', array( 'xsi:type' => 'dcterms:IMT' ), $wgMimeType );

		$mime = $this->index->getMimeType();
		if ( $mime ) {
			$record .= Xml::element( 'dc:format', array( 'xsi:type' => 'dcterms:IMT' ), $mime );
		}

		$metadata = $this->index->getIndexEntries();
		foreach( $metadata as $entry ) {
			$record .= $this->getDcQdcEntry( $entry );
		}
		$record .= Xml::closeElement( 'prp_qdc:qdc' );
		return $record;
	}

	/**
	 * Return Qualified Dublin Core entry
	 * @param $entry ProofreadIndexEntry
	 * @throws MWException
	 * @return string
	 */
	protected function getDcQdcEntry( ProofreadIndexEntry $entry ) {
		$key = $entry->getQualifiedDublinCoreProperty();
		if ( !$key ) {
			return '';
		}

		$text = '';
		$values = $entry->getTypedValues();
		foreach( $values as $value ) {
			switch( $value->getMainType() ) {
				case 'string':
					$text .= Xml::element( $key, array( 'xml:lang' => $this->lang->getHtmlCode() ), $value ) . "\n";
					break;
				case 'page':
					$text .= Xml::element( $key, null, $value->getMainText() ) . "\n";
					break;
				case 'number':
					$text .= Xml::element( $key, array( 'xsi:type' => 'xsi:decimal' ), $value ) . "\n";
					break;
				case 'identifier':
					$text .= Xml::element( $key, array( 'xsi:type' => 'dcterms:URI' ), $value->getUri() ) . "\n";
					break;
				case 'langcode':
					$text .= Xml::element( $key, array( 'xsi:type' => 'dcterms:RFC5646' ), $value ) . "\n";
					break;
				default:
					throw new MWException( 'Unknown type: ' . $entry->getType() );
			}
		}
		return $text;
	}
}
