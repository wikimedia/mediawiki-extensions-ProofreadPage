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
	this.canonicalImageLink = null;
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
	if ( !this.canonicalImageLink ) {
		this.generateImageLink( data );
	} else {
		this.emit(
			'aftersetimageurl',
			this.canonicalImageLink.replace( '$1', String( data.subPage ) )
		);
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
			canonicalImageLink = null,
			canonicalRegex = new RegExp( 'page' + subpage + '*-' + imageSize + 'px' ),
			canonicalText = 'page$1-' + imageSize + 'px';
		try {
			imageUrl = response.query.pages[ response.query.pageids[ 0 ] ].imageinfo[ 0 ].thumburl;
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
		canonicalImageLink = imageUrl.replace( canonicalRegex, canonicalText );
		this.canonicalImageLink = canonicalImageLink;
		this.emit( 'aftersetimageurl', imageUrl );
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
