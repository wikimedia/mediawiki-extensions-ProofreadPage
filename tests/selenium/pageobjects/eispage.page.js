'use strict';
const Page = require( 'wdio-mediawiki/Page' );
const utils = require( '../util/eis-util' );

class EisPagePage extends Page {
	async openEis( pagename ) {
		// eslint-disable-next-line camelcase
		await super.openTitle( pagename, { action: 'edit', prp_editinsequence: true } );
		// wait for toolbar to load
		await browser.waitUntil( async () => await browser.$$( '.prp-edit-in-sequence-toolbar' ).length > 0 );
		// turn off all unload events for wikiEditor
		await browser.execute( () => {
			// eslint-disable-next-line no-undef
			$( window ).off( 'beforeunload' );
		} );
	}

	get prevButton() {
		return browser.$( '.prp-editinsequence-prev' );
	}

	get pageStatusButtonLabel() {
		return browser.$( '.prp-editinsequence-page-status > span > span.oo-ui-labelElement-label' );
	}

	get pageStatusButton() {
		return browser.$( '.prp-editinsequence-page-status' );
	}

	get nextButton() {
		return browser.$( '.prp-editinsequence-next' );
	}

	async waitForOOUIElementToBeActive( $element ) {
		await browser.waitUntil( async () => await utils.isEnabledInOOUI( $element ) );
	}

	async waitForPageStatusButtonToBeResponsive() {
		const page = this;
		await browser.waitUntil( async () => await page.pageStatusButtonLabel.getText() !== '' && ( await page.pageStatusButtonLabel.getText() ).replace( /\s/g, '' ).length, { timeout: 30 * 1000 } );
	}

	async selectPageStatusFromDropdown( valueName ) {
		await this.pageStatusButton.click();
		await browser.$( '.oo-ui-tool-name-' + valueName ).waitForDisplayed();
		await browser.$( '.oo-ui-tool-name-' + valueName ).click();
	}
}

module.exports = new EisPagePage();
