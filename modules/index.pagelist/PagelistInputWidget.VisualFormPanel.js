var PageNumberInputWidget = require( './PagelistInputWidget.PageNumberInputWidget.js' );

/**
 * Panel that shows current page number status
 * and provides a form to change the page number.
 *
 * @param {mw.proofreadpage.PagelistInputWidget.VisualDialogModel} VisualDialogModel
 * @param {Object} config config variable for PanelLayout
 * @class
 */
function VisualFormPanel( VisualDialogModel, config ) {
	config = config || {};
	config.padded = true;
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-visual-form-panel' ) ) ||
		[ 'prp-pagelist-dialog-visual-form-panel' ];
	VisualFormPanel.super.call( this, config );
	this.data = null;
	this.visualDialogModel = VisualDialogModel;
	this.visualDialogModel.connect( this, {
		aftersetpagedata: 'setPageData'
	} );
	this.pageNumberInput = new PageNumberInputWidget();
	this.numberInput = new OO.ui.NumberInputWidget( {
		min: 1
	} );
	this.singlePageToggle = new OO.ui.CheckboxInputWidget( {
		selected: false
	} );
	this.updateButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'proofreadpage-pagelist-dialog-update-button' ),
		flags: [ 'progressive' ]
	} );
	this.helpButton = new OO.ui.ButtonWidget( {
		icon: 'helpNotice',
		framed: false,
		href: mw.msg( 'proofreadpage-pagelist-dialog-visual-help' ),
		label: mw.msg( 'proofreadpage-pagelist-dialog-help-invisible-label' ),
		invisibleLabel: true,
		target: '_blank'
	} );

	this.pageNumberInputLayout = new OO.ui.FieldLayout( this.pageNumberInput, {
		label: mw.msg( 'proofreadpage-pagelist-dialog-visual-page-number-type-input-label' ),
		align: 'inline'
	} );
	this.numberInputLayout = new OO.ui.FieldLayout( this.numberInput, {
		label: mw.msg( 'proofreadpage-pagelist-dialog-visual-page-number-input-label' ),
		align: 'inline'
	} );

	this.singlePageToggleLayout = new OO.ui.FieldLayout( this.singlePageToggle, {
		label: mw.msg( 'proofreadpage-pagelist-dialog-visual-single-page-toggle-label' ),
		align: 'inline'
	} );

	this.horizontalLayout = new OO.ui.HorizontalLayout( {
		items: [
			this.updateButton,
			this.helpButton
		],
		classes: [ 'prp-pagelist-dialog-visual-submit-button-row' ]
	} );

	this.form = new OO.ui.FieldsetLayout( {
		items: [
			this.pageNumberInputLayout,
			this.numberInputLayout,
			this.singlePageToggleLayout,
			this.horizontalLayout
		]
	} );

	this.pageNumberInput.connect( this, {
		changedToSinglePageIncompatibleValue: 'disableSinglePageToggle',
		changedToNumberingCompatibleValue: 'enableNumberField',
		changedToNumberingIncompatibleValue: 'disableNumberField',
		enter: 'onEnter'
	} );
	this.numberInput.connect( this, {
		change: 'enableUpdateButton',
		enter: 'onUpdate'
	} );

	this.updateButton.connect( this, {
		click: 'onUpdate'
	} );
	this.singlePageToggle.connect( this, {
		change: 'setFormLabel'
	} );

	this.$element.append( this.$messages, this.form.$element );
}

OO.inheritClass( VisualFormPanel, OO.ui.PanelLayout );

/**
 * Update page number.
 */
VisualFormPanel.prototype.onUpdate = function () {
	var pageNumberType = this.pageNumberInput.getValue(),
		single = this.singlePageToggle.isSelected(),
		pageNumber = this.numberInput.getValue();
	this.visualDialogModel.updateCachedData( {
		label: pageNumberType,
		single: single,
		number: pageNumber
	} );
	this.updateButton.setDisabled( true );
};

/**
 * Handles enter events from pageNumberInput widget, focusses numberInput,
 * if numberInput is disabled updates pagelist
 */
VisualFormPanel.prototype.onEnter = function () {
	if ( this.numberInput.isDisabled() ) {
		this.onUpdate();
	} else {
		this.numberInput.focus();
	}
};

/**
 * Sets form label
 */
VisualFormPanel.prototype.setFormLabel = function () {
	if ( !this.singlePageToggle.isSelected() ) {
		this.form.setLabel( mw.msg( 'proofreadpage-pagelist-dialog-visual-form-panel-label-range', this.data.subPage ) );
	} else {
		this.form.setLabel( mw.msg( 'proofreadpage-pagelist-dialog-visual-form-panel-label-single', this.data.subPage ) );
	}
};

/**
 * Disables the single page toggle for the particular page.
 */
VisualFormPanel.prototype.disableSinglePageToggle = function () {
	this.singlePageToggle.setDisabled( true );
	this.numberInput.setDisabled( false );
	this.numberInputLayout.setWarnings( [] );
	this.enableUpdateButton();
};

/**
 * Sets the number field and/or displays a warning if
 * input is already present.
 */
VisualFormPanel.prototype.disableNumberField = function () {
	this.singlePageToggle.setDisabled( false );
	if ( !this.numberInput.getValue() ) {
		this.numberInput.setDisabled( true );
		this.numberInputLayout.setWarnings( [] );
	} else {
		this.numberInput.setDisabled( false );
		this.numberInputLayout.setWarnings( [
			mw.msg( 'proofreadpage-pagelist-dialog-visual-number-field-disabled-but-active' )
		] );
		this.numberInput.once( 'change', this.disableNumberField.bind( this ) );
	}

	this.enableUpdateButton();
};

/**
 * Enables the number input field
 */
VisualFormPanel.prototype.enableNumberField = function () {
	this.singlePageToggle.setDisabled( false );
	this.numberInput.setDisabled( false );
	this.numberInputLayout.setWarnings( [] );
	this.enableUpdateButton();
};

VisualFormPanel.prototype.enableUpdateButton = function () {
	this.updateButton.setDisabled( false );
};

/**
 * Sets page data and creates and appends messages
 *
 * @param {Object} data
 */
VisualFormPanel.prototype.setPageData = function ( data ) {
	this.data = data;
	this.setFormLabel();
	this.singlePageToggle.setSelected( false );
	this.numberInputLayout.setWarnings( [] );
	this.numberInput.setValue( data.assignedPageNumber || '' );
	this.pageNumberInput.setValue( data.type );
	this.pageNumberInput.updateFormPanel();
	this.updateButton.setDisabled( true );
};

module.exports = VisualFormPanel;
