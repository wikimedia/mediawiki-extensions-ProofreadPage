var PagelistModel = require( './PagelistModel.js' );

/**
 * Model to store data about currently editing page
 *
 * @class
 * @param {PagelistModel} pagelistModel PagelistModel linked to current toolbar
 * @param {'ocr'|'text-layer'|''} [fallbackType] Specify a fallback method to load data if page does not exist
 */
function PageModel( pagelistModel, fallbackType ) {
	OO.EventEmitter.call( this );
	this.pagelistModel = pagelistModel;
	this.fallbackType = fallbackType || '';
	this.exists = false;
	this.pageName = null;
	this.header = '';
	this.initialHeader = '';
	this.body = '';
	this.initialBody = '';
	this.footer = '';
	this.initialFooter = '';
	this.pageStatus = {
		status: 1,
		lastUser: mw.config.get( 'wgUserName' )
	};
	this.initialPageStatus = {
		status: 1,
		lastUser: mw.config.get( 'wgUserName' )
	};
	this.pagelistModel.on( 'pageUpdated', this.fetchData.bind( this ) );
}

OO.mixinClass( PageModel, OO.EventEmitter );

/**
 * Fetch data related to the current page
 *
 * @private
 * @param {string} page Name of page
 */
PageModel.prototype.fetchData = function ( page ) {
	this.savePage();
	this.emit( 'beforePageModelUpdate' );
	var api = new mw.Api();
	this.pageName = page;

	api.post( {
		action: 'query',
		prop: 'revisions',
		indexpageids: true,
		titles: page,
		rvprop: 'content',
		rvslots: 'main'
	} ).then( function ( result ) {
		this.setInitialPageData( result );
		this.setPageData();
		this.loadSavedPage();
	}.bind( this ) ).catch( function ( err ) {
		if ( err === 'http' ) {
			this.handleError( mw.msg( 'prp-editinsequence-http-error-page' ) );
		} else {
			this.handleError( mw.msg( 'prp-editinsequence-unknown-error' ) );
		}
		mw.log.error( err );
	} );
};

/**
 * Stores the unsaved edits to a page when a page is navigated away from
 */
PageModel.prototype.savePage = function () {
	if ( !this.hasChanges() ) {
		return;
	}
	mw.storage.setObject( 'prp-editinsequence-page-save-' + this.pageName, {
		header: this.header,
		body: this.body,
		footer: this.footer,
		pageStatus: this.pageStatus
	} );
};

/**
 * Checks if the unsaved edits to a page need to be saved
 *
 * @param {string} header
 * @param {string} body
 * @param {string} footer
 * @param {Object} pageStatus
 * @return {boolean} True if there are changes, false otherwise
 */
PageModel.prototype.hasChanges = function () {
	return this.header !== this.initialHeader ||
	this.body !== this.initialBody ||
	this.footer !== this.initialFooter ||
	this.pageStatus.status !== this.initialPageStatus.status ||
	this.pageStatus.lastUser !== this.initialPageStatus.lastUser;
};

/**
 * Loads unsaved edits to a page if present
 */
PageModel.prototype.loadSavedPage = function () {
	var savedPage = mw.storage.getObject( 'prp-editinsequence-page-save-' + this.pageName );
	if ( !savedPage ) {
		return;
	}
	this.header = savedPage.header;
	this.body = savedPage.body;
	this.footer = savedPage.footer;
	this.pageStatus = savedPage.pageStatus;
	this.emit( 'loadUnsavedEdit', {
		header: this.header,
		body: this.body,
		footer: this.footer,
		pageStatus: this.pageStatus
	} );
	// delete saved page
	mw.storage.remove( 'prp-editinsequence-page-save-' + this.pageName );
	return;
};

/**
 * Handles errors
 *
 * @private
 * @param {string} err Error string
 */
PageModel.prototype.handleError = function ( err ) {
	this.emit( 'error', err );
};

/**
 * Initialize model with data of current page
 *
 * @private
 * @param {Object} data Data recieved from fetchData
 * @fires pageModelUpdated Event denoting that the PageModel has been updated with data for current page
 */
PageModel.prototype.setInitialPageData = function ( data ) {
	var pageid = data.query.pageids[ 0 ];

	if ( parseInt( pageid ) === -1 ) {
		this.setInitialFallbackPageData();
		return;
	}

	var wikitext = data.query.pages[ pageid ].revisions[ 0 ].slots.main[ '*' ];

	var pageData = this.parsePageData( wikitext );

	this.initialHeader = pageData.header;
	this.initialBody = pageData.body;
	this.initialFooter = pageData.footer;
	this.initialPageStatus = pageData.pageStatus;
	this.exists = true;
};

/**
 * Parses the various sections and pagestatus of a Page: page given the wikitext.
 * The algorithm relies on the DOMParser() WebAPI which is used to parse and identify
 * the <noincludes> and <pagequality/> tags. Only the first and last immediately
 * descendant <noinclude> tags (descandents of root) are considered and the rest
 * is considered to be part of the body, header and footer depending on where they are
 * placed.
 *
 * Please update the tests tests/qunit/PageModel.test.js if the logic
 * here is changed to include more edgecases.
 *
 * TODO(sohom): This logic should roughly mirror
 * PageContentHandler::unserializeContentInWikitext(...)
 * until T321446 is fixed.
 *
 * @private
 * @param {string} wikitext Wikitext of current Page: page
 * @return {Object<string>} Header, body, footer and page status as identified from the wikitext
 */
PageModel.prototype.parsePageData = function ( wikitext ) {
	var pageRegex = /^<noinclude>([\s\S]*?)<\/noinclude>([\s\S]*)<noinclude>([\s\S]*?)<\/noinclude>$/;
	var pageQualityRegexPattern1 =
	/^<pagequality level="([0-4])" user="([\s\S]*?)" *(?:\/>|> *<\/pagequality>)([\s\S]*?)$/;
	var pageQualityRegexPattern2 =
	/^<pagequality user="([\s\S]*?)" level="([0-4])" *(?:\/>|> *<\/pagequality>)([\s\S]*?)$/;
	var parsedWikitext = pageRegex.exec( wikitext );
	if ( !parsedWikitext || parsedWikitext.length !== 4 ) {
		// something has gone terribly wrong, so just return the raw text
		// as the body
		return {
			header: '',
			body: wikitext,
			footer: '',
			pageStatus: {
				status: 1,
				lastUser: mw.config.get( 'wgUserName' )
			}
		};
	}

	var header = parsedWikitext[ 1 ];
	var body = parsedWikitext[ 2 ];
	var footer = parsedWikitext[ 3 ];

	var parsedPageQuality = pageQualityRegexPattern1.exec( header );
	var pattern = 1;

	if ( !parsedPageQuality || parsedPageQuality.length !== 4 ) {
		parsedPageQuality = pageQualityRegexPattern2.exec( header );
		pattern = 2;
		if ( !parsedPageQuality || parsedPageQuality.length !== 4 ) {
			// something has gone terribly wrong again,
			// but atleast we have headers, footers and body
			return {
				header: header,
				body: body,
				footer: footer,
				pageStatus: {
					status: 1,
					lastUser: mw.config.get( 'wgUserName' )
				}
			};
		}
	}

	var pageStatus = {};
	if ( pattern === 1 ) {
		pageStatus.status = parseInt( parsedPageQuality[ 1 ] );
		pageStatus.lastUser = parsedPageQuality[ 2 ];
	} else {
		pageStatus.status = parseInt( parsedPageQuality[ 2 ] );
		pageStatus.lastUser = parsedPageQuality[ 1 ];
	}

	header = parsedPageQuality[ 3 ];

	return {
		header: header,
		body: body,
		footer: footer,
		pageStatus: pageStatus
	};
};

/**
 * Set page status for the page
 *
 * @private
 * @param {number} status Status that is being set
 */
PageModel.prototype.setPageStatus = function ( status ) {
	this.pageStatus.status = status;
	this.pageStatus.lastUser = mw.config.get( 'wgUserName' );
	this.emit( 'pageStatusChanged' );
};

/**
 * @param {string} footer
 */
PageModel.prototype.setFooter = function ( footer ) {
	this.footer = footer;
};

/**
 * @param {string} header
 */
PageModel.prototype.setHeader = function ( header ) {
	this.header = header;
};

/**
 * @param {string} body
 */
PageModel.prototype.setBody = function ( body ) {
	this.body = body;
};

/**
 * Handle page not existing and load backup data
 *
 * @private
 * @fires pageModelUpdated Event denoting that the PageModel has been updated with data for current page
 */
PageModel.prototype.setInitialFallbackPageData = function () {
	if ( this.fallbackType === 'ocr' ) {
		// TODO(sohom): fetch ocr of page image
		return;
	} else if ( this.fallbackType === 'text-layer' ) {
		// TODO(sohom): fetch text layer
		return;
	}
	this.exists = false;
	this.initialBody = '';
	this.initialFooter = '';
	this.initialHeader = '';
	this.initialPageStatus = {
		status: 1,
		lastUser: mw.config.get( 'wgUserName' )
	};
};

/**
 * Sets the page data based on the initial data
 */
PageModel.prototype.setPageData = function () {
	this.body = this.initialBody;
	this.header = this.initialHeader;
	this.footer = this.initialFooter;
	this.pageStatus = this.initialPageStatus;
	this.emit( 'pageModelUpdated' );
};

/**
 * Get Page status
 *
 * @public
 * @return {Object} Current PageStatus
 */
PageModel.prototype.getPageStatus = function () {
	return this.pageStatus;
};

/**
 * Get Page name
 *
 * @public
 * @return {string}
 */
PageModel.prototype.getPageName = function () {
	return this.pageName;
};

/**
 * Get if the current page exists
 *
 * @public
 * @return {boolean}
 */
PageModel.prototype.getExists = function () {
	return this.exists;
};

/**
 * Builds wikitext from supplied header, body, footer, pageStatus values
 *
 * @param {string} header
 * @param {string} body
 * @param {string} footer
 * @param {Object} pageStatus
 * @param {number} pageStatus.status
 * @param {string} pageStatus.lastUser
 * @public
 * @return {string} wikiText
 */
PageModel.prototype.getWikitext = function ( header, body, footer, pageStatus ) {
	var wikitext = '<noinclude><pagequality level="$level" user="$user" />$header</noinclude>$body<noinclude>$footer</noinclude>';
	wikitext = wikitext.replace( '$header', header );
	wikitext = wikitext.replace( '$body', body );
	wikitext = wikitext.replace( '$footer', footer );
	wikitext = wikitext.replace( '$level', pageStatus.status );
	wikitext = wikitext.replace( '$user', pageStatus.lastUser );
	return wikitext;
};

PageModel.prototype.setInitialPageDataToCurrent = function () {
	this.initialBody = this.body;
	this.initialHeader = this.header;
	this.initialFooter = this.footer;
	this.initialPageStatus = this.pageStatus;
};

/**
 * Get a string representing wikitext of the page when editor started editing
 *
 * @return {string} Serialized wikitext
 */
PageModel.prototype.getInitialWikitext = function () {
	return this.getWikitext( this.initialHeader, this.initialBody, this.initialFooter, this.initialPageStatus );
};

/**
 * Get a string representing wikitext of the page based on the current edits
 *
 * @return {string} Serialized wikitext
 */
PageModel.prototype.getCurrentWikitext = function () {
	return this.getWikitext( this.header, this.body, this.footer, this.pageStatus );
};

/**
 *Get header, footer and body for current page after edits
 *
 * @public
 * @return {Object<string>} Header, body, footer for current page
 */
PageModel.prototype.getEditorData = function () {
	return {
		header: this.header,
		body: this.body,
		footer: this.footer
	};
};

/**
 * Get header, footer and body for current page when initially loaded
 *
 * @public
 * @return {Object<string>}
 */
PageModel.prototype.getInitialEditorData = function () {
	return {
		header: this.initialHeader,
		body: this.initialBody,
		footer: this.initialFooter
	};
};

module.exports = PageModel;
