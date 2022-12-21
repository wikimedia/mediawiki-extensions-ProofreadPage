var PagelistModel = require( './PagelistModel.js' );
var PageModel = require( './PageModel.js' );
var EditorController = require( './EditorController.js' );
var OpenseadragonController = require( './OpenseadragonController.js' );
var SaveOptionsDialog = require( './SaveOptionsDialog.js' );
var SaveOptionsModel = require( './SaveOptionsModel.js' );
/**
 * Implements the edit-in-sequence services
 *
 * @class
 */
function EditInSequence() {
	this.pagelistModel = new PagelistModel( mw.config.get( 'wgPageName' ), mw.config.get( 'prpIndexTitle' ) );
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
	this.osdController = null;
	if ( mw.proofreadpage.openseadragon ) {
		this.osdController = new OpenseadragonController( mw.proofreadpage.openseadragon, this.pagelistModel );
	} else {
		mw.hook( 'ext.proofreadpage.osd-controller-available' ).add( function () {
			this.osdController = new OpenseadragonController( mw.proofreadpage.openseadragon, this.pagelistModel );
		}.bind( this ) );
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

EditInSequence.prototype.showError = function ( err ) {
	mw.notify( err, {
		type: 'error',
		autoHide: false,
		title: mw.msg( 'prp-editinsequence-error' )
	} );
};

module.exports = EditInSequence;
