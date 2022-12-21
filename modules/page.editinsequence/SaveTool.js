/**
 * Implements the save changes button
 *
 * @class
 */
function SaveTool() {
	SaveTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-save' );
}

OO.inheritClass( SaveTool, OO.ui.Tool );

SaveTool.static.name = 'save';

if ( mw.config.get( 'wgEditSubmitButtonLabelPublish' ) ) {
	SaveTool.static.title = OO.ui.deferMsg( 'publishchanges' );
} else {
	SaveTool.static.title = OO.ui.deferMsg( 'savechanges' );
}
SaveTool.static.flags = [ 'primary', 'progressive' ];
SaveTool.static.icon = 'article';
SaveTool.static.displayBothIconAndLabel = true;

/**
 * @inheritdoc
 */
SaveTool.prototype.onSelect = function () {
	this.setActive( false );
	if ( this.toolbar.eis.saveOptionsModel.getDialogBeforeSave() || !this.toolbar.eis.saveOptionsModel.getIsInitialized() ) {
		this.toolbar.eis.openSaveDialog();
		this.toolbar.eis.saveOptionsModel.setIntialized();
	} else {
		this.toolbar.eis.editorController.save();

	}
};

/**
 * @inheritdoc
 */
SaveTool.prototype.onUpdateState = function () {};

module.exports = SaveTool;
