/**
 * Backbone of the toolbar, contains data pertaining to the
 * pages that can be visited/navigated to using edit-in-sequence
 * during the current session
 *
 * @class
 * @param {string} currentPage Initial Page: page
 * @param {string} indexPage Index: page associated with initial Page: page
 */
function PagelistModel( currentPage, indexPage ) {
	OO.EventEmitter.call( this );
	this.currentPage = currentPage.replace( /_/g, ' ' );
	this.response = [];
	this.currentIndex = 0;
	this.indexPage = indexPage;
	this.pagelist = [];
	this.fetchPagelistData();
}

OO.mixinClass( PagelistModel, OO.EventEmitter );

/**
 * Fetch pagelist data from the 'proofreadpagesinindex' API
 *
 * @private
 * @param {Object|null} contd continue parameter to be appended to request
 */
PagelistModel.prototype.fetchPagelistData = function ( contd ) {
	var api = new mw.Api();

	api.post( {
		action: 'query',
		list: 'proofreadpagesinindex',
		prppiititle: this.indexPage,
		prppiiprop: 'ids|title',
		continue: contd && contd.continue || undefined,
		prppiicontinue: contd && contd.prppiicontinue || undefined
	} ).done( function ( response ) {
		if ( response.error ) {
			this.handleError( response.error.info );
			return;
		}

		if ( response.continue ) {
			this.response = this.response.concat( response.query.proofreadpagesinindex );
			this.fetchPagelistData( response.continue );
			return;
		}

		this.setPageListData( this.response.concat( response.query.proofreadpagesinindex ) );
		this.response = [];
	}.bind( this ) ).catch( function ( err ) {
		if ( err === 'http' ) {
			this.handleError( mw.msg( 'prp-editinsequence-http-error-pagelist' ) );
		} else if ( err === 'invalidtitle' ) {
			this.handleError( mw.msg( 'prp-editinsequence-pagination-does-not-exist' ) );
		} else {
			this.handleError( mw.msg( 'prp-editinsequence-unknown-error' ) );
		}
		mw.log.error( err );
	}.bind( this ) );
};

/**
 * Handles errors
 *
 * @private
 * @param {string} err Error string
 */
PagelistModel.prototype.handleError = function ( err ) {
	this.emit( 'error', err );
};

/**
 * Initializes PagelistModel with data from fetchPagelistData
 *
 * @private
 * @param {Array<Object>} response Response data from fetchPagelistData
 * @fires pageListReady Event denoting that the PagelistModel has been initialized
 * @fires pageUpdated Event denoting that a new page has been set
 * @fires lastReached Event denoting that the current page is the last page in the Pagelist sequence
 * @fires firstReached Event denoting that the current page is the first page in the Pagelist sequence
 */
PagelistModel.prototype.setPageListData = function ( response ) {
	var tempPage = null;
	for ( var i = 0; i < response.length; i++ ) {
		tempPage = response[ i ];
		this.pagelist.push( {
			pageNumber: tempPage.pageoffset,
			exists: tempPage.pageid !== 0,
			pageid: tempPage.pageid,
			title: tempPage.title
		} );

		if ( this.currentPage === tempPage.title ) {
			this.currentIndex = i;
		}
	}

	this.emit( 'pageListModelReady' );
	this.emit( 'pageUpdated', this.currentPage );

	// might encounter a list of length 1, so check two conditions seperately
	if ( this.currentIndex === this.pagelist.length - 1 ) {
		this.emit( 'lastReached' );
	}

	if ( this.currentIndex === 0 ) {
		this.emit( 'firstReached' );
	}
};

/**
 * Go to next page
 *
 * @public
 * @fires pageUpdated Event denoting a new page has been set
 * @fires lastReached Event denoting last page in Pagelist sequence has been reached
 */
PagelistModel.prototype.next = function () {
	this.currentIndex++;
	this.currentPage = this.pagelist[ this.currentIndex ].title;
	this.emit( 'pageUpdated', this.currentPage );
	if ( this.currentIndex === this.pagelist.length ) {
		this.emit( 'lastReached' );
	}
};

/**
 * Returns stored details of next page, returns invalid output if next page is out of bounds
 *
 * @public
 * @return {Object<string, number, boolean>} Stored details of next page
 */
PagelistModel.prototype.getNext = function () {
	if ( this.currentIndex === this.pagelist.length ) {
		return {
			title: '',
			pageNumber: -1,
			pageid: 0,
			exists: false
		};
	}
	return this.pagelist[ this.currentIndex + 1 ];
};

/**
 * Go to previous page
 *
 * @public
 * @fires pageUpdated Event denoting
 * @fires firstReached
 */
PagelistModel.prototype.prev = function () {
	this.currentIndex--;
	this.currentPage = this.pagelist[ this.currentIndex ].title;
	this.emit( 'pageUpdated', this.currentPage );
	if ( this.currentIndex === 0 ) {
		this.emit( 'firstReached' );
	}
};

/**
 * Returns stored details of previous page, returns invalid output if previous page is out of bounds
 *
 * @public
 * @return {Object<string, number, boolean>} Stored details of previous page
 */
PagelistModel.prototype.getPrev = function () {
	if ( this.currentIndex === 0 ) {
		return {
			title: '',
			pageNumber: -1,
			formattedPageNumber: '',
			exists: false
		};
	}
	return this.pagelist[ this.currentIndex - 1 ];
};

/**
 * Get stored data of current page
 *
 * @public
 * @return {Object<string, number, boolean>} Stored data of current page
 */
PagelistModel.prototype.getCurrent = function () {
	return this.pagelist[ this.currentIndex ];
};

module.exports = PagelistModel;
