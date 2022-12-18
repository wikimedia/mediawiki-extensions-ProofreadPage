/**
 * Implements a advanced menu that can be used to configure
 * the save mechanism when the save dialog is not shown on every save.
 *
 * @class
 */
function SaveOptionsTool() {
	SaveOptionsTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-saveoptions' );
	this.toggle( false );
	this.toolbar.eis.saveOptionsModel.on( 'dialogBeforeSaveChange', this.onDialogBeforeSaveChange.bind( this ) );
}

OO.inheritClass( SaveOptionsTool, OO.ui.Tool );

SaveOptionsTool.static.name = 'saveOptions';
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
