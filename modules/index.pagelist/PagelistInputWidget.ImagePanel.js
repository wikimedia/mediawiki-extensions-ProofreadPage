const OpenSeadragon = require( 'ext.proofreadpage.openseadragon' );
/**
 * Panel that allows users to veiw a image of the scan
 * for which the page number is being set
 *
 * @param {mw.proofreadpage.PagelistInputWidget.PageModel} pageModel
 * @param {Object} config Configuration variable for PanelLayout
 * @class
 */
function ImagePanel( pageModel, config ) {
	config = config || {};
	config.scrollable = true;
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-image-panel' ) ) ||
		[ 'prp-pagelist-dialog-image-panel' ];
	ImagePanel.super.call( this, config );
	OO.ui.mixin.PendingElement.call( this, config );
	this.pageModel = pageModel;
	this.pageModel.connect( this, {
		aftersetimageurl: 'setImageSrc',
		imageurlnotfound: 'displayError'
	} );
	this.$openseadragonDiv = $( '<div>' );
	this.$openseadragonDiv.attr( 'id', 'prp-pagelist-openseadragon' );
	this.$image = $( '<img>' );
	this.apiThrottle = null;
	this.imageLoadFailed = false;
	this.imageLoadRetries = 0;

	this.$image.hide();
	this.pushPending();

	this.messages = new OO.ui.MessageWidget();
	this.messages.toggle( false );
	this.$openseadragonDiv.append( this.$image );
	this.$element.append( this.$openseadragonDiv, this.messages.$element );
}

OO.inheritClass( ImagePanel, OO.ui.PanelLayout );
OO.mixinClass( ImagePanel, OO.ui.mixin.PendingElement );

/**
 * Sets the source of the image and handles
 *
 * @param {string} url
 * @param {string} zoomUrlOnePointFive
 * @param {string} zoomUrlTwo
 * @param {number} width
 * @param {number} height
 */
ImagePanel.prototype.setImageSrc = function (
	url,
	zoomUrlOnePointFive,
	zoomUrlTwo,
	width,
	height
) {
	this.messages.toggle( false );
	this.$image.hide();
	this.popPending();
	this.pushPending();
	this.$image.attr( 'src', url );

	this.$image.on( 'load', () => {
		this.popPending();
		if ( this.viewer ) {
			this.removeMap( url, zoomUrlOnePointFive, zoomUrlTwo, width, height );
		} else {
			this.zoomPan( url, zoomUrlOnePointFive, zoomUrlTwo, width, height );
		}
		// If we have had a 'fail to load' before, clear
		// the setInterval cause we probably
		// don't need it anymore
		if ( this.apiThrottle ) {
			clearInterval( this.apiThrottle );
			this.apiThrottle = null;
		}
		this.imageLoadFailed = false;
		this.imageLoadRetries = 0;
	} );
	this.$image.on( 'error', () => {
		this.imageLoadFailed = true;
		this.onImageFailedToLoad();
	} );
};

/**
 * Nudges the backend to send us the correct url
 *  and/or create the image if not done so. We poll 5 times
 *  with a wait of 2 seconds and then error out.
 */
ImagePanel.prototype.onImageFailedToLoad = function () {
	// We probably don't have the image created already. In this case,
	// make another request to the server every 2 seconds to nudge it to
	// create the image for us. In case we have nudged more
	// than 5 times, error out.
	if ( this.imageLoadRetries === 0 ) {
		this.pageModel.generateImageLink( this.pageModel.data );
		this.imageLoadRetries++;

		this.apiThrottle = setInterval( () => {
			if ( this.imageLoadFailed ) {
				this.pageModel.generateImageLink( this.pageModel.data );

				this.imageLoadRetries++;
				this.imageLoadFailed = false;
			}
		}, 2000 );
	} else if ( this.imageLoadRetries > 5 ) {
		clearInterval( this.apiThrottle );
		this.displayError();
	}
};

/**
 * Handles errors during retrieving url
 */
ImagePanel.prototype.displayError = function () {
	this.popPending();
	this.messages.toggle( true );
	this.$image.hide();
	this.messages.setLabel( mw.msg( 'proofreadpage-pagelist-imageurlnotfound' ) );
};

/**
 * Swap the image tilesource, if image already exist
 *
 * @param {string} url
 * @param {string} zoomUrlOnePointFive
 * @param {string} zoomUrlTwo
 * @param {number} width
 * @param {number} height
 */
ImagePanel.prototype.removeMap = function ( url, zoomUrlOnePointFive, zoomUrlTwo, width, height ) {
	this.newTileSource = {
		type: 'legacy-image-pyramid',
		levels: [ {
			url: url,
			height: height,
			width: width
		},
		{
			url: zoomUrlOnePointFive,
			height: 1.5 * height,
			width: 1.5 * width
		},
		{
			url: zoomUrlTwo,
			height: 2 * height,
			width: 2 * width
		}
		]
	};
	this.viewer.open( this.newTileSource );

};

/**
 * Add OpenSeadragon library to image, for zooming and panning.
 *
 * @param {string} url
 * @param {string} zoomUrlOnePointFive
 * @param {string} zoomUrlTwo
 * @param {number} width
 * @param {number} height
 */
ImagePanel.prototype.zoomPan = function ( url, zoomUrlOnePointFive, zoomUrlTwo, width, height ) {
	this.viewer = new OpenSeadragon( {
		id: 'prp-pagelist-openseadragon',
		zoomInButton: 'prp-openseadragon-zoomIn',
		zoomOutButton: 'prp-openseadragon-zoomOut',
		homeButton: 'prp-openseadragon-home',
		showNavigator: true,
		showFullPageControl: false,
		navigatorHeight: '140px',
		navigatorWidth: '80px',
		animationTime: 0.5,
		preserveViewport: true,
		visibilityRatio: 0.5,
		minZoomLevel: 0.5,
		maxZoomLevel: 4.5,
		tileSources: {
			type: 'legacy-image-pyramid',
			levels: [ {
				url: url,
				height: height,
				width: width
			},
			{
				url: zoomUrlOnePointFive,
				height: 1.5 * height,
				width: 1.5 * width
			},
			{
				url: zoomUrlTwo,
				height: 2 * height,
				width: 2 * width
			}
			]
		}

	} );

	this.viewer.viewport.goHome = function () {
		if ( this.viewer ) {
			const oldBounds = this.viewer.viewport.getBounds();
			const newBounds = new OpenSeadragon.Rect( 0, 0, 1, oldBounds.height / oldBounds.width );
			this.viewer.viewport.fitBounds( newBounds, true );
		}
	}.bind( this );

};

module.exports = ImagePanel;
