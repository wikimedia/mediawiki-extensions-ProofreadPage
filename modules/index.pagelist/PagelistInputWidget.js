var Model = require( './PagelistInputWidget.Model.js' );
var PagelistPreview = require( './PagelistInputWidget.PagelistPreview.js' );
var PagelistInputWidgetDialog = require( './PagelistInputWidget.Dialog.js' );
/**
 * A widget aimed at making the job of creating pagelists easier
 *
 * @param {Object} config configuration variables for OO.ui.Widget and OO.ui.MultilineInputWidget
 * @class
 */
function PagelistInputWidget( config ) {

	PagelistInputWidget.super.call( this, config );
	OO.ui.mixin.PendingElement.call( this, config );

	this.textInputWidget = config.textInputWidget;

	this.api = new mw.Api();
	this.model = new Model(
		config.templateParameter,
		this.textInputWidget.getValue() );
	this.output = new PagelistPreview( this.model, {
		classes: [ 'prp-pagelist-input-preview-output' ]
	} );
	this.buttonWidget = new OO.ui.ButtonWidget( {
		label: mw.msg( 'proofreadpage-pagelist-input-preview-button' ),
		flags: [ 'progressive' ],
		classes: [ 'prp-pagelist-input-preview-button' ]
	} );

	this.dialog = new PagelistInputWidgetDialog( this.model );
	this.dialog.connect( this, {
		dialogclose: 'onDialogClose'
	} );

	OO.ui.getWindowManager().addWindows( [ this.dialog ] );

	this.buttonWidget.connect( this, {
		click: 'onButtonClick'
	} );

	this.output.connect( this, {
		previewDisplayed: 'onPreviewResolution',
		errorDisplayed: 'onPreviewResolution',
		pageselected: 'openWindow'
	} );

	this.$element.append(
		this.textInputWidget.$element,
		this.buttonWidget.$element,
		this.output.$element
	);
}

OO.inheritClass( PagelistInputWidget, OO.ui.Widget );
OO.mixinClass( PagelistInputWidget, OO.ui.mixin.PendingElement );

/**
 * Handles clicks on this.buttonWidget
 */
PagelistInputWidget.prototype.onButtonClick = function () {
	this.buttonWidget.setDisabled( true );
	this.textInputWidget.pushPending();
	this.textInputWidget.setDisabled( true );

	this.model.updateWikitext( this.textInputWidget.getValue() );
};

/**
 * Handles tasks to be performed once the preview/errors have been displayed
 */
PagelistInputWidget.prototype.onPreviewResolution = function () {
	this.buttonWidget.setDisabled( false );
	this.textInputWidget.popPending();
	this.textInputWidget.setDisabled( false );
};

/**
 * Opens the Wikisource Pagelist Dialog
 *
 * @param {OO.ui.OptionWidget} selectedOption option that was selected prior to firing the event
 */
PagelistInputWidget.prototype.openWindow = function ( selectedOption ) {
	// will probably be caused by onDialogClose
	if ( !selectedOption ) {
		return;
	}
	OO.ui.getWindowManager().openWindow( 'PagelistInputDialog', selectedOption.getData() || {} );
};

/**
 * Handles dialogclose events
 *
 * @param {OO.ui.OptionWiget} selectedOption item that was selected prior to firing the event
 */
PagelistInputWidget.prototype.onDialogClose = function ( selectedOption ) {
	if ( selectedOption ) {
		this.output.selectItemByDataWithoutEvent( selectedOption.getData() );
	}
};

module.exports = PagelistInputWidget;
