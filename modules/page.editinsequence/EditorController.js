var PagelistModel = require( './PagelistModel.js' );
var PageModel = require( './PageModel.js' );
var PreviewWidget = require( './PreviewWidget.js' );
var SaveOptionsModel = require( './SaveOptionsModel.js' );

/**
 * Preforms actions that require controlling with the editing interface
 *
 * @class
 * @param {jQuery} $content The content element of the document
 * @param {PageModel} pageModel PageModel associated with the toolbar
 * @param {PagelistModel} pagelistModel Pagelist model for associated toolbar
 * @param {SaveOptionsModel} saveModel Save Model associated with the toolbar
 */
function EditorController( $content, pageModel, pagelistModel, saveModel ) {
	OO.EventEmitter.call( this, null );
	this.$header = $content.find( '#wpHeaderTextbox' );
	this.$body = $content.find( '#wpTextbox1' );
	this.$footer = $content.find( '#wpFooterTextbox' );
	this.$heading = $content.find( '#firstHeadingTitle' );
	this.$editorArea = $content.find( '.prp-page-content' );

	// Remove the actionable items from the default interface
	$content.find( '.editCheckboxes' ).hide();
	$content.find( '#wpSummaryLabel' ).hide();
	$content.find( '.editButtons' ).hide();

	this.saveModel = saveModel;
	this.pagelistModel = pagelistModel;
	this.api = new mw.Api();
	this.previewWidget = new PreviewWidget();
	this.previewWidget.$element.insertAfter( this.$editorArea );
	this.pageModel = pageModel;
	this.pageModel.on( 'pageModelUpdated', this.onPageModelUpdated.bind( this ) );
	this.pageModel.on( 'pageStatusChanged', this.updatePreview.bind( this ) );
}

OO.mixinClass( EditorController, OO.EventEmitter );

/**
 * Event handler for pageModelUpdated
 *
 * @see PageModel.js
 */
EditorController.prototype.onPageModelUpdated = function () {
	if ( this.pageModel.getExists() ) {
		this.updateEditorData();
	} else {
		this.setDefaultEditorData();
	}
	this.updatePreview();
};

/**
 * Update the editor interface with data from pageModel
 */
EditorController.prototype.updateEditorData = function () {
	var editorData = this.pageModel.getEditorData();
	this.$heading.text( this.pageModel.getPageName() );
	this.$header.val( editorData.header );
	this.$body.val( editorData.body );
	this.$footer.val( editorData.footer );
};

/**
 * Update the editor interface with default data (empty)
 */
EditorController.prototype.setDefaultEditorData = function () {
	this.$heading.text( this.pageModel.getPageName() );
	this.$header.val( '' );
	this.$body.val( '' );
	this.$footer.val( '' );
};

/**
 * Builds wikitext from supplied header, body, footer, pageStatus values supplied
 *
 * @param {string} header
 * @param {string} body
 * @param {string} footer
 * @param {string} pageStatus
 * @return {string} wikiText
 */
EditorController.prototype.getWikitext = function ( header, body, footer, pageStatus ) {
	var wikitext = '<noinclude><pagequality level="$level" user="$user" />$header</noinclude>$body<noinclude>$footer</noinclude>';
	wikitext = wikitext.replace( '$header', header );
	wikitext = wikitext.replace( '$body', body );
	wikitext = wikitext.replace( '$footer', footer );
	wikitext = wikitext.replace( '$level', pageStatus.status );
	wikitext = wikitext.replace( '$user', pageStatus.lastUser );
	return wikitext;
};

/**
 * Updates the previews of a page with the latest preview, if the preview is not being shown
 * this function is a no-op
 */
EditorController.prototype.updatePreview = function () {
	this.previewWidget.updatePreview( this.getWikitext( this.$header.val(), this.$body.val(), this.$footer.val(), this.pageModel.getPageStatus() ), this.pageModel.getPageName() );
};
/**
 * Shows the preview
 */
EditorController.prototype.showPreview = function () {
	this.previewWidget.showPreview();
	this.updatePreview();
	this.$editorArea.hide();
};

/**
 * Hides the preview
 */
EditorController.prototype.hidePreview = function () {
	this.previewWidget.hidePreview();
	this.$editorArea.show();
};

/**
 * Saves a edit based on the global save options model metadata
 *
 * @return {jQuery.Deffered} Promise that resolves when the edit is saved
 */

EditorController.prototype.save = function () {
	var saveData = this.saveModel.getSaveData(),
		wikiText = this.getWikitext( this.$header.val(), this.$body.val(), this.$footer.val(), this.pageModel.getPageStatus() ),
		pageName = this.pageModel.getPageName();
	return this.api.postWithToken( 'csrf', {
		action: 'edit',
		title: pageName,
		text: wikiText,
		summary: saveData.editSummary,
		minor: saveData.isMinorEdit,
		watch: saveData.shouldWatchlist ? 'watch' : 'preferences',
		format: 'json'
	} ).then( function ( result ) {
		if ( result && result.edit && result.edit.result ) {
			mw.config.set( 'wgPostEdit', 'saved' );
			// The following messages are used here:
			// * postedit-confirmation-published
			// * postedit-confirmation-saved
			mw.hook( 'postEdit' ).fire();
			switch ( this.saveModel.getAfterSaveAction() ) {
				case 'go-to-next':
					this.pagelistModel.next();
					break;
				case 'go-to-previous':
					this.pagelistModel.prev();
					break;
			}
		}

		return result;
	}.bind( this ) );
};

module.exports = EditorController;
