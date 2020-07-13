var ImagePanel = require( './PagelistInputWidget.ImagePanel.js' );
var FormPanel = require( './PagelistInputWidget.FormPanel.js' );
/**
 * Main view for Wikisource Pagelist Dialog
 *
 * @param {mw.proofreadpage.PagelistInputWidget.PageModel} pageModel
 * @param {mw.proofreadpage.PagelistInputWidget.PagelistPreview} preview
 * @param {Object} config Configuration variable for PanelLayout
 * @class
 */
function MainPanel( pageModel, preview, config ) {
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
	this.pageModel = pageModel;
	this.imagePanel = new ImagePanel( this.pageModel );
	this.formPanel = new FormPanel( this.pageModel );
	this.stackLayout = new OO.ui.StackLayout( {
		items: [
			this.formPanel,
			this.previewPanel
		],
		continuous: true,
		classes: [ 'prp-pagelist-dialog-stack-layout' ]
	} );
	config = config || {};
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-main-panel' ) ) ||
		[ 'prp-pagelist-dialog-main-panel' ];
	MainPanel.super.call( this, config );
	this.$element.append( this.imagePanel.$element, this.stackLayout.$element );
}

OO.inheritClass( MainPanel, OO.ui.PanelLayout );

/**
 * Intialize/Reintializes the pageModel object
 *
 * @param  {OO.ui.OptionWidget} selectedOption
 */
MainPanel.prototype.onSetData = function ( selectedOption ) {
	var data = selectedOption.getData() || {};
	this.pageModel.setData( data );
};

module.exports = MainPanel;
