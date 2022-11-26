var PagelistModel = require( './PagelistModel.js' );
var PageModel = require( './PageModel.js' );
var EditorController = require( './EditorController.js' );
var OpenseadragonController = require( './OpenseadragonController.js' );
/**
 * Implements the edit-in-sequence toolbar
 *
 * @class
 */
function Toolbar() {
	Toolbar.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-toolbar' );
	this.pagelistModel = new PagelistModel( mw.config.get( 'wgPageName' ), mw.config.get( 'prpIndexTitle' ) );
	this.pageModel = new PageModel( this.pagelistModel );
	// eslint-disable-next-line no-jquery/no-global-selector
	this.editorController = new EditorController( $( '#content' ), this.pageModel );
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

OO.inheritClass( Toolbar, OO.ui.Toolbar );

Toolbar.prototype.showError = function ( err ) {
	mw.notify( err, {
		type: 'error',
		autoHide: false,
		title: mw.msg( 'prp-editinsequence-error' )
	} );
};

module.exports = Toolbar;
