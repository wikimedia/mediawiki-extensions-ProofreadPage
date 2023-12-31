const ImagePanel = require( './PagelistInputWidget.ImagePanel.js' );
const WikitextFormPanel = require( './PagelistInputWidget.WikitextFormPanel.js' );
const VisualFormPanel = require( './PagelistInputWidget.VisualFormPanel.js' );
const TopPanel = require( './PagelistInputWidget.TopPanel.js' );
/**
 * Main view for Wikisource Pagelist Dialog
 *
 * @param {boolean} mode Whether the user is in visual mode
 * @param {mw.proofreadpage.PagelistInputWidget.WikitextDialogModel} wikitextDialogModel
 * @param {mw.proofreadpage.PagelistInputWidget.VisualDialogModel} visualDialogModel
 * @param {mw.proofreadpage.PagelistInputWidget.PagelistPreview} preview
 * @param {Object} config Configuration variable for PanelLayout
 * @class
 */
function MainPanel( mode, wikitextDialogModel, visualDialogModel, preview, config ) {
	this.visualMode = mode;
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
	this.wikitextDialogModel = wikitextDialogModel;
	this.visualDialogModel = visualDialogModel;
	this.dialogModel = this.visualMode ? this.visualDialogModel : this.wikitextDialogModel;
	// Note: We aren't going to update the dialogModel for TopPanel and ImagePanel
	// since both of them are dependent on methods of the parent class, DialogModel
	this.imagePanel = new ImagePanel( this.dialogModel );
	this.topPanel = new TopPanel( this.dialogModel );
	this.wikitextFormPanel = new WikitextFormPanel( this.wikitextDialogModel );
	this.visualFormPanel = new VisualFormPanel( this.visualDialogModel );
	this.formPanel = this.visualMode ? this.visualFormPanel : this.wikitextFormPanel;
	this.rightStackLayout = new OO.ui.StackLayout( {
		items: [
			this.formPanel,
			this.previewPanel
		],
		continuous: true,
		classes: [ 'prp-pagelist-dialog-stack-layout', 'prp-pagelist-dialog-right-panel' ]
	} );
	this.leftStackLayout = new OO.ui.StackLayout( {
		items: [
			this.topPanel,
			this.imagePanel
		],
		continuous: true,
		classes: [ 'prp-pagelist-dialog-stack-layout', 'prp-pagelist-dialog-left-panel' ]
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
	const data = selectedOption.getData() || {};
	// set data on only the model in use
	if ( this.visualMode ) {
		this.visualDialogModel.setData( data );
	} else {
		this.wikitextDialogModel.setData( data );
	}
};

MainPanel.prototype.changeEditMode = function ( mode ) {
	this.visualMode = mode;
	this.dialogModel = mode ? this.visualDialogModel : this.wikitextDialogModel;
	this.formPanel = mode ? this.visualFormPanel : this.wikitextFormPanel;
	this.rightStackLayout.clearItems();
	this.rightStackLayout.addItems( [ this.formPanel, this.previewPanel ] );
};

module.exports = MainPanel;
