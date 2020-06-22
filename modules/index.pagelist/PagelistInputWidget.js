var PagelistInputWidgetModel = require( './PagelistInputWidget.Model.js' );
var PagelistPreview = require( './PagelistInputWidget.PagelistPreview.js' );
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
	this.model = new PagelistInputWidgetModel(
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

	this.buttonWidget.connect( this, {
		click: 'onButtonClick'
	} );

	this.output.connect( this, {
		previewDisplayed: 'onPreviewResolution',
		errorDisplayed: 'onPreviewResolution'
	} );

	this.$element.append( this.textInputWidget.$element, this.buttonWidget.$element, this.output.$element );
}

OO.inheritClass( PagelistInputWidget, OO.ui.Widget );
OO.mixinClass( PagelistInputWidget, OO.ui.mixin.PendingElement );

/**
 * Handles clicks on this.buttonWidget
 */
PagelistInputWidget.prototype.onButtonClick = function () {
	this.buttonWidget.setDisabled( true );
	this.textInputWidget.pushPending();

	this.model.updateWikitext( this.textInputWidget.getValue() );
};

/**
 * Handles tasks to be performed once the preview/errors have been displayed
 */
PagelistInputWidget.prototype.onPreviewResolution = function () {
	this.buttonWidget.setDisabled( false );
	this.textInputWidget.popPending();
};

module.exports = PagelistInputWidget;
