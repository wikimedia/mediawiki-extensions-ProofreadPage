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
// eslint-disable-next-line es-x/no-regexp-prototype-flags
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
		this.toolbar.eis.editorController.save().always( function ( result, errorResult ) {
			if ( errorResult && errorResult.error ) {
				mw.notify( errorResult.error.info,
					{ title: mw.msg( 'prp-editinsequence-could-not-save-edit' ), type: 'error' } );
			}
		} );

	}
};

/**
 * @inheritdoc
 */
SaveTool.prototype.onUpdateState = function () {};

module.exports = SaveTool;
