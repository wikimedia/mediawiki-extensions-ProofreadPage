var MainPanel = require( './PagelistInputWidget.MainPanel.js' );
var PageModel = require( './PagelistInputWidget.PageModel.js' );
var PagelistPreview = require( './PagelistInputWidget.PagelistPreview.js' );

/**
 * A dialog aimed at allowing a thumbnail of the scan of the page whoes
 * page number is being set to be displayed.
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Model} model
 * @param {Object} config configuration variable for process dialog
 * @class
 */
function Dialog( model, config ) {
	this.preview = new PagelistPreview( model, {
		classes: [ 'prp-pagelist-dialog-pagelist-preview' ]
	} );
	this.pageModel = new PageModel( null, model );
	this.mainPanel = new MainPanel( this.pageModel, this.preview );

	Dialog.super.call( this, config );
}

OO.inheritClass( Dialog, OO.ui.ProcessDialog );

Dialog.static.name = 'PagelistInputDialog';
Dialog.static.size = 'full';
Dialog.static.title = OO.ui.deferMsg(
	'proofreadpage-pagelist-dialog-title',
	mw.config.get( 'wgTitle' )
);
Dialog.static.actions = [
	{
		action: 'dialogsave',
		label: OO.ui.deferMsg( 'proofreadpage-pagelist-dialog-insertchanges' ),
		flags: [ 'primary', 'progressive' ]
	},
	{
		action: 'dialogcancel',
		icon: 'close',
		flags: 'safe'
	}
];

/**
 * @inheritDoc
 */
Dialog.prototype.initialize = function () {
	Dialog.super.prototype.initialize.apply( this, arguments );

	this.$body.html( this.mainPanel.$element );
};

/**
 * @inheritDoc
 * @param  {Object} data Data to setup intial view
 */
Dialog.prototype.getSetupProcess = function ( data ) {
	data = data || {};
	this.pageModel.setData( data );
	return Dialog.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			this.preview.selectItemByDataWithoutEvent( data );
		}, this );
};

/**
 * @inheritDoc
 * @event dialogclose Dialog closed
 */
Dialog.prototype.getActionProcess = function ( action ) {
	// this function should do more than just close the dialog once we add the form
	// logic to FormPanel
	var dialog = this;
	this.emit( 'dialogclose', this.preview.buttonSelectWidget.findSelectedItem() );
	if ( action === 'dialogcancel' ) {
		return new OO.ui.Process( function () {
			dialog.close( { action: action } );
		} );
	} else if ( action === 'dialogsave' ) {
		return new OO.ui.Process( function () {
			dialog.close( { action: action } );
		} );
	}
	return Dialog.super.prototype.getActionProcess.call( this, action );
};

module.exports = Dialog;
