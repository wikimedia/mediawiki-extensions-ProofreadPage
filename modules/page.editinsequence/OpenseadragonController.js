const PagelistModel = require( './PagelistModel.js' );

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
	const currentImage = this.imageSet[ this.currentPage ];
	if ( currentImage ) {
		osdParams.tileSources.url = currentImage;
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
	const api = new mw.Api(),
		nextPage = this.pagelistModel.getNext(),
		prevPage = this.pagelistModel.getPrev(),
		isCurrentPageRendered = !!this.imageSet[ currentPage ],
		apiParams = {
			action: 'query',
			format: 'json',
			prop: 'imageforpage',
			titles: isCurrentPageRendered ? '' : currentPage,
			prppifpprop: '',
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
	api.get( apiParams ).then( ( response ) => {
		const pages = response.query.pages;
		for ( let i = 0; i < pages.length; i++ ) {
			const ifp = pages[ i ].imagesforpage;
			if ( ifp && !this.imageSet[ pages[ i ].title ] && ifp.thumbnail ) {
				this.imageSet[ pages[ i ].title ] = ifp.thumbnail;
				this.preload( ifp.thumbnail );
			}
		}
		if ( !isCurrentPageRendered ) {
			this.currentPage = currentPage;
			this.osdInstance.forceInitialize();
		}
	} );
};

/**
 * Asynchronously preloads images.
 *
 * @param {string} ifpThumb Image to preload
 */
OpenseadragonController.prototype.preload = function ( ifpThumb ) {
	const img = new Image();
	img.src = ifpThumb;
	this.preloadSet.push( img );
};

module.exports = OpenseadragonController;
