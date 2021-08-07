/**
 * An interface class containing methods that will be used by both the WikitextDialogModel
 * and the VisualDialogModel as well as certain template methods that should be overriden.
 *
 * @param {Object} data Page data
 * @param {mw.proofreadpage.PagelistInputWidget.Model} mainModel
 * @class
 */
function DialogModel( data, mainModel ) {
	OO.EventEmitter.call( this );

	this.data = data || {};
	this.api = new mw.Api();
	this.mainModel = mainModel;
	this.obj = {};
	this.imageUrl = null;
	this.imageZoomUrlOnePointFive = null;
	this.imageZoomUrlTwo = null;
	this.imageHeight = null;
	this.imageWidth = null;
}

OO.mixinClass( DialogModel, OO.EventEmitter );

/**
 * Sets data for model
 *
 * @param {Object} data
 * @event aftersetpagedata
 * @event aftersetimageurl
 */
DialogModel.prototype.setData = function ( data ) {
	this.data = data;
	if ( this.obj[ data.subPage ] ) {
		this.imageUrl = this.obj[ data.subPage ].imageUrl;
		this.imageZoomUrlOnePointFive = this.obj[ data.subPage ].imageZoomUrlOnePointFive;
		this.imageZoomUrlTwo = this.obj[ data.subPage ].imageZoomUrlTwo;
		this.imageHeight = this.obj[ data.subPage ].imageHeight;
		this.imageWidth = this.obj[ data.subPage ].imageWidth;
		this.emit( 'aftersetimageurl', this.imageUrl, this.imageZoomUrlOnePointFive, this.imageZoomUrlTwo, this.imageWidth, this.imageHeight );
	} else {
		this.generateImageLink( data );
	}

	this.emit( 'aftersetpagedata', this.data );
};

/**
 * Generates image link for corresponding index page
 *
 * @param  {Object} data Data for current subpage
 */
DialogModel.prototype.generateImageLink = function ( data ) {
	var pageTitle = mw.config.get( 'wgFormattedNamespaces' )[ 6 ] + ':' + mw.config.get( 'wgTitle' ),
		subpage = data.subPage || 1,
		imageSize = 1024; // arbitrary number, same as used at PageDisplayHandler.php
	this.api.get( {
		action: 'query',
		prop: 'imageinfo',
		indexpageids: true,
		titles: pageTitle,
		iiprop: 'url',
		iiurlparam: 'page' + subpage + '-' + imageSize + 'px'
	} ).done( function ( response ) {
		var imageUrl = null,
			imageZoomUrlOnePointFive = null,
			imageZoomUrlTwo = null,
			imageWidth = null,
			imageHeight = null;

		try {
			imageUrl = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumburl;
			imageZoomUrlOnePointFive = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].responsiveUrls[ 1.5 ];
			imageZoomUrlTwo = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].responsiveUrls[ 2 ];
			imageWidth = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumbwidth;
			imageHeight = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumbheight;
		} catch ( e ) {
			this.emit( 'imageurlnotfound' );
			mw.log.error( e, response );
			return;
		}
		// incase thumbUrl doesn't exist
		if ( !imageUrl ) {
			this.emit( 'imageurlnotfound' );
			return;
		}
		this.obj[ subpage ] = {
			imageUrl: response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumburl,
			imageZoomUrlOnePointFive: response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].responsiveUrls[ 1.5 ],
			imageZoomUrlTwo: response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].responsiveUrls[ 2 ],
			imageWidth: response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumbwidth,
			imageHeight: response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumbheight
		};
		this.emit( 'aftersetimageurl', imageUrl, imageZoomUrlOnePointFive, imageZoomUrlTwo, imageWidth, imageHeight );
	}.bind( this ) ).catch( function ( e ) {
		this.emit( 'imageurlnotfound' );
		mw.log.error( e );
	} );
};

/**
 * Placeholder method to be overriden
 */
DialogModel.prototype.updateCachedDataFromMainModel = function () {
};

/**
 * Placeholder method to be overriden
 */
DialogModel.prototype.updateCachedData = function () {
};

/**
 * Placeholder method to be overriden
 */
DialogModel.prototype.setCachedData = function () {
};

/**
 * Placeholder method to be overriden
 */
DialogModel.prototype.unloadCachedData = function () {
};

/**
 * Placeholder method to be overriden
 */
DialogModel.prototype.setCachedDataChanged = function () {
};

module.exports = DialogModel;
