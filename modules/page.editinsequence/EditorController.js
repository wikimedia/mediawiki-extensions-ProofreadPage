var PageModel = require( './PageModel.js' );

/**
 * Preforms actions that require controlling with the editing interface
 *
 * @class
 * @param {jQuery} $content The content element of the document
 * @param {PageModel} pageModel PageModel associated with the toolbar
 */
function EditorController( $content, pageModel ) {
	this.$header = $content.find( '#wpHeaderTextbox' );
	this.$body = $content.find( '#wpTextbox1' );
	this.$footer = $content.find( '#wpFooterTextbox' );
	this.$heading = $content.find( '#firstHeadingTitle' );
	this.pageModel = pageModel;
	this.pageModel.on( 'pageModelUpdated', this.onPageModelUpdated.bind( this ) );
}

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

module.exports = EditorController;
