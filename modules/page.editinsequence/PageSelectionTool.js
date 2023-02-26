/**
 * Implements the button used to toggle the page selection UI
 *
 * @class
 * @extends OO.ui.Tool
 */
function PageSelectionTool() {
	PageSelectionTool.super.apply( this, arguments );
	this.selected = false;
}

OO.inheritClass( PageSelectionTool, OO.ui.Tool );

PageSelectionTool.static.name = 'pageSelection';
PageSelectionTool.static.title = mw.msg( 'prp-editinsequence-page-selection-label' );
PageSelectionTool.static.icon = 'arrowNext';
PageSelectionTool.static.displayBothIconAndLabel = true;

/**
 * @inheritdoc
 */
PageSelectionTool.prototype.onUpdateState = function () {
};

PageSelectionTool.prototype.onSelect = function () {
	this.selected = !this.selected;
	this.setActive( this.selected );
	this.toolbar.eis.pageSelectionLayout.toggle();
};

module.exports = PageSelectionTool;
