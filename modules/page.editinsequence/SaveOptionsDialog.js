const SaveOptionsModel = require( './SaveOptionsModel.js' );

/**
 * Dialog asking for save metadata
 *
 * @param {Object} config Configuration variable for save options dialog
 * @class
 */
function SaveOptionsDialog( config ) {
	config = config || {};
	SaveOptionsDialog.super.call( this, config );
	this.saveGlobalModel = config.saveModel;
	this.saveModel = new SaveOptionsModel();
	this.editorController = config.editorController;
}

OO.inheritClass( SaveOptionsDialog, OO.ui.ProcessDialog );

SaveOptionsDialog.static.name = 'SaveOptionsDialog';

const publishButtonLabel = mw.msg( mw.config.get( 'wgEditSubmitButtonLabelPublish' ) ? 'publishchanges' : 'savechanges' );

SaveOptionsDialog.static.title = OO.ui.deferMsg( 'prp-editinsequence-save-dialog-title' );

SaveOptionsDialog.static.actions = [
	{
		flags: [ 'primary', 'progressive' ],
		label: publishButtonLabel,
		action: 'save'
	},
	{
		flags: 'safe',
		icon: 'close',
		action: 'dialogcancel'
	}
];

/**
 * @inheritdoc
 */
SaveOptionsDialog.prototype.initialize = function () {
	SaveOptionsDialog.super.prototype.initialize.call( this );

	this.editFieldSet = new OO.ui.FieldsetLayout();

	this.editSummary = new OO.ui.MultilineTextInputWidget( {
		placeholder: mw.msg( 'prp-editinsequence-editsummary-placeholder' ),
		autosize: true,
		maxLength: mw.config.get( 'wgCommentCodePointLimit' ),
		label: String( mw.config.get( 'wgCommentCodePointLimit' ) )
	} );
	this.editSummary.on( 'change', this.onEditSummaryChange.bind( this ) );

	this.minorEditCheckbox = new OO.ui.CheckboxInputWidget();
	this.minorEditCheckbox.on( 'change', this.onMinorEditChange.bind( this ) );
	this.shouldWatchlistCheckbox = new OO.ui.CheckboxInputWidget();
	this.shouldWatchlistCheckbox.on( 'change', this.onShouldWatchlistChange.bind( this ) );

	this.editFieldSet.addItems( [
		new OO.ui.FieldLayout( this.editSummary, {
			label: mw.msg( 'summary' ),
			align: 'top'
		} ),
		new OO.ui.FieldLayout( this.minorEditCheckbox, { label: mw.msg( 'minoredit' ), align: 'inline' } ),
		new OO.ui.FieldLayout( this.shouldWatchlistCheckbox, { label: mw.msg( 'watchthis' ), align: 'inline' } )
	] );

	this.afterSaveActionField = new OO.ui.ButtonSelectWidget( { items: [
		new OO.ui.ButtonOptionWidget( {
			data: 'do-nothing',
			label: mw.msg( 'prp-editinsequence-save-next-action-do-nothing' )
		} ),
		new OO.ui.ButtonOptionWidget( {
			data: 'go-to-next',
			label: mw.msg( 'prp-editinsequence-save-next-action-go-to-next' )
		} ),
		new OO.ui.ButtonOptionWidget( {
			data: 'go-to-previous',
			label: mw.msg( 'prp-editinsequence-save-next-action-go-to-prev' )
		} )
	] } );
	this.afterSaveActionField.on( 'choose', this.onafterSaveActionChange.bind( this ) );
	this.shouldShowDialogField = new OO.ui.ToggleSwitchWidget( {
		value: true
	} );
	this.shouldShowDialogField.on( 'change', this.onShouldShowDialogFieldChange.bind( this ) );

	this.eisFieldSet = new OO.ui.FieldsetLayout();
	this.eisFieldSet.addItems( [
		new OO.ui.FieldLayout( this.afterSaveActionField, { label: mw.msg( 'prp-editinsequence-save-next-action-label', publishButtonLabel ), align: 'top' } ),
		new OO.ui.FieldLayout( this.shouldShowDialogField, { label: mw.msg( 'prp-editinsequence-show-dialog-toggle-label', publishButtonLabel ) } )
	] );

	this.panel = new OO.ui.PanelLayout( {
		padded: true,
		expanded: false
	} );
	this.panel.$element.append( [ this.editFieldSet.$element, this.eisFieldSet.$element ] );
	this.$body.append( this.panel.$element );
};

SaveOptionsDialog.prototype.onShouldShowDialogFieldChange = function ( value ) {
	this.saveModel.setDialogBeforeSave( value );
};

SaveOptionsDialog.prototype.onEditSummaryChange = function ( value ) {
	this.saveModel.setEditSummary( value );
	this.editSummary.setLabel( String( mw.config.get( 'wgCommentCodePointLimit' ) - value.length ) );
};

SaveOptionsDialog.prototype.onMinorEditChange = function ( selected ) {
	this.saveModel.setMinorEdit( selected );
};

SaveOptionsDialog.prototype.onShouldWatchlistChange = function ( selected ) {
	this.saveModel.setShouldWatchlist( selected );
};

SaveOptionsDialog.prototype.onafterSaveActionChange = function ( item ) {
	this.saveModel.setAfterSaveAction( item.getData() );
};

/**
 * @inheritdoc
 */
SaveOptionsDialog.prototype.getSetupProcess = function ( data ) {
	data = data || {};
	if ( !this.saveGlobalModel.getDialogBeforeSave() ) {
		data.title = mw.msg( 'prp-editinsequence-save-settings-dialog-title' );
		data.actions = [
			{
				flags: [ 'primary', 'progressive' ],
				label: mw.msg( 'prp-editinsequence-save-settings-dialog-primary-button-title' ),
				action: 'savesettings'
			},
			{
				flags: 'safe',
				icon: 'close',
				action: 'dialogcancel'
			}
		];
	}
	return SaveOptionsDialog.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			this.saveModel = this.saveGlobalModel.clone();

			const saveData = this.saveModel.getSaveData();
			this.editSummary.setValue( saveData.editSummary );
			this.minorEditCheckbox.setValue( saveData.isMinorEdit );
			this.shouldWatchlistCheckbox.setValue( saveData.shouldWatchlist );
			this.afterSaveActionField.chooseItem( this.afterSaveActionField.findItemFromData( this.saveModel.getAfterSaveAction() ) );
			this.shouldShowDialogField.setValue( this.saveModel.getDialogBeforeSave() );
		}, this );
};

/**
 * @inheritdoc
 */
SaveOptionsDialog.prototype.getActionProcess = function ( action ) {
	if ( action === 'save' ) {
		return new OO.ui.Process( function () {
			this.saveGlobalModel.merge( this.saveModel );
			this.pushPending();
			this.editorController.save().always( ( result, errorResult ) => {
				this.popPending();
				this.close( { action: action } );
				if ( errorResult && errorResult.error ) {
					mw.notify( errorResult.error.info,
						{ title: mw.msg( 'prp-editinsequence-could-not-save-edit' ), type: 'error' } );
				}
			} );
		}, this );
	} else if ( action === 'savesettings' ) {
		return new OO.ui.Process( function () {
			this.saveGlobalModel.merge( this.saveModel );
			this.close( { action: action } );
		}, this );
	} else {
		this.close( { action: action } );
	}
	return SaveOptionsDialog.super.prototype.getActionProcess.call( this, action );
};

module.exports = SaveOptionsDialog;
