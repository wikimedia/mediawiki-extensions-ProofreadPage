const PagelistModel = require( './PagelistModel.js' );
const PageModel = require( './PageModel.js' );
const PreviewWidget = require( './PreviewWidget.js' );
const SaveOptionsModel = require( './SaveOptionsModel.js' );

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
	this.$headingContainer = $content.find( '#firstHeading' );
	this.$editorArea = $content.find( '.prp-page-content' );
	this.$header.on( 'keyup change', this.onHeaderChange.bind( this ) );
	this.$body.on( 'keyup change', this.onBodyChange.bind( this ) );
	this.$footer.on( 'keyup change', this.onFooterChange.bind( this ) );
	mw.hook( 'ext.CodeMirror.switch' ).add( this.onCodeMirrorSwitch.bind( this ) );

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
	this.pageModel.connect( this, {
		pageModelUpdated: 'onPageModelUpdated',
		pageStatusChanged: 'updatePreview',
		loadUnsavedEdit: 'onLoadUnsavedEdit'
	} );
	this.addUnsavedEditIndicator();
	// Prevent beforeunload from firing when we are saving and instead
	$( window ).off( 'beforeunload' );
	if ( mw.confirmCloseWindow ) {
		mw.confirmCloseWindow( { test: this.onCloseWindow.bind( this ) } );
	}
}

OO.mixinClass( EditorController, OO.EventEmitter );

/**
 * Depending on whether or not codemirror is used, switch update mechanism. For CodeMirror
 * we use the DOMNodeInserted and DOMNodeRemoved events to detect changes instead of the
 * 'change' events as in a normal textarea.
 *
 * @param {boolean} enabled Status of CodeMirror
 * @param {jQuery} $textbox textbox corresponding to the CodeMirror extension
 */
EditorController.prototype.onCodeMirrorSwitch = function ( enabled, $textbox ) {
	const $oldBody = this.$body;
	this.$body = $textbox;
	if ( enabled ) {
		this.$body.on( 'DOMNodeInserted DOMNodeRemoved', this.onBodyChange.bind( this ) );
		$oldBody.off( 'keyup' );
	} else {
		this.$body.on( 'keyup', this.onBodyChange.bind( this ) );
		$oldBody.off( 'DOMNodeInserted DOMNodeRemoved' );
	}
};

/**
 *Event handler for when the page is navigated away from
 *
 * @return {boolean} False all the time to prevent mw.confirmCloseWindow from firing
 */
EditorController.prototype.onCloseWindow = function () {
	this.pageModel.savePage();
	return true;
};

EditorController.prototype.addUnsavedEditIndicator = function () {
	document.title = '* ' + document.title;
	this.unsavedEditBadge = new OO.ui.PopupButtonWidget( {
		icon: 'notice',
		framed: false,
		classes: [ 'prp-eis-unsaved-edit-badge' ],
		invisibleLabel: true,
		popup: {
			$content: $( '<div>' ).text( mw.msg( 'prp-editinsequence-unsaved-edit-message' ) ),
			padded: true
		}
	} );
	this.unsavedEditBadge.toggle( false );
	this.$headingContainer.before( this.unsavedEditBadge.$element );
};

EditorController.prototype.toggleUnsavedEditIndicator = function ( show ) {
	if ( show && document.title.charAt( 0 ) === '*' ) {
		document.title = document.title.slice( 2 );
	} else {
		document.title = '* ' + document.title;
	}
	this.unsavedEditBadge.toggle( show );
};

/**
 * Show a notification and handling discarding edit when a unsaved edit
 * is loaded.
 */
EditorController.prototype.onLoadUnsavedEdit = function () {
	const editorData = this.pageModel.getEditorData();
	this.$header.val( editorData.header );
	this.$body.textSelection( 'setContents', editorData.body );
	this.$footer.val( editorData.footer );
	this.toggleUnsavedEditIndicator( this.pageModel.hasChanges() );
};

EditorController.prototype.onHeaderChange = function () {
	this.pageModel.setHeader( this.$header.val() );
	this.toggleUnsavedEditIndicator( this.pageModel.hasChanges() );
};

EditorController.prototype.onBodyChange = function () {
	this.pageModel.setBody( this.$body.textSelection( 'getContents' ) );
	this.toggleUnsavedEditIndicator( this.pageModel.hasChanges() );
};

EditorController.prototype.onFooterChange = function () {
	this.pageModel.setFooter( this.$footer.val() );
	this.toggleUnsavedEditIndicator( this.pageModel.hasChanges() );
};

/**
 * Event handler for pageModelUpdated
 *
 * @see PageModel.js
 */
EditorController.prototype.onPageModelUpdated = function () {
	this.toggleUnsavedEditIndicator( false );
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
	const editorData = this.pageModel.getInitialEditorData();
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
 * Sync current header, body and footer with page model
 * (required to update programattic changes to page model)
 * should be invoked before important user-facing operations
 * such as diff, preview and save
 */
EditorController.prototype.forceSync = function () {
	this.pageModel.setHeader( this.$header.val() );
	this.pageModel.setBody( this.$body.textSelection( 'getContents' ) );
	this.pageModel.setFooter( this.$footer.val() );
	this.onFooterChange();
};

/**
 * Updates the previews of a page with the latest preview, if the preview is not being shown
 * this function is a no-op
 */
EditorController.prototype.updatePreview = function () {
	this.forceSync();
	this.previewWidget.updatePreview( this.pageModel.getCurrentWikitext(), this.pageModel.getPageName() );
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
	this.forceSync();
	this.toggleUnsavedEditIndicator( false );
	const saveData = this.saveModel.getSaveData(),
		wikiText = this.pageModel.getCurrentWikitext(),
		pageName = this.pageModel.getPageName();
	return this.api.postWithToken( 'csrf', {
		action: 'edit',
		title: pageName,
		text: wikiText,
		summary: saveData.editSummary,
		minor: saveData.isMinorEdit,
		watch: saveData.shouldWatchlist ? 'watch' : 'preferences',
		format: 'json'
	}, {
		headers: {
			'X-User-Agent': 'EditInSequence'
		}
	} ).then( ( result ) => {
		if ( result && result.edit && result.edit.result ) {
			this.pageModel.setInitialPageDataToCurrent();
			this.pagelistModel.setPageStatus( this.pageModel.getPageStatus().status );
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
	} );
};

module.exports = EditorController;
