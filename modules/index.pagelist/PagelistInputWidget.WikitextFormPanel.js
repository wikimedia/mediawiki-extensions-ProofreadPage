/**
 * A form panel to edit the pagelist in wikitext format
 *
 * @param {mw.proofreadpage.PagelistInputWidget.WikitextDialogModel} dialogModel Model that corodinates various parts of the Dialog UI
 * @param {Object} config      configuration object
 * @class
 */
function WikitextFormPanel( dialogModel, config ) {
	config = config || {};
	config.padded = true;
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-wikitext-form-panel' ) ) ||
		[ 'prp-pagelist-dialog-wikitext-form-panel' ];
	WikitextFormPanel.super.call( this, config );
	this.dialogModel = dialogModel;

	this.multilineTextInput = new OO.ui.MultilineTextInputWidget( {
		autosize: true,
		maxRows: 10,
		classes: [ 'prp-pagelist-dialog-text-input' ]
	} );
	this.updateButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'proofreadpage-pagelist-input-preview-button' ),
		flags: [ 'progressive' ],
		classes: [ 'prp-pagelist-dialog-update-button' ]
	} );
	this.helpButton = new OO.ui.ButtonWidget( {
		icon: 'helpNotice',
		framed: false,
		href: mw.msg( 'proofreadpage-pagelist-dialog-visual-help' ),
		label: mw.msg( 'proofreadpage-pagelist-dialog-help-invisible-label' ),
		invisibleLabel: true,
		target: '_blank'
	} );

	this.horizontalLayout = new OO.ui.HorizontalLayout( {
		items: [
			this.updateButton,
			this.helpButton
		],
		classes: [ 'prp-pagelist-dialog-wikitext-submit-button-row' ]
	} );

	this.multilineTextInput.connect( this, {
		change: 'updateCacheStatus',
		enter: 'updateModel'
	} );

	this.dialogModel.connect( this, {
		updateWikiText: 'updateWikiText',
		pagelistPreviewGenerated: 'stopPending',
		dialogOpened: 'setupForm'
	} );

	this.updateButton.connect( this, {
		click: 'updateModel'
	} );

	this.updateButton.connect( this, {
		click: 'updateCacheStatus'
	} );

	this.$element.append( this.multilineTextInput.$element, this.horizontalLayout.$element );
}

OO.inheritClass( WikitextFormPanel, OO.ui.PanelLayout );

/**
 * Updates wikitext based on DialogModel events
 *
 * @param  {string} wikitext
 */
WikitextFormPanel.prototype.updateWikiText = function ( wikitext ) {
	this.multilineTextInput.setValue( wikitext );
};

/**
 * Updates the DialogModel based on input in the text area.
 */
WikitextFormPanel.prototype.updateModel = function () {
	var wikitext = this.multilineTextInput.getValue();
	this.makePending();
	this.dialogModel.updateCachedData( wikitext );
};

/**
 * Adds pending animation and prevents user input
 */
WikitextFormPanel.prototype.makePending = function () {
	this.multilineTextInput.disconnect( this, {
		enter: 'updateModel'
	} );

	this.updateButton.setDisabled( true );
};

/**
 * Removes pending animation and removes disabled status
 */
WikitextFormPanel.prototype.stopPending = function () {
	this.multilineTextInput.connect( this, {
		enter: 'updateModel'
	} );

	this.multilineTextInput.focus();
	this.updateButton.setDisabled( false );
};

/**
 * Resizes the form after user has clicked on the
 * pagelist preview.
 */
WikitextFormPanel.prototype.setupForm = function () {
	// Hack to wait until the Dialog has fully loaded
	setTimeout( function () {
		this.multilineTextInput.adjustSize( true );
		this.multilineTextInput.focus();
	}.bind( this ), 700 );
};

/**
 * Notifies the DialogModel that the wikitext has changed.
 */
WikitextFormPanel.prototype.updateCacheStatus = function () {
	this.dialogModel.setChangedCachedData( this.multilineTextInput.getValue() );
};

module.exports = WikitextFormPanel;
