var OpenSeadragon = require( 'ext.proofreadpage.openseadragon' );

function OpenSeadragonController( $img, usebetatoolbar ) {
	OO.EventEmitter.call( this );
	this.Openseadragon = OpenSeadragon;
	this.viewer = null;
	if ( $img.length ) {
		this.noImageFound = false;
		this.img = $img[ 0 ];
	} else {
		this.noImageFound = true;
		this.img = null;
	}
	this.zoomFactor = Number( mw.user.options.get( 'proofreadpage-zoom-factor', 1.2 ) );
	this.animationTime = Number( mw.user.options.get( 'proofreadpage-animation-time', 0 ) );
	this.usebetatoolbar = usebetatoolbar;
	this.lastId = '';
}

OO.mixinClass( OpenSeadragonController, OO.EventEmitter );

/**
 * Construct an OpenSeadragon legacy-image-pyramid tile source from the
 * image element on the page
 *
 * @return {Object} OSD tile source constructor options
 */
OpenSeadragonController.prototype.constructImagePyramidSource = function () {
	if ( this.noImageFound ) {
		return;
	}

	var width = parseInt( this.img.getAttribute( 'width' ) );
	var height = parseInt( this.img.getAttribute( 'height' ) );
	var levels = [ {
		url: this.img.getAttribute( 'src' ),
		width: width,
		height: height
	} ];

	// Occasionally, there is no srcset set on the server
	var srcSet = this.img.getAttribute( 'srcset' );
	if ( srcSet ) {
		var parts = srcSet.split( ' ' );
		for ( var i = 0; i < parts.length - 1; i += 2 ) {
			var srcUrl = parts[ i ];
			var srcFactor = parseFloat( parts[ i + 1 ].replace( /x,?/, '' ) );

			levels.push( {
				url: srcUrl,
				width: srcFactor * width,
				height: srcFactor * height
			} );
		}
	}

	return {
		type: 'legacy-image-pyramid',
		levels: levels
	};
};

/**
 * Initialize the zoom system
 *
 * @param {string} id
 */
OpenSeadragonController.prototype.initialize = function ( id ) {

	if ( this.noImageFound ) {
		return;
	}

	var tileSource = this.constructImagePyramidSource( this.img );

	// Set the OSD strings before setting up the buttons
	var osdStringMap = [
		[ 'Tooltips.Home', 'proofreadpage-button-reset-zoom-label' ],
		[ 'Tooltips.ZoomIn', 'proofreadpage-button-zoom-in-label' ],
		[ 'Tooltips.ZoomOut', 'proofreadpage-button-zoom-out-label' ],
		[ 'Tooltips.RotateLeft', 'proofreadpage-button-rotate-left-label' ],
		[ 'Tooltips.RotateRight', 'proofreadpage-button-rotate-right-label' ]
	];

	osdStringMap.forEach( function ( mapping ) {
		// eslint-disable-next-line mediawiki/msg-doc
		OpenSeadragon.setString( mapping[ 0 ], mw.msg( mapping[ 1 ] ) );
	} );

	var osdParams = {
		id: id,
		showFullPageControl: false,
		preserveViewport: true,
		animationTime: this.animationTime,
		visibilityRatio: 0.5,
		minZoomLevel: 0.5,
		maxZoomLevel: 4.5,
		zoomPerClick: this.zoomFactor,
		zoomPerScroll: this.zoomFactor,
		tileSources: tileSource
	};

	if ( this.usebetatoolbar ) {
		$.extend( osdParams, {
			zoomInButton: 'prp-page-zoomIn',
			zoomOutButton: 'prp-page-zoomOut',
			homeButton: 'prp-page-zoomReset',
			rotateLeftButton: 'prp-page-rotateLeft',
			rotateRightButton: 'prp-page-rotateRight',
			showRotationControl: true
		} );
	} else {
		osdParams.showNavigationControl = false;
	}
	if ( this.viewer ) {
		this.viewer.destroy();
		this.viewer = null;
	}

	this.emit( 'prp-osd-before-creation', osdParams );

	this.viewer = OpenSeadragon( osdParams );
	// TODO(sohom): We want to deprecate this particular way of utilizing Openseadragon. Remove this once the
	// community has migrated to using the new API.
	mw.proofreadpage.viewer = this.viewer;

	this.viewer.viewport.goHome = function () {
		if ( this.viewer ) {
			var oldBounds = this.viewer.viewport.getBounds();
			var newBounds = new OpenSeadragon.Rect( 0, 0, 1, oldBounds.height / oldBounds.width );
			this.viewer.viewport.fitBounds( newBounds, true );
		}
	}.bind( this );

	this.viewer.addHandler( 'open', function () {
		this.emit( 'prp-osd-after-creation', this.viewer );
		// inform any listeners that the OSD viewer is ready
		mw.hook( 'ext.proofreadpage.osd-viewer-ready' ).fire( this.viewer );
	}.bind( this ) );

	this.lastId = id;
};

/**
 * Force Openseadragon to initialize. This function can be used to trigger
 * Openseadragon updates after registering callbacks that modify specific
 * parameters.
 *
 * @public
 */
OpenSeadragonController.prototype.forceInitialize = function () {
	this.initialize( this.lastId );
};

module.exports = OpenSeadragonController;
