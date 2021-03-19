<?php

namespace ProofreadPage\Index;

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
	 * Sets up an index TemplateStyles provider
	 * @param \Title $indexTitle the title of the index
	 */
	public function __construct( \Title $indexTitle ) {
		$this->indexTitle = $indexTitle;
	}

	/**
	 * Return TemplateStyles page for the current index
	 * This may or may not exist.
	 * @return \Title
	 */
	public function getTemplateStylesPage() {
		return $this->indexTitle->getSubpage( "styles.css" );
	}

	/**
	 * Return TemplateStyles for the given index
	 * @param string|null $wrapper optional CSS selector to limit the style scope
	 * @return string
	 */
	public function getIndexTemplateStyles( ?string $wrapper ) {
		$cssTitle = $this->getTemplateStylesPage();
		$styles = '';

		if ( $cssTitle->exists()
				&& \ExtensionRegistry::getInstance()->isLoaded( 'TemplateStyles' ) ) {
			// if this is a normal redirect, follow it, because TS will not
			// do that for the the final page
			$cssTitle = \WikiPage::factory( $cssTitle )->getRedirectTarget() ?: $cssTitle;

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
