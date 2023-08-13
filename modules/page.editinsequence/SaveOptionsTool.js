/**
 * Implements a advanced menu that can be used to configure
 * the save mechanism when the save dialog is not shown on every save.
 *
 * @class
 */
function SaveOptionsTool() {
	SaveOptionsTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-saveoptions' );
	this.toggle( !mw.user.options.get( 'proofreadpage-show-dialog-before-every-save' ) );
	this.toolbar.eis.saveOptionsModel.on( 'dialogBeforeSaveChange', this.onDialogBeforeSaveChange.bind( this ) );
}

OO.inheritClass( SaveOptionsTool, OO.ui.Tool );

SaveOptionsTool.static.name = 'saveOptions';
// eslint-disable-next-line es-x/no-regexp-prototype-flags
SaveOptionsTool.static.flags = [ 'primary', 'progressive' ];
SaveOptionsTool.static.icon = 'settings';

/**
 * @inheritdoc
 */
SaveOptionsTool.prototype.onSelect = function () {
	this.setActive( false );
	this.toolbar.eis.openSaveDialog();
};

/**
 * Toggle the visibilty of the tool when the dialog is shown on every save
 *
 * @param {boolean} val Toggle status
 */
SaveOptionsTool.prototype.onDialogBeforeSaveChange = function ( val ) {
	this.toggle( !val );
};

/**
 * @inheritdoc
 */
SaveOptionsTool.prototype.onUpdateState = function () {};

module.exports = SaveOptionsTool;
