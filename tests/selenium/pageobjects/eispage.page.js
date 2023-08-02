'use strict';
const Page = require( 'wdio-mediawiki/Page' );

class EisPagePage extends Page {
	async openEis( pagename ) {
		// eslint-disable-next-line camelcase
		await super.openTitle( pagename, { action: 'edit', prp_editinsequence: true } );
		// wait for toolbar to load
		await browser.waitUntil( async function () {
			return await browser.$$( '.prp-editinsequence-toolbar' ).length > 0;
		} );
		// turn off all unload events for wikiEditor
		await browser.execute( function () {
			// eslint-disable-next-line no-undef
			$( window ).off( 'beforeunload' );
		} );
	}

	get prevButton() {
		return browser.$( '.prp-editinsequence-prev' );
	}

	get nextButton() {
		return browser.$( '.prp-editinsequence-next' );
	}

}

module.exports = new EisPagePage();
