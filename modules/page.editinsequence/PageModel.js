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
	this.body = '';
	this.footer = '';
	this.pageStatus = {
		status: 0,
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
	var api = new mw.Api();
	this.pageName = page;

	api.post( {
		action: 'query',
		prop: 'revisions',
		indexpageids: true,
		titles: page,
		rvprop: 'content',
		rvslots: '*'
	} ).then( this.setPageData.bind( this ) ).catch( function ( err ) {
		if ( err === 'http' ) {
			this.handleError( mw.msg( 'prp-editinsequence-http-error-page' ) );
		} else {
			this.handleError( mw.msg( 'prp-editinsequence-unknown-error' ) );
		}
		mw.log.error( err );
	} );
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
PageModel.prototype.setPageData = function ( data ) {
	var pageid = data.query.pageids[ 0 ];

	if ( parseInt( pageid ) === -1 ) {
		this.setDefault();
		return;
	}

	var wikitext = data.query.pages[ pageid ].revisions[ 0 ].slots.main[ '*' ];

	var pageData = this.parsePageData( wikitext );

	this.header = pageData.header;
	this.body = pageData.body;
	this.footer = pageData.footer;
	this.pageStatus = pageData.pageStatus;
	this.exists = true;
	this.emit( 'pageModelUpdated' );
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
 * @private
 * @param {string} wikitext Wikitext of current Page: page
 * @return {Object<string>} Header, body, footer and page status as identified from the wikitext
 */
PageModel.prototype.parsePageData = function ( wikitext ) {
	var parsedPagePage = ( new DOMParser() ).parseFromString( wikitext, 'text/html' ),
		noincludes = parsedPagePage.querySelectorAll( 'body > noinclude' ), pageStatus = {};
	var header = noincludes[ 0 ].querySelector( 'pagequality' ).innerHTML;
	pageStatus.status = parseInt( noincludes[ 0 ].querySelector( 'pagequality' ).getAttribute( 'level' ) );
	pageStatus.lastUser = noincludes[ 0 ].querySelector( 'pagequality' ).getAttribute( 'user' );
	noincludes[ 0 ].remove();
	var footer = noincludes[ noincludes.length - 1 ].innerHTML;
	noincludes[ noincludes.length - 1 ].remove();
	var body = parsedPagePage.querySelector( 'body' ).innerHTML;
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
 * Handle page not existing and load backup data
 *
 * @private
 * @fires pageModelUpdated Event denoting that the PageModel has been updated with data for current page
 */
PageModel.prototype.setDefault = function () {
	if ( this.fallbackType === 'ocr' ) {
		// TODO(sohom): fetch ocr of page image
		return;
	} else if ( this.fallbackType === 'text-layer' ) {
		// TODO(sohom): fetch text layer
		return;
	}
	this.exists = false;
	this.body = '';
	this.footer = '';
	this.header = '';
	this.pageStatus = 0;
	this.pageStatusLastUser = mw.config.get( 'wgUserName' );
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
 * Get header, footer and body for current page
 *
 * @public
 * @return {Object<string>}
 */
PageModel.prototype.getEditorData = function () {
	return {
		header: this.header,
		body: this.body,
		footer: this.footer
	};
};

module.exports = PageModel;
