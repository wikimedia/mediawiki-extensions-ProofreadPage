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

class EditProofreadIndexPage extends EditPage {

	protected function isSectionEditSupported() {
		return false; // sections and forms don't mix
	}

	/**
	 * Add custom fields
	 */
	protected function showContentForm() {
		$pageLang = $this->mTitle->getPageLanguage();
		$out = $this->mArticle->getContext()->getOutput();
		$inputAttributes = array( 'lang' => $pageLang->getCode(), 'dir' => $pageLang->getDir() );
		if ( wfReadOnly() ) {
			$inputAttributes['readonly'] = '';
		}

		$index = new ProofreadIndexPage( $this->mTitle, ProofreadIndexPage::getDataConfig(), $this->textbox1 );
		$entries = $index->getIndexEntries();

		$out->addHTML( Html::openElement( 'table', array( 'id' => 'prp-formTable' ) ) );
		$i = 10;
		foreach( $entries as $entry ) {
			$inputAttributes['tabindex'] = $i;
			$this->addEntry( $entry, $inputAttributes, $out );
			$i++;
		}
		$out->addHTML( Html::closeElement( 'table' ) );

		$out->addModules( 'ext.proofreadpage.index' );
	}

	/**
	 * Add an entry to the form
	 *
	 * @param $entry ProofreadIndexEntry
	 * @param $inputAttributes array
	 */
	protected function addEntry( ProofreadIndexEntry $entry, $inputAttributes, OutputPage $out ) {
		if ( $entry->isHidden() ) {
			return;
		}
		$key = $this->getFieldNameForEntry( $entry->getKey() );
		$val = $this->safeUnicodeOutput( $entry->getStringValue() );

		$out->addHTML(
			Html::openElement( 'tr' ) .
				Html::openElement( 'th', array( 'scope' => 'row' ) ) .
					Xml::label( $entry->getLabel(), $key )
		);


		$help = $entry->getHelp();
		if ( $help !== '' ) {
			$out->addHTML( Html::element( 'span', array( 'title' => $help, 'class' => 'prp-help-field' ) ) );
		}

		$out->addHTML(
			Html::closeElement( 'th' ) .
			Html::openElement( 'td' )
		);

		$values = $entry->getPossibleValues();
		if ( $values !== null ) {
			$select = new XmlSelect( $key, $key, $val );
			foreach( $values as $value => $label ) {
				$select->addOption( $label, $value );
			}
			if ( !isset( $values[$val] ) && $val !== '' ) { //compatiblity with already set data that aren't in the list
				$select->addOption( $val, $val );
			}
			$out->addHTML( $select->getHtml() );
		} else {
			$type = $entry->getType();
			$inputType = ( $type === 'number' ) ? 'number' : 'text';
			$size = $entry->getSize();
			$inputAttributes['class'] = 'prp-input-' . $type;

			if ( $size === 1 ) {
				$inputAttributes['type'] = $inputType;
				$inputAttributes['id'] = $key;
				$inputAttributes['size'] = 60;
				$out->addHTML( Html::input( $key, $val, $inputType, $inputAttributes ) );
			}
			else {
				$inputAttributes['cols'] = 60;
				$inputAttributes['rows'] = $size;
				$out->addHTML( Html::textarea( $key, $val, $inputAttributes ) );
			}
		}

		$out->addHTML(
				Html::closeElement( 'td' ) .
			Html::closeElement( 'tr' )
		);
	}

	/**
	 * Return the name of the edit field for an entry
	 *
	 * @param $key string the entry key
	 * @return string
	 */
	protected function getFieldNameForEntry( $key ) {
		return 'wpprpindex-' . str_replace( ' ', '_', $key );
	}

	/**
	 * Extract the page content data from the posted form
	 *
	 * @param $request WebRequest
	 * @return string
	 */
	protected function importContentFormData( &$request ) {
		if ( $this->textbox1 !== '' ) {
			return $this->textbox1;
		}

		$config = ProofreadIndexPage::getDataConfig();
		$text = "{{:MediaWiki:Proofreadpage_index_template";
		foreach( $config as $key => $params ) {
			$field = $this->getFieldNameForEntry( $key );
			$value = $this->cleanInputtedContent( $this->safeUnicodeInput( $request, $field ) );
			$entry = new ProofreadIndexEntry( $key, $value, $params );
			if( !$entry->isHidden() ) {
				$text .= "\n|" . $entry->getKey() . "=" . $entry->getStringValue();
			}
		}
		return $text . "\n}}";
	}

	/**
	 * Clean a text before inclusion into a template
	 *
	 * @param $value string
	 * @return string
	 */
	protected function cleanInputtedContent( $value ) {
		// remove trailing \n \t or \r
		$value = trim( $value );

		// replace pipe symbol everywhere...
		$value = preg_replace( '/\|/', '&!&', $value );

		// ...except in links...
		$prev = '';
		do {
			$prev = $value;
			$value = preg_replace( '/\[\[(.*?)&!&(.*?)\]\]/', '[[$1|$2]]', $value );
		} while ( $value != $prev );

		// ..and in templates
		do {
			$prev = $value;
			$value = preg_replace( '/\{\{(.*?)&!&(.*?)\}\}/s', '{{$1|$2}}', $value );
		} while ( $value != $prev );

		$value = preg_replace( '/&!&/', '{{!}}', $value );

		return $value;
	}


	/**
	 * Check the validity of the page
	 */
	function internalAttemptSave( &$result, $bot = false ) {
		$index = new ProofreadIndexPage( $this->mTitle, ProofreadIndexPage::getDataConfig(), $this->textbox1 );

		//Get list of pages titles
		$links = $index->getLinksToPageNamespace();
		$linksTitle = array();
		foreach( $links as $link ) {
			$linksTitle[] = $link[0];
		}

		if ( count( $linksTitle ) !== count( array_unique( $linksTitle ) ) ) {
			$this->mArticle
				->getContext()
				->getOutput()
				->showErrorPage( 'proofreadpage_indexdupe', 'proofreadpage_indexdupetext' );
			$status = Status::newGood();
			$status->fatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}

		return parent::internalAttemptSave( $result, $bot );
	}
}
