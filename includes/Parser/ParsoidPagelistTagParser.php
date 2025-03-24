<?php

namespace ProofreadPage\Parser;

use DOMException;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use ProofreadPage\Context;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Ext\ExtensionTagHandler;
use Wikimedia\Parsoid\Ext\ParsoidExtensionAPI;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * Parsoid implementation of the <pagelist> tag handler
 */
class ParsoidPagelistTagParser extends ExtensionTagHandler {
	use ParsoidTrait;
	use PagelistTagRendererTrait;

	private LinkRenderer $linkRenderer;
	private ParsoidExtensionAPI $extApi;
	private Context $context;
	private bool $useParsoid;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$this->useParsoid = MediaWikiServices::getInstance()->getMainConfig()->get( 'ProofreadPageUseParsoid' );
	}

	/**
	 * Render the pagelist output as a DOM fragment.
	 *
	 * @param array $output Array of page number expressions
	 * @return DocumentFragment
	 * @throws DOMException
	 */
	private function renderOutput( $output ): DocumentFragment {
		return DOMUtils::parseHTMLToFragment(
			$this->extApi->getTopLevelDoc(),
			Html::rawElement( 'div', [
				'class' => 'prp-index-pagelist',
			], implode( ' ', $output )
			) );
	}

	/**
	 * @param ParsoidExtensionAPI $extApi
	 * @param string $src
	 * @param array $extArgs
	 * @return DocumentFragment|false|null
	 * @throws DOMException
	 */
	public function sourceToDom( ParsoidExtensionAPI $extApi, string $src, array $extArgs ) {
		if ( !$this->useParsoid ) {
			return false;
		}
		$this->extApi = $extApi;
		$this->context = Context::getDefaultContext( true );
		$args = array_column( $extArgs, 'v', 'k' );
		try {
			// renderTag should return DocumentFragment for Parsoid parser
			return $this->renderTag( $this->context, $args );
		} catch ( ParserError $e ) {
			return $e->getDocumentFragment( $this->extApi, 'prp-index-pagelist', true );
		}
	}

}
