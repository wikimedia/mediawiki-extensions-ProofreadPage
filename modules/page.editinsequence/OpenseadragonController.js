var PagelistModel = require( './PagelistModel.js' );

/**
 * A class that controls Openseadragon (OSD) behaviour based on events sent from the pagelistModel.
 * It registers a handler for 'prp-osd-before-creation' so that whenever OSD is initialized
 * this class can inject the correct images to load.
 *
 * @param {OpenseadragonController} osdInstance A instance of page.OpenseadragonController that
 * controls the image for the current page.
 * @param {PagelistModel} pagelistModel
 */
function OpenseadragonController( osdInstance, pagelistModel ) {
	this.osdInstance = osdInstance;
	this.imageSet = {};
	this.preloadSet = [];
	this.pagelistModel = pagelistModel;
	this.currentPage = '';
	this.osdInstance.on( 'prp-osd-before-creation', this.initializer.bind( this ) );
	this.pagelistModel.on( 'pageUpdated', this.update.bind( this ) );
}

/**
 * Hook to modify the Openseadragon initialization parameters
 *
 * @param {Object} osdParams Unmodified Openseadragon init parameters
 */
OpenseadragonController.prototype.initializer = function ( osdParams ) {
	var currentImageSet = this.imageSet[ this.currentPage ];
	if ( currentImageSet ) {
		osdParams.tileSources.levels[ 0 ].url = currentImageSet[ 0 ];
		osdParams.tileSources.levels[ 1 ].url = currentImageSet[ 1 ];
		osdParams.tileSources.levels[ 2 ].url = currentImageSet[ 2 ];
	}
};

/**
 * Event handler that updates the internal state based on updates from the
 * pagelistModel. Also forces initialization of Openseadragon if the
 * current state is changed.
 *
 * @param {string} currentPage The page the user is currently on
 */
OpenseadragonController.prototype.update = function ( currentPage ) {
	var api = new mw.Api(),
		nextPage = this.pagelistModel.getNext(),
		prevPage = this.pagelistModel.getPrev(),
		isCurrentPageRendered = !!this.imageSet[ currentPage ],
		apiParams = {
			action: 'query',
			format: 'json',
			prop: 'imageforpage',
			titles: isCurrentPageRendered ? '' : currentPage,
			prppifpprop: 'responsiveimages',
			formatversion: '2'
		};

	if ( isCurrentPageRendered ) {
		this.currentPage = currentPage;
		this.osdInstance.forceInitialize();
	}

	if ( nextPage.pageNumber !== -1 ) {
		apiParams.titles += '|' + nextPage.title;
	}

	if ( prevPage.pageNumber !== -1 ) {
		apiParams.titles += '|' + prevPage.title;
	}
	api.get( apiParams ).then( function ( response ) {
		var pages = response.query.pages;
		for ( var i = 0; i < pages.length; i++ ) {
			var ifp = pages[ i ].imagesforpage;
			if ( ifp && !this.imageSet[ pages[ i ].title ] && ifp.thumbnail ) {
				var ifpArray = [ ifp.thumbnail, ifp.responsiveimages[ '1.5' ], ifp.responsiveimages[ '2' ] ];
				this.imageSet[ pages[ i ].title ] = ifpArray;
				this.preload( ifpArray );
			}
		}
		if ( !isCurrentPageRendered ) {
			this.currentPage = currentPage;
			this.osdInstance.forceInitialize();
		}
	}.bind( this ) );
};

/**
 * Asynchronously preloads images.
 *
 * @param {Array<string>} ifpArray List of image to preload
 */
OpenseadragonController.prototype.preload = function ( ifpArray ) {
	for ( var i = 0; i < ifpArray.length; i++ ) {
		var img = new Image();
		img.src = ifpArray[ i ];
		this.preloadSet.push( img );
	}
};

module.exports = OpenseadragonController;
