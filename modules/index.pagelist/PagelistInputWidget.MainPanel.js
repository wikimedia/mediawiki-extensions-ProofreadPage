var ImagePanel = require( './PagelistInputWidget.ImagePanel.js' );
var WikitextFormPanel = require( './PagelistInputWidget.WikitextFormPanel.js' );
var TopPanel = require( './PagelistInputWidget.TopPanel.js' );
/**
 * Main view for Wikisource Pagelist Dialog
 *
 * @param {mw.proofreadpage.PagelistInputWidget.DialogModel} dialogModel
 * @param {mw.proofreadpage.PagelistInputWidget.PagelistPreview} preview
 * @param {Object} config Configuration variable for PanelLayout
 * @class
 */
function MainPanel( dialogModel, preview, config ) {
	this.pagelistPreview = preview;
	this.previewPanel = new OO.ui.PanelLayout( {
		classes: [ 'prp-pagelist-dialog-preview-panel' ],
		padded: true
	} );
	this.previewPanel.$element.append( this.pagelistPreview.$element );
	this.menuPanel = this.pagelistPreview;
	this.pagelistPreview.connect( this, {
		pageselected: 'onSetData'
	} );
	this.dialogModel = dialogModel;
	this.imagePanel = new ImagePanel( this.dialogModel );
	this.topPanel = new TopPanel( this.dialogModel );
	this.formPanel = new WikitextFormPanel( this.dialogModel );
	this.rightStackLayout = new OO.ui.StackLayout( {
		items: [
			this.formPanel,
			this.previewPanel
		],
		continuous: true,
		classes: [ 'prp-pagelist-dialog-stack-layout' ]
	} );
	this.leftStackLayout = new OO.ui.StackLayout( {
		items: [
			this.topPanel,
			this.imagePanel
		],
		continuous: true,
		classes: [ 'prp-pagelist-dialog-stack-layout' ]
	} );
	config = config || {};
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-main-panel' ) ) ||
		[ 'prp-pagelist-dialog-main-panel' ];
	MainPanel.super.call( this, config );
	this.$element.append( this.leftStackLayout.$element, this.rightStackLayout.$element );
}

OO.inheritClass( MainPanel, OO.ui.PanelLayout );

/**
 * Intialize/Reintializes the dialogModel object
 *
 * @param  {OO.ui.OptionWidget} selectedOption
 */
MainPanel.prototype.onSetData = function ( selectedOption ) {
	var data = selectedOption.getData() || {};
	this.dialogModel.setData( data );
};

module.exports = MainPanel;
