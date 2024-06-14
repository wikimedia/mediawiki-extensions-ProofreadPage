const OpenSeadragon = require( 'ext.proofreadpage.openseadragon' );

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

	const width = parseInt( this.img.getAttribute( 'width' ) );
	const height = parseInt( this.img.getAttribute( 'height' ) );
	const levels = [ {
		url: this.img.getAttribute( 'src' ),
		width: width,
		height: height
	} ];

	// Occasionally, there is no srcset set on the server
	const srcSet = this.img.getAttribute( 'srcset' );
	if ( srcSet ) {
		const parts = srcSet.split( ' ' );
		for ( let i = 0; i < parts.length - 1; i += 2 ) {
			const srcUrl = parts[ i ];
			const srcFactor = parseFloat( parts[ i + 1 ].replace( /x,?/, '' ) );

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

	const tileSource = this.constructImagePyramidSource( this.img );

	// Set the OSD strings before setting up the buttons
	const osdStringMap = [
		[ 'Tooltips.Home', 'proofreadpage-button-reset-zoom-label' ],
		[ 'Tooltips.ZoomIn', 'proofreadpage-button-zoom-in-label' ],
		[ 'Tooltips.ZoomOut', 'proofreadpage-button-zoom-out-label' ],
		[ 'Tooltips.RotateLeft', 'proofreadpage-button-rotate-left-label' ],
		[ 'Tooltips.RotateRight', 'proofreadpage-button-rotate-right-label' ]
	];

	osdStringMap.forEach( ( mapping ) => {
		// eslint-disable-next-line mediawiki/msg-doc
		OpenSeadragon.setString( mapping[ 0 ], mw.msg( mapping[ 1 ] ) );
	} );

	const osdParams = {
		id: id,
		showFullPageControl: false,
		preserveViewport: true,
		animationTime: this.animationTime,
		visibilityRatio: 0.5,
		minZoomLevel: 0.5,
		maxZoomLevel: 4.5,
		zoomPerClick: this.zoomFactor,
		zoomPerScroll: this.zoomFactor,
		timeout: 2 * 60 * 1000, // 2 minutes
		tileSources: tileSource
	};

	if ( this.usebetatoolbar ) {
		Object.assign( osdParams, {
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
	// https://phabricator.wikimedia.org/T348078#9720139
	mw.log.deprecate( mw.proofreadpage, 'viewer', mw.proofreadpage.viewer,
		'Please use new API. [Since November 2022]' );

	this.viewer.viewport.goHome = function () {
		if ( this.viewer ) {
			const oldBounds = this.viewer.viewport.getBounds();
			const newBounds = new OpenSeadragon.Rect( 0, 0, 1, oldBounds.height / oldBounds.width );
			this.viewer.viewport.fitBounds( newBounds, true );
		}
	}.bind( this );

	this.viewer.addHandler( 'open', () => {
		this.initializeViewportFromSavedData( id );
		this.emit( 'prp-osd-after-creation', this.viewer );
		// inform any listeners that the OSD viewer is ready
		mw.hook( 'ext.proofreadpage.osd-viewer-ready' ).fire( this.viewer );
	} );

	this.viewer.addHandler( 'viewport-change', () => {
		// the viewer may have been already destroyed on H/V swap
		if ( !this.viewer ) {
			return;
		}

		const center = this.viewer.viewport.getCenter();
		const newViewport = {
			rotation: this.viewer.viewport.getRotation(),
			zoom: this.viewer.viewport.getZoom(),
			x: center.x,
			y: center.y
		};

		mw.storage.setObject( this.getStorageKey( id ), newViewport, 31536000 );
	} );

	this.lastId = id;
};

/**
 * Initializes viewport from previously saved data
 *
 * @private
 * @param {string} id Current image orientation
 */
OpenSeadragonController.prototype.initializeViewportFromSavedData = function ( id ) {
	const viewportData = mw.storage.getObject( this.getStorageKey( id ) );
	if ( viewportData === null ) {
		return;
	}

	this.viewer.viewport.setRotation( viewportData.rotation );
	this.viewer.viewport.zoomTo( viewportData.zoom );
	this.viewer.viewport.panTo(
		new OpenSeadragon.Point( viewportData.x, viewportData.y )
	);
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
/**
 * Returns a URL to the current image source
 *
 * @return {string} url to the image
 * @public
 */
OpenSeadragonController.prototype.getCurrentImage = function () {
	let url = '';
	try {
		url = this.viewer.source.getTileUrl( this.viewer.source.getClosestLevel(), 0, 0 );
	} catch ( e ) {
		url = this.img;
	}

	// Normalize the URL, here we create anchor tag and set the href. This
	// should use the browser's inbuilt URL resolver to create a canonical URL
	// and should take care of most sneaky edge cases
	const anchorTag = document.createElement( 'a' );
	anchorTag.href = url;

	return anchorTag.href;
};

/**
 * Get Storage key for particular Page: page
 * We are going to use the following format
 * mw-prp-page-edit-<name_of_associated_index_page>-<id>
 * where id denotes vertical/horizontal
 *
 * @private
 * @param {string} id
 * @return {string} Storage key for given Page: page
 */
OpenSeadragonController.prototype.getStorageKey = function ( id ) {
	return 'mw-prp-page-edit-' + encodeURIComponent( mw.config.get( 'prpIndexTitle' ) ) + id;
};

module.exports = OpenSeadragonController;
