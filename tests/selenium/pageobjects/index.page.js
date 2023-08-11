'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class IndexPage extends Page {
	// returns form fields
	get formFields() {
		return browser.$$( '[name^=wpprpindex-]' );
	}

	// opening a page
	async open() {
		await super.openTitle( 'Index:Foo.djvu', { action: 'edit' } );
	}
}

module.exports = new IndexPage();
