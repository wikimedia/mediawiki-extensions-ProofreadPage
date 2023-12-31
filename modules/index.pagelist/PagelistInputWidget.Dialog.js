const MainPanel = require( './PagelistInputWidget.MainPanel.js' );
const WikitextDialogModel = require( './PagelistInputWidget.WikitextDialogModel.js' );
const VisualDialogModel = require( './PagelistInputWidget.VisualDialogModel.js' );
const PagelistPreview = require( './PagelistInputWidget.PagelistPreview.js' );

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

	this.visualMode = !!parseInt( mw.user.options.get( 'proofreadpage-pagelist-use-visual-mode' ) );
	this.wikitextDialogModel = new WikitextDialogModel( null, model );
	this.visualDialogModel = new VisualDialogModel( null, model );
	this.dialogModel = this.visualMode ? this.visualDialogModel : this.wikitextDialogModel;
	this.mainPanel = new MainPanel(
		this.visualMode,
		this.wikitextDialogModel,
		this.visualDialogModel,
		this.preview
	);

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
 */
Dialog.prototype.showErrors = function ( errors ) {
	Dialog.super.prototype.showErrors.call( this, errors );
	if ( this.currentAction === 'dialogcancel' ) {
		this.dismissButton.setLabel( mw.msg( 'proofreadpage-pagelist-dialog-cancel' ) );
		this.retryButton.clearFlags().setFlags( 'destructive' );
		this.$errorsTitle.text( mw.msg( 'proofreadpage-pagelist-dialog-unsaved-progress-title' ) );
		this.retryButton.setLabel( mw.msg( 'proofreadpage-pagelist-dialog-discard-changes' ) );
	}
};

/**
 * @inheritDoc
 */
Dialog.prototype.onRetryButtonClick = function () {
	if ( this.currentAction === 'dialogcancel' ) {
		// Set the changed flag and unload everything
		this.dialogModel.setChangedFlag( false );
		this.dialogModel.unloadCachedData();
	}
	Dialog.super.prototype.onRetryButtonClick.call( this );
};
/**
 * @inheritDoc
 * @param  {Object} data Data to setup intial view
 */
Dialog.prototype.getSetupProcess = function ( data ) {
	data = data || {};
	this.dialogModel.setData( data );
	return Dialog.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			this.preview.buttonSelectWidget.selectItemByData( data );
			this.dialogModel.dialogOpened();
		}, this );
};

/**
 * @inheritDoc
 * @event dialogclose Dialog closed
 */
Dialog.prototype.getActionProcess = function ( action ) {
	// this function should do more than just close the dialog once we add the form
	// logic to FormPanel
	this.emit( 'dialogclose', this.preview.buttonSelectWidget.findSelectedItem() );
	return Dialog.super.prototype.getActionProcess.call( this, action ).next( function () {
		if ( action === 'dialogcancel' ) {
			if ( !this.dialogModel.unloadCachedData() ) {
				return new OO.ui.Error( mw.msg( 'proofreadpage-pagelist-dialog-unsaved-progress' ) );
			} else {
				this.close( { action: action } );
			}
		} else if ( action === 'dialogsave' ) {
			this.dialogModel.setCachedData();
			this.close( { action: action } );
		}
		return Dialog.super.prototype.getActionProcess.call( this, action );
	}, this );
};

/**
 * @inheritDoc
 */
Dialog.prototype.onDialogKeyDown = function ( e ) {
	if ( e.which === OO.ui.Keys.ESCAPE ) {
		this.executeAction( 'dialogcancel' );
		e.preventDefault();
		e.stopPropagation();
	}
};

Dialog.prototype.changeEditMode = function ( mode ) {
	this.visualMode = !!parseInt( mode );
	this.dialogModel = this.visualMode ? this.visualDialogModel : this.wikitextDialogModel;
	this.mainPanel.changeEditMode( this.visualMode );
};

module.exports = Dialog;
