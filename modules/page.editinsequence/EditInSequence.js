const PagelistModel = require( './PagelistModel.js' );
const PageModel = require( './PageModel.js' );
const EditorController = require( './EditorController.js' );
const OpenseadragonController = require( './OpenseadragonController.js' );
const SaveOptionsDialog = require( './SaveOptionsDialog.js' );
const SaveOptionsModel = require( './SaveOptionsModel.js' );
const PageSelectionLayout = require( './PageSelectionLayout.js' );
const pageSelectionFilter = require( './PageSelectionFilter.js' );
/**
 * Implements the edit-in-sequence services
 *
 * @class
 */
function EditInSequence() {
	const currentPage = decodeURIComponent( location.hash.slice( 1 ) ) || mw.config.get( 'wgPageName' );
	this.pagelistModel = new PagelistModel( currentPage, mw.config.get( 'prpIndexTitle' ) );
	this.pageModel = new PageModel( this.pagelistModel );
	this.saveOptionsModel = new SaveOptionsModel();
	// eslint-disable-next-line no-jquery/no-global-selector
	this.editorController = new EditorController( $( '#content' ), this.pageModel, this.pagelistModel, this.saveOptionsModel );
	this.saveOptionsDialog = new SaveOptionsDialog( {
		saveModel: this.saveOptionsModel,
		editorController: this.editorController,
		size: 'large'
	} );
	OO.ui.getWindowManager().addWindows( [ this.saveOptionsDialog ] );
	this.pageSelectionLayout = new PageSelectionLayout( this.pagelistModel );
	for ( let i = 0; i < pageSelectionFilter.length; i++ ) {
		this.pageSelectionLayout.register( pageSelectionFilter[ i ].name, pageSelectionFilter[ i ] );
	}
	mw.hook( 'ext.proofreadpage.page-selection-register-filter' ).fire( this.pageSelectionLayout );
	this.pageSelectionLayout.initialize();
	this.osdController = null;
	if ( mw.proofreadpage.openseadragon ) {
		this.osdController = new OpenseadragonController( mw.proofreadpage.openseadragon, this.pagelistModel );
	} else {
		mw.hook( 'ext.proofreadpage.osd-controller-available' ).add( () => {
			this.osdController = new OpenseadragonController( mw.proofreadpage.openseadragon, this.pagelistModel );
		} );
	}

	this.pagelistModel.on( 'error', this.showError );
	this.pageModel.on( 'error', this.showError );
}

/**
 * Open the save dialog
 */
EditInSequence.prototype.openSaveDialog = function () {
	OO.ui.getWindowManager().openWindow( this.saveOptionsDialog );
};

EditInSequence.prototype.getPageSelectionLayout = function () {
	return this.pageSelectionLayout;
};

EditInSequence.prototype.showError = function ( err ) {
	mw.notify( err, {
		type: 'error',
		autoHide: false,
		title: mw.msg( 'prp-editinsequence-error' )
	} );
};

module.exports = EditInSequence;
