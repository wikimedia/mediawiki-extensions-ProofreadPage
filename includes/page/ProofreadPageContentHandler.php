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
 * Content handler for a Page: pages
 */
class ProofreadPageContentHandler extends TextContentHandler {

	/**
	 * @var WikitextContentHandler
	 */
	protected $wikitextContentHandler;

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_PROOFREAD_PAGE ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_WIKITEXT ) );
		$this->wikitextContentHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @see ContentHandler::serializeContent
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		$level = $content->getLevel();
		$text = '<noinclude><pagequality level="' . $level->getLevel() . '" user="';
		if ( $level->getUser() instanceof User ) {
			$text .= $level->getUser()->getName();
		}
		$text .= '" /><div class="pagetext">' . $content->getHeader()->serialize() . "\n\n\n" . '</noinclude>';
		$text .= $content->getBody()->serialize();
		$text .= '<noinclude>' . $content->getFooter()->serialize() . '</div></noinclude>';
		return $text;
	}

	/**
	 * @see ContentHandler::unserializeContent
	 */
	public function unserializeContent( $text, $format = null ) {
		$header = '';
		$footer = '';
		$proofreader = '';
		$level = 1;

		if ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*)<noinclude>(.*?)<\/noinclude>$/s', $text, $m ) ) {
			$header = $m[1];
			$body = $m[2];
			$footer = $this->cleanTrailingDivTag( $m[3] );
		} elseif ( preg_match( '/^<noinclude>(.*?)\n*<\/noinclude>(.*?)$/s', $text, $m ) ) {
			$header = $m[1];
			$body = $this->cleanTrailingDivTag( $m[2] );
		} else {
			$body = $text;
		}

		if ( preg_match( '/^<pagequality level="(0|1|2|3|4)" user="(.*?)" \/>(.*?)$/s', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[2];
			$header = $this->cleanHeader( $m[3] );
		} elseif ( preg_match( '/^\{\{PageQuality\|(0|1|2|3|4)(|\|(.*?))\}\}(.*)/is', $header, $m ) ) {
			$level = intval( $m[1] );
			$proofreader = $m[3];
			$header = $this->cleanHeader( $m[4] );
		}

		return new ProofreadPageContent(
			$this->wikitextContentHandler->unserializeContent( $header ),
			$this->wikitextContentHandler->unserializeContent( $body ),
			$this->wikitextContentHandler->unserializeContent( $footer ),
			new ProofreadPageLevel( $level, ProofreadPageLevel::getUserFromUserName( $proofreader ) )
		);
	}

	protected function cleanTrailingDivTag( $text ) {
		if ( preg_match( '/^(.*?)<\/div>$/s', $text, $m2 ) ) {
			return  $m2[1];
		} else {
			return $text;
		}
	}

	protected function cleanHeader( $header ) {
		if ( preg_match('/^(.*?)<div class="pagetext">(.*?)$/s', $header, $mt) ) {
			$header = $mt[2];
		} elseif ( preg_match('/^(.*?)<div>(.*?)$/s', $header, $mt) ) {
			$header = $mt[2];
		}
		return $header;
	}

	/**
	 * @see ContentHandler::getActionOverrides
	 */
	public function getActionOverrides() {
		return array(
			'edit' => 'ProofreadPage\Page\PageEditAction',
			'submit' => 'ProofreadPage\Page\PageSubmitAction',
			'view' => 'ProofreadPageViewAction'
		);
	}

	/**
	 * @see ContentHandler::getDiffEngineClass
	 */
	protected function getDiffEngineClass() {
		return 'ProofreadPageDifferenceEngine';
	}

	/**
	 * @see ContentHandler::makeEmptyContent
	 */
	public function makeEmptyContent() {
		return new ProofreadPageContent(
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeEmptyContent(),
			new ProofreadPageLevel()
		);
	}

	/**
	 * @see ContentHandler::merge3
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

		if ( $myContent->getLevel()->getLevel() !== $yourContent->getLevel()->getLevel() ) {
			return false;
		}

		$wikitextHandler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );
		$mergedHeader = $myContent->getHeader()->equals( $yourContent->getHeader() )
			? $myContent->getHeader()
			: $wikitextHandler->merge3( $oldContent->getHeader(), $myContent->getHeader(), $yourContent->getHeader() );
		$mergedBody = $myContent->getBody()->equals( $yourContent->getBody() )
			? $myContent->getBody()
			: $wikitextHandler->merge3( $oldContent->getBody(), $myContent->getBody(), $yourContent->getBody() );
		$mergedFooter = $myContent->getFooter()->equals( $yourContent->getFooter() )
			? $yourContent->getFooter()
			: $wikitextHandler->merge3( $oldContent->getFooter(), $myContent->getFooter(), $yourContent->getFooter() );

		if ( !$mergedHeader || !$mergedBody || !$mergedFooter ) {
			return false;
		}

		return new ProofreadPageContent(
			$mergedHeader,
			$mergedBody,
			$mergedFooter,
			$yourContent->getLevel()
		);
	}

	/**
	 * @see ContentHandler::getAutosummary
	 */
	public function getAutosummary( Content $oldContent = null, Content $newContent = null, $flags ) {
		$summary = parent::getAutosummary( $oldContent, $newContent, $flags );

		if (
			$newContent instanceof ProofreadPageContent &&
			( $oldContent === null || $oldContent instanceof ProofreadPageContent && !$newContent->getLevel()->equals( $oldContent->getLevel() ) )
		) {
			$summary = trim( '/* ' . $newContent->getLevel()->getLevelCategoryName() . ' */ ' . $summary );
		}

		return $summary;
	}

	/**
	 * @see ContentHandler::makeParserOptions
	 */
	public function makeParserOptions( $context ) {
		$parserOptions = parent::makeParserOptions( $context );
		$parserOptions->setEditSection( false );
		return $parserOptions;
	}

	/**
	 * @see ContentHandler::makeRedirectContent
	 * @todo is it the right content for redirects?
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		return new ProofreadPageContent(
			$this->wikitextContentHandler->makeEmptyContent(),
			$this->wikitextContentHandler->makeRedirectContent( $destination, $text ),
			$this->wikitextContentHandler->makeEmptyContent(),
			new ProofreadPageLevel()
		);
	}

	/**
	 * @see ContentHandler::supportsRedirects
	 */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * @see ContentHandler::isParserCacheSupported
	 */
	public function isParserCacheSupported() {
		return true;
	}
}
