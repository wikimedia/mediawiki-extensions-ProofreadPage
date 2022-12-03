/**
 * PreviewTool implements the Preview button on the EditInSequence toolbar
 */
function PreviewTool() {
	PreviewTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-preview' );
	this.isEnabled = false;
}

OO.inheritClass( PreviewTool, OO.ui.Tool );

PreviewTool.static.name = 'preview';
PreviewTool.static.icon = 'eye';
PreviewTool.static.title = OO.ui.deferMsg( 'prp-editinsequence-preview' );
PreviewTool.static.displayBothIconAndLabel = true;

/**
 * @inheritdoc
 */
PreviewTool.prototype.onSelect = function () {
	this.isEnabled = !this.isEnabled;
	this.setActive( this.isEnabled );
	if ( this.isEnabled ) {
		this.toolbar.eis.editorController.showPreview();
	} else {
		this.toolbar.eis.editorController.hidePreview();
	}
};

/**
 * @inheritdoc
 */
PreviewTool.prototype.onUpdateState = function () {};

module.exports = PreviewTool;
