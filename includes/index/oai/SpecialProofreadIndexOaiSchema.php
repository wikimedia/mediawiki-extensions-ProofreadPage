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
 * A special page to return XML schema used by ProofreadPage OAi-PMH API
 */
class SpecialProofreadIndexOaiSchema extends UnlistedSpecialPage {
	protected $namespaces = array( 'qdc' );

	public function __construct() {
		parent::__construct( 'ProofreadIndexOaiSchema' );
	}

	public function execute( $namespace ) {
		$output = $this->getOutput();

		if ( $namespace === '' || !in_array($namespace, $this->namespaces) ) {
			$this->getRequest()->response()->header( 'HTTP/1.1 404 Not Found' );
			$output->showErrorPage( 'proofreadpage-indexoai-error-schemanotfound', 'proofreadpage-indexoai-error-schemanotfound-text', array( $namespace ) );
		} else {
			$output->disable();
			header( 'Content-type: text/xml; charset=utf-8' );
			readfile( __DIR__ . '/schemas/' . $namespace . '.xsd' );
		}
	}
}
