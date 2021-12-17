<?php

namespace ProofreadPage\Index;

use MediaWiki\MediaWikiServices;

/**
 * @license GPL-2.0-or-later
 *
 * Returns the TemplateStyles sheets associated with an Index
 */
class IndexTemplateStyles {

	/**
	 * @var \Title
	 */
	private $indexTitle;

	/**
	 * @var bool
	 */
	private $haveStyleSupport;

	/**
	 * Sets up an index TemplateStyles provider
	 * @param \Title $indexTitle the title of the index (or the subpage of an
	 * index)
	 */
	public function __construct( \Title $indexTitle ) {
		// Use the base page so all subpages of an index share the same stylesheet
		$this->indexTitle = $indexTitle->getBaseTitle();
		$this->haveStyleSupport = \ExtensionRegistry::getInstance()->isLoaded( 'TemplateStyles' );
	}

	/**
	 * @return bool whether the given index has styles support
	 */
	public function hasStylesSupport() {
		return $this->haveStyleSupport;
	}

	/**
	 * Return TemplateStyles page for the current index
	 * This may or may not exist.
	 *
	 * @return \Title|null the title of the styles page, or null if no styles support
	 */
	public function getTemplateStylesPage() {
		$stylesPage = null;

		if ( $this->haveStyleSupport ) {
			$stylesPage = $this->indexTitle->getSubpage( "styles.css" );
		}

		return $stylesPage;
	}

	/**
	 * Returns the canonical index page associated with this page. This is the
	 * root Index page.
	 *
	 * @return \Title the main index page
	 */
	public function getAssociatedIndexPage() {
		return $this->indexTitle;
	}

	/**
	 * Return TemplateStyles for the given index
	 * @param string|null $wrapper optional CSS selector to limit the style scope
	 * @return string
	 */
	public function getIndexTemplateStyles( ?string $wrapper ) {
		$cssTitle = $this->getTemplateStylesPage();
		$styles = '';

		if ( $cssTitle && $cssTitle->exists() ) {
			// if this is a normal redirect, follow it, because TS will not
			// do that for the final page
			$cssTitle = MediaWikiServices::getInstance()->getWikiPageFactory()
				->newFromTitle( $cssTitle )->getRedirectTarget() ?: $cssTitle;

			$ts_attribs = [
				"src" => $cssTitle->getFullText()
			];

			if ( $wrapper ) {
				$ts_attribs["wrapper"] = $wrapper;
			}

			$styles .= \Xml::element( "templatestyles", $ts_attribs, "" );
		}
		return $styles;
	}
}
