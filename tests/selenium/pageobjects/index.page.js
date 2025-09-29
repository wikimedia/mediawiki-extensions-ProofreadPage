import Page from 'wdio-mediawiki/Page.js';

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

export default new IndexPage();
