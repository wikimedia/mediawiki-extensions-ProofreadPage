'use strict';
const Page = require( 'wdio-mediawiki/Page' ),
	utils = require( '../util/eis-util' );

class EisPagePage extends Page {
	openEis( pagename ) {
		// eslint-disable-next-line camelcase
		super.openTitle( pagename, { action: 'edit', prp_editinsequence: true } );
		// wait for toolbar to load
		browser.waitUntil( function () {
			return browser.$$( '.prp-edit-in-sequence-toolbar' ).length > 0;
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

	get pageStatusButtonLabel() {
		return browser.$$( '.prp-editinsequence-page-status > span > span.oo-ui-labelElement-label' )[ 0 ];
	}

	get pageStatusButton() {
		return browser.$$( '.prp-editinsequence-page-status' )[ 0 ];
	}

	get nextButton() {
		return browser.$$( '.prp-editinsequence-next' )[ 0 ];
	}

	waitForOOUIElementToBeActive( $element ) {
		browser.waitUntil( function () {
			return utils.isEnabledInOOUI( $element );
		} );
	}

	waitForPageStatusButtonToBeResponsive() {
		browser.waitUntil( function () {
			return this.pageStatusButtonLabel.getText() !== '' && this.pageStatusButtonLabel.getText().replace( /\s/g, '' ).length;
		}.bind( this ), { timeout: 30 * 1000 } );
	}

	selectPageStatusFromDropdown( valueName ) {
		this.pageStatusButton.click();
		browser.$$( '.oo-ui-tool-name-' + valueName )[ 0 ].waitForDisplayed();
		browser.$$( '.oo-ui-tool-name-' + valueName )[ 0 ].click();
	}
}

module.exports = new EisPagePage();
