var DialogModel = require( './PagelistInputWidget.DialogModel.js' );
/**
 * A model used to coordinate various parts of the Dialog UI.
 * The model caches changes to the wikitext while the dialog is open
 * and then syncs the changes to the mainModel once the dialog closes.
 *
 * @param {Object} data Page data
 * @param {mw.proofreadpage.PagelistInputWidget.Model} mainModel
 * @class
 */
function WikiTextDialogModel( data, mainModel ) {
	WikiTextDialogModel.super.call( this, data, mainModel );
	this.wikitext = null;
	this.mainModel.connect( this, {
		wikitextUpdated: 'updateCachedDataFromMainModel',
		enumeratedListCreated: 'pagelistPreviewGenerationDone',
		parsingerror: 'pagelistPreviewGenerationDone'
	} );
}

OO.inheritClass( WikiTextDialogModel, DialogModel );

/**
 * Syncs current model with the main model
 *
 * @param {string} wikitext
 */
WikiTextDialogModel.prototype.updateCachedDataFromMainModel = function ( wikitext ) {
	this.wikitext = wikitext;
	this.changed = false;
	this.emit( 'updateWikiText', wikitext );
};

/**
 * Updates cached wikitext and PagelistPreview.
 *
 * @param {string} wikitext
 */
WikiTextDialogModel.prototype.updateCachedData = function ( wikitext ) {
	this.wikitext = wikitext;
	this.mainModel.generateParametersFromWikitext( wikitext );
};

/**
 * Syncs main model with this model
 */
WikiTextDialogModel.prototype.setCachedData = function () {
	// Using this.changed cause that's more recent
	this.mainModel.updateWikitext( this.wikitext );
	this.changed = false;
};

/**
 * Resets cache to state before opening the dialog
 *
 * @return {boolean} Whether there is unsaved data or not
 */
WikiTextDialogModel.prototype.unloadCachedData = function () {
	var test = !this.changed;
	if ( test ) {
		this.wikitext = this.mainModel.getWikitext();
		this.changed = false;
		this.emit( 'updateWikiText', this.wikitext );
	}
	return test;
};

/**
 * Sets the value of this.changed which is used by unloadCachedData to check
 * if discarding changes is possible
 *
 * @param {boolean} value
 */
WikiTextDialogModel.prototype.setChangedFlag = function ( value ) {
	if ( typeof value === 'boolean' ) {
		this.changed = value;
	}
};

/**
 * Updates the wikitext every time the textinput changes.
 * This makes sure that whenever user clicks Insert Changes
 * they always inserts the latest changes.
 *
 * @param {string} changedText
 */
WikiTextDialogModel.prototype.setChangedCachedData = function ( changedText ) {
	this.changed = !( this.mainModel.getWikitext() === changedText );
	this.wikitext = changedText;
};

/**
 * @event pagelistGenerated
 */
WikiTextDialogModel.prototype.pagelistPreviewGenerationDone = function () {
	this.emit( 'pagelistPreviewGenerated' );
};

/**
 * @event DialogOpened
 */
WikiTextDialogModel.prototype.dialogOpened = function () {
	this.emit( 'dialogOpened' );
};

module.exports = WikiTextDialogModel;
