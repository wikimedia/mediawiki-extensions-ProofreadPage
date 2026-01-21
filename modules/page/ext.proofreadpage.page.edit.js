const OpenSeadragonController = require( './OpenseadragonController.js' );
const PageQualityInputWidget = require( './PageQualityInputWidget.js' );

( function () {
	'use strict';

	mw.proofreadpage = {};
	let
		/**
		 * Is the layout horizontal (ie is the scan image on top of the edit area)
		 *
		 * @type {boolean}
		 */
		isLayoutHorizontal = false,

		/**
		 * Are the header/footer fields visible?
		 *
		 * @type {boolean}
		 */
		headersVisible = true,

		/**
		 * The edit form
		 *
		 * @type {jQuery}
		 */
		$editForm,

		/**
		 * OSD parent container, when the page is in "vertical" mode, and
		 * the image viewer is inside the edit form
		 *
		 * @type {jQuery}
		 */
		$imgContVertical,

		/**
		 * OSD parent container, when the page is in "horizontal" mode, and
		 * the image viewer is outside the edit form
		 *
		 * @type {jQuery}
		 */
		$imgContHorizontal,

		/**
		 * OSD Controller, wraps Openseadragon functionality inside a easy to use interface
		 */
		osdController = null,

		/**
		 * Has the wikitext editor UI been updated?
		 *
		 * @type {boolean}
		 */
		editorUiUpdated = false;

	/**
	 * Returns the value of a user option as boolean
	 *
	 * @param {string} optionId
	 * @return {boolean}
	 */
	function getBooleanUserOption( optionId ) {
		return Number( mw.user.options.get( optionId ) ) === 1;
	}

	/**
	 * Sets the value of a user option based on input
	 *
	 * @param {string} optionId name of user option
	 * @param {boolean|null} value value to be set
	 */
	function setBooleanUserOption( optionId, value ) {
		const convertedValue = value ? 1 : 0;
		if ( getBooleanUserOption( optionId ) !== value ) {
			mw.user.options.set( optionId, convertedValue );
			new mw.Api().saveOption( optionId, convertedValue );
		}
	}

	/**
	 * Show or hide header and footer areas
	 *
	 * @param {boolean} [visible] Visibility, inverts if undefined
	 * @param {string} [speed] Speed of the toggle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( visible, speed ) {
		headersVisible = visible === undefined ? !headersVisible : visible;

		const method = headersVisible ? 'show' : 'hide';
		$editForm.find( '.prp-page-edit-header' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-body label.prp-page-edit-area-label' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-footer' )[ method ]( speed );

		// eslint-disable-next-line no-jquery/no-global-selector
		const setActive = $( '.tool[rel=toggle-visibility]' ).data( 'setActive' );
		if ( setActive ) {
			setActive( headersVisible );
		}

		setBooleanUserOption( 'proofreadpage-showheaders', headersVisible );
	}

	/**
	 * Put the scan image on top or on the left of the edit area
	 *
	 * @param {boolean} [horizontal] Use horizontal layout, inverts if undefined
	 */
	function toggleLayout( horizontal ) {

		isLayoutHorizontal = horizontal === undefined ? !isLayoutHorizontal : horizontal;

		// Record current layout status on both layout elements, for CSS convenience.
		const $layoutParts = $imgContHorizontal.add( $editForm.find( '.prp-page-container' ) );
		$layoutParts.toggleClass( 'prp-layout-is-vertical', !isLayoutHorizontal );
		$layoutParts.toggleClass( 'prp-layout-is-horizontal', isLayoutHorizontal );

		const idToInitialize = isLayoutHorizontal ?
			'prp-page-image-openseadragon-horizontal' :
			'prp-page-image-openseadragon-vertical';
		osdController.initialize( idToInitialize );

		// eslint-disable-next-line no-jquery/no-global-selector
		const setActive = $( '.tool[rel=toggle-layout]' ).data( 'setActive' );
		if ( setActive ) {
			setActive( isLayoutHorizontal );
		}

		setBooleanUserOption( 'proofreadpage-horizontal-layout', isLayoutHorizontal );
	}

	/**
	 * Apply user preferences
	 */
	function setupPreferences() {
		toggleHeaders( getBooleanUserOption( 'proofreadpage-showheaders' ) );
		toggleLayout( getBooleanUserOption( 'proofreadpage-horizontal-layout' ) );
	}

	/**
	 * Set up the editing interface
	 */
	function setupWikitextEditor() {
		const zoomInterface = {
			zoomIn: {
				type: 'element',
				element: new OO.ui.ButtonWidget( {
					id: 'prp-page-zoomIn',
					icon: 'zoomIn',
					framed: false
				} ).$element
			},
			zoomOut: {
				type: 'element',
				element: new OO.ui.ButtonWidget( {
					id: 'prp-page-zoomOut',
					icon: 'zoomOut',
					framed: false
				} ).$element
			},
			zoomReset: {
				type: 'element',
				element: new OO.ui.ButtonWidget( {
					id: 'prp-page-zoomReset',
					icon: 'zoomReset',
					framed: false
				} ).$element
			},
			rotateLeft: {
				type: 'element',
				element: new OO.ui.ButtonWidget( {
					id: 'prp-page-rotateLeft',
					icon: 'undo',
					framed: false
				} ).$element
			},
			rotateRight: {
				type: 'element',
				element: new OO.ui.ButtonWidget( {
					id: 'prp-page-rotateRight',
					icon: 'redo',
					framed: false
				} ).$element
			}
		};

		const $wpTextbox = $editForm.find( '#wpTextbox1' );
		$wpTextbox.wikiEditor( 'addToToolbar', {
			section: 'secondary',
			groups: {
				'proofreadpage-secondary': {
					tools: zoomInterface
				}
			}
		} );

		const tools = {
			other: {
				label: mw.msg( 'proofreadpage-group-other' ),
				tools: {
					'toggle-visibility': {
						label: mw.msg( 'proofreadpage-button-toggle-visibility-label' ),
						type: 'button',
						oouiIcon: 'headerFooter',
						action: {
							type: 'callback',
							execute: function () {
								toggleHeaders( undefined, 'fast' );
							}
						}
					},
					'toggle-layout': {
						label: mw.msg( 'proofreadpage-button-toggle-layout-label' ),
						type: 'button',
						oouiIcon: 'switchLayout',
						action: {
							type: 'callback',
							execute: toggleLayout.bind( this, undefined )
						}
					}
				}
			}
		};

		updateEditorUi();

		$wpTextbox.wikiEditor( 'addToToolbar', {
			sections: {
				'proofreadpage-tools': {
					type: 'toolbar',
					label: mw.msg( 'proofreadpage-section-tools' ),
					groups: tools
				}
			}
		} );
	}

	/**
	 * Update the wikitext editor UI.
	 *
	 * This function is called when the wikitext editor is initialized,
	 * or if applicable, just before CodeMirror is added to the DOM.
	 */
	function updateEditorUi() {
		if ( editorUiUpdated ) {
			return;
		}
		editorUiUpdated = true;
		$editForm.find( '.prp-page-edit-body' ).append( $editForm.find( '#wpTextbox1' ) );
		$editForm.find( '.editOptions' ).before( $editForm.find( '.wikiEditor-ui' ) );
		$editForm.find( '.wikiEditor-ui-text' ).append( $editForm.find( '.prp-page-container' ) );
	}

	/**
	 * Add CodeMirror to edit form.
	 */
	function setupCodeMirror() {
		let initialCMInstance = true;
		// Called just before CodeMirror initialization.
		mw.hook( 'ext.CodeMirror.initialize' ).add( () => {
			// Don't run again for the new CM instances we'll create later.
			if ( !initialCMInstance ) {
				return;
			}
			initialCMInstance = false;

			// Ensure DOM manipulations are finished before CodeMirror is initialized.
			updateEditorUi();

			// Add CodeMirror instances to the header and footer once the main instance is ready.
			mw.hook( 'ext.CodeMirror.ready' ).add( addCodeMirrorToHeaders );
		} );
	}

	/**
	 * @typedef CodeMirror
	 * @see https://doc.wikimedia.org/CodeMirror/master/js/js/CodeMirror.html
	 */

	/**
	 * Add CodeMirror instances to the header and footer.
	 *
	 * @param {CodeMirror} mainInstance
	 */
	function addCodeMirrorToHeaders( mainInstance ) {
		// Don't run again for the new CM instances we're about to add.
		mw.hook( 'ext.CodeMirror.ready' ).remove( addCodeMirrorToHeaders );
		// eslint-disable-next-line new-cap
		new mainInstance.child( document.getElementById( 'wpHeaderTextbox' ), mainInstance ).initialize();
		// eslint-disable-next-line new-cap
		new mainInstance.child( document.getElementById( 'wpFooterTextbox' ), mainInstance ).initialize();
		// Force line wrapping since Proofread Page uses flexbox for layout (T380262#10907952).
		mainInstance.preferences.lockPreference( 'lineWrapping', mainInstance.view, true );
	}

	/**
	 * Init global variables of the script
	 */
	function initEnvironment() {
		if ( $editForm === undefined ) {
			// eslint-disable-next-line no-jquery/no-global-selector
			$editForm = $( '#editform' );
		}
		$editForm.find( '#wpTextbox1' ).removeAttr( 'rows cols' );

		if ( $imgContVertical === undefined ) {
			$imgContVertical = $( '<div>' )
				// We'd rather not have the ID, but OSD requires it.
				.addClass( 'prp-page-image-openseadragon-vertical' )
				.attr( 'id', 'prp-page-image-openseadragon-vertical' )
				.appendTo( '.prp-page-image' );
		}

		if ( $imgContHorizontal === undefined ) {
			$imgContHorizontal = $( '<div>' )
				.addClass( 'prp-page-image-openseadragon-horizontal' )
				.attr( 'id', 'prp-page-image-openseadragon-horizontal' )
				.insertBefore( $editForm );
		}
	}

	$( () => {
		// eslint-disable-next-line no-jquery/no-global-selector
		const $img = $( '.prp-page-image' ).find( 'img' );

		osdController = new OpenSeadragonController( $img, getBooleanUserOption( 'usebetatoolbar' ) );
		if ( $img.length ) {
			mw.proofreadpage.openseadragon = osdController;
			mw.hook( 'ext.proofreadpage.osd-controller-available' ).fire( osdController );
			// TODO(sohom): Keeping this event for backward compatibility.
			// will remove once community scripts have been updated.
			mw.hook( 'ext.proofreadpage.osd-viewer-ready' ).fire( osdController.viewer );
		} else {
			mw.notify( mw.msg( 'proofreadpage-openseadragon-no-image-found' ), {
				type: 'error',
				autoHide: false
			} );
			mw.hook( 'ext.proofreadpage.osd-no-image-found' );
		}

		initEnvironment();
		$img.hide();

		// Set up page quality widget.
		/**
		 * @internal not stable for use.
		 */
		mw.proofreadpage.PageQualityInputWidget = PageQualityInputWidget;
		$editForm.find( '.prp-pageQualityInputWidget' ).each( function () {
			OO.ui.infuse( this );
		} );

		// Set up the preferences (header/footer and horizontal/vertical layout)
		// when WikiEditor is active as well as when it's not.
		mw.hook( 'wikiEditor.toolbarReady' ).add( () => {
			setupWikitextEditor();
			setupPreferences();
			setupCodeMirror();
		} );

		// Initialize the height of the text UI to match what the resizing bar has stored.
		mw.hook( 'ext.WikiEditor.resize' ).add( ( resizingBar ) => {
			$editForm.find( '.wikiEditor-ui-text' ).css( 'height', resizingBar.getResizedPane().height() );
		} );

		if ( !getBooleanUserOption( 'usebetatoolbar' ) ) {
			setupPreferences();
			setupCodeMirror();
		}
	} );

}() );
