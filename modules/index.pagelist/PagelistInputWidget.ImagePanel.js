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

	this.$image = $( '<img>' );
	this.apiThrottle = null;
	this.imageLoadFailed = false;
	this.imageLoadRetries = 0;

	this.$image.hide();
	this.pushPending();
	this.$element.css( 'min-height', '100%' );

	this.messages = new OO.ui.MessageWidget();
	this.messages.toggle( false );

	this.$element.append( this.$image, this.messages.$element );
}

OO.inheritClass( ImagePanel, OO.ui.PanelLayout );
OO.mixinClass( ImagePanel, OO.ui.mixin.PendingElement );

/**
 * Sets the source of the image and handles
 *
 * @param {string} url
 */
ImagePanel.prototype.setImageSrc = function ( url ) {
	this.messages.toggle( false );
	this.$image.hide();
	this.popPending();
	this.pushPending();
	this.$image.attr( 'src', url );

	this.$image.on( 'load', function () {
		this.$image.show();
		this.popPending();
		// stops the scrollbar from jumping around
		this.$element.css( 'min-height', this.$image.height() + 'px' );
		// If we have had a 'fail to load' before, clear
		// the setInterval cause we probably
		// don't need it anymore
		if ( this.apiThrottle ) {
			clearInterval( this.apiThrottle );
			this.apiThrottle = null;
		}
		this.imageLoadFailed = false;
		this.imageLoadRetries = 0;
	}.bind( this ) );

	this.$image.on( 'error', function () {
		this.imageLoadFailed = true;
		this.onImageFailedToLoad();
	}.bind( this ) );
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

		this.apiThrottle = setInterval( function () {
			if ( this.imageLoadFailed ) {
				this.pageModel.generateImageLink( this.pageModel.data );

				this.imageLoadRetries++;
				this.imageLoadFailed = false;
			}
		}.bind( this ), 2000 );
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

module.exports = ImagePanel;
