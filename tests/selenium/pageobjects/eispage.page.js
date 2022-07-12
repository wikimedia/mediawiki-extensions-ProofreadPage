'use strict';
const Page = require( 'wdio-mediawiki/Page' );

class EisPagePage extends Page {
	openEis( pagename ) {
		// eslint-disable-next-line camelcase
		super.openTitle( pagename, { action: 'edit', prp_editinsequence: true } );
		// wait for toolbar to load
		browser.waitUntil( function () {
			return browser.$$( '.prp-editinsequence-toolbar' ).length > 0;
		} );
		// turn off all unload events for wikiEditor
		browser.execute( function () {
			// eslint-disable-next-line no-undef
			$( window ).off( 'beforeunload' );
		} );
	}

	get prevButton() {
		return browser.$$( '.prp-editinsequence-prev' )[ 0 ];
	}

	get nextButton() {
		return browser.$$( '.prp-editinsequence-next' )[ 0 ];
	}
}

module.exports = new EisPagePage();
