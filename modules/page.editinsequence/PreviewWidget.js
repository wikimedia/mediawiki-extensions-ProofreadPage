const preview = require( 'mediawiki.page.preview' );

/**
 * Encapsulates the previewing functionality exposed via the EditorController interface
 *
 * @param {Object} config Configuration variable
 */
function PreviewWidget( config ) {
	config = Object.assign( { scrollable: true }, config );
	PreviewWidget.super.call( this, config );
	OO.ui.mixin.PendingElement.call( this );
	this.$fullText = $( '<textarea>' );
	this.$previewArea = $( '<div>' );
	this.$previewArea.hide();
	this.errorMessageWidget = new OO.ui.MessageWidget();
	this.errorMessageWidget.toggle( false );
	this.$element.append( this.errorMessageWidget.$element );
	this.$previewArea.addClass( 'prp-edit-in-sequence-preview-wrapper' );
	this.$element.addClass( 'prp-edit-in-sequence-preview-area' );
	this.$element.append( this.$previewArea );
	this.isPreviewShown = false;
	this.toggle( false );
}

OO.inheritClass( PreviewWidget, OO.ui.PanelLayout );
OO.mixinClass( PreviewWidget, OO.ui.mixin.PendingElement );

/**
 * @param {string} wikitext Wikitext to be previewed
 * @param {string} pagename Current page name
 * Updates the previews of a page with the latest preview, if the preview is not being shown
 * this function is a no-op
 */
PreviewWidget.prototype.updatePreview = function ( wikitext, pagename ) {
	if ( this.isPreviewShown ) {
		this.errorMessageWidget.toggle( false );
		this.$previewArea.empty();
		this.$fullText.val( wikitext );
		preview.doPreview( {
			title: pagename,
			$previewNode: this.$previewArea,
			$textareaNode: this.$fullText
		} ).then( () => {
			this.popPending();
			this.$previewArea.show();
		} ).catch( ( err, response ) => {
			if ( err !== 'http' ) {
				this.showError( 'prp-edit-in-sequence-preview-api-error', response.error.info );
			} else {
				this.showError( 'prp-edit-in-sequence-preview-http-error' );
			}
			this.$previewArea.hide();
			this.popPending();
		} );
	}
};

PreviewWidget.prototype.showError = function () {
	this.errorMessageWidget.setLabel( mw.msg.apply( null, arguments ) );
	this.errorMessageWidget.toggle( true );
};

/**
 * @param {string} wikitext
 * Shows the preview
 */
PreviewWidget.prototype.showPreview = function () {
	this.isPreviewShown = true;
	this.toggle( true );
	this.pushPending();
};

/**
 * Hides the preview
 */
PreviewWidget.prototype.hidePreview = function () {
	this.isPreviewShown = false;
	this.toggle( false );
	this.$previewArea.hide();
};

module.exports = PreviewWidget;
