( function () {
	'use strict';
	var OpenSeadragon = require( 'ext.proofreadpage.openseadragon' );
	mw.proofreadpage = {};
	var
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
		 * image element
		 *
		 * @type {jQuery}
		 */
		$img,

		/**
		 * The main edit box
		 *
		 * @type {jQuery}
		 */
		$wpTextbox,

		/**
		 * the OpenSeadragon image viewer
		 *
		 * @type {Object}
		 */
		viewer = null;

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
		var convertedValue = value ? 1 : 0;
		if ( getBooleanUserOption( optionId ) !== value ) {
			mw.user.options.set( optionId, convertedValue );
			new mw.Api().saveOption( optionId, convertedValue );
		}
	}

	/**
	 * Construct ann OpenSeadragon legacy-image-pyramid tile source from the
	 * image element on the page
	 *
	 * @param {Object} img the image element
	 * @return {Object} OSD tile source constructor options
	 */
	function constructImagePyramidSource( img ) {
		var width = parseInt( img.getAttribute( 'width' ) );
		var height = parseInt( img.getAttribute( 'height' ) );

		var levels = [ {
			url: img.getAttribute( 'src' ),
			width: width,
			height: height
		} ];

		// Occasionally, there is no srcset set on the server
		var srcSet = img.getAttribute( 'srcset' );
		if ( srcSet ) {
			var parts = srcSet.split( ' ' );
			for ( var i = 0; i < parts.length - 1; i += 2 ) {
				var srcUrl = parts[ i ];
				var srcFactor = parseFloat( parts[ i + 1 ].replace( /x,?/, '' ) );

				levels.push( {
					url: srcUrl,
					width: srcFactor * width,
					height: srcFactor * height
				} );
			}
		}

		return {
			type: 'legacy-image-pyramid',
			levels: levels
		};
	}

	/**
	 * Ensure that the zoom system is properly initialized
	 *
	 * @param {string} id
	 */
	function ensureImageZoomInitialization( id ) {
		if ( !$img || $img.length === 0 ) {
			// No valid image on the page
			return;
		}

		// make space for the OSD viewer
		$img.hide();

		var tileSource = constructImagePyramidSource( $img[ 0 ] );

		// Set the OSD strings before setting up the buttons
		var osdStringMap = [
			[ 'Tooltips.Home', 'proofreadpage-button-reset-zoom-label' ],
			[ 'Tooltips.ZoomIn', 'proofreadpage-button-zoom-in-label' ],
			[ 'Tooltips.ZoomOut', 'proofreadpage-button-zoom-out-label' ],
			[ 'Tooltips.RotateLeft', 'proofreadpage-button-rotate-left-label' ],
			[ 'Tooltips.RotateRight', 'proofreadpage-button-rotate-right-label' ]
		];

		osdStringMap.forEach( function ( mapping ) {
			// eslint-disable-next-line mediawiki/msg-doc
			OpenSeadragon.setString( mapping[ 0 ], mw.msg( mapping[ 1 ] ) );
		} );

		var zoomFactor = Number( mw.user.options.get( 'proofreadpage-zoom-factor', 1.2 ) );
		var animationTime = Number( mw.user.options.get( 'proofreadpage-animation-time', 0 ) );

		var osdParams = {
			id: id,
			showFullPageControl: false,
			preserveViewport: true,
			animationTime: animationTime,
			visibilityRatio: 0.5,
			minZoomLevel: 0.5,
			maxZoomLevel: 4.5,
			zoomPerClick: zoomFactor,
			zoomPerScroll: zoomFactor,
			tileSources: tileSource
		};

		if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
			$.extend( osdParams, {
				zoomInButton: 'prp-page-zoomIn',
				zoomOutButton: 'prp-page-zoomOut',
				homeButton: 'prp-page-zoomReset',
				rotateLeftButton: 'prp-page-rotateLeft',
				rotateRightButton: 'prp-page-rotateRight',
				showRotationControl: true
			} );
		} else {
			osdParams.showNavigationControl = false;
		}

		viewer = OpenSeadragon( osdParams );

		viewer.viewport.goHome = function () {
			if ( viewer ) {
				var oldBounds = viewer.viewport.getBounds();
				var newBounds = new OpenSeadragon.Rect( 0, 0, 1, oldBounds.height / oldBounds.width );
				viewer.viewport.fitBounds( newBounds, true );
			}
		};

		viewer.addHandler( 'open', function () {
			// inform any listeners that the OSD viewer is ready
			mw.hook( 'ext.proofreadpage.osd-viewer-ready' ).fire( viewer );
		} );

		mw.proofreadpage.viewer = viewer;
	}

	/**
	 * Show or hide header and footer areas
	 *
	 * @param {boolean} [visible] Visibility, inverts if undefined
	 * @param {string} [speed] Speed of the toggle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( visible, speed ) {
		var method, setActive;
		headersVisible = visible === undefined ? !headersVisible : visible;

		method = headersVisible ? 'show' : 'hide';
		$editForm.find( '.prp-page-edit-header' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-body label.prp-page-edit-area-label' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-footer' )[ method ]( speed );

		// eslint-disable-next-line no-jquery/no-global-selector
		setActive = $( '.tool[rel=toggle-visibility]' ).data( 'setActive' );
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
		var setActive;

		var wasLayoutHorizontal = isLayoutHorizontal;
		isLayoutHorizontal = horizontal === undefined ? !isLayoutHorizontal : horizontal;

		if ( viewer && wasLayoutHorizontal === isLayoutHorizontal ) {
			// nothing changed, nothing to do
			return;
		}

		if ( viewer ) {
			viewer.destroy();
			viewer = null;
		}

		// Record current layout status on both layout elements, for CSS convenience.
		var $layoutParts = $imgContHorizontal.add( $editForm.find( '.prp-page-container' ) );
		$layoutParts.toggleClass( 'prp-layout-is-vertical', !isLayoutHorizontal );
		$layoutParts.toggleClass( 'prp-layout-is-horizontal', isLayoutHorizontal );

		var idToInitialize = isLayoutHorizontal ?
			'prp-page-image-openseadragon-horizontal' :
			'prp-page-image-openseadragon-vertical';
		ensureImageZoomInitialization( idToInitialize );

		// eslint-disable-next-line no-jquery/no-global-selector
		setActive = $( '.tool[rel=toggle-layout]' ).data( 'setActive' );
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
	 * Init the automatic fill of the summary input box
	 */
	function setupPageQuality() {
		// eslint-disable-next-line no-jquery/no-global-selector
		$( 'input[name="wpQuality"]' ).on( 'click', function () {
			// eslint-disable-next-line no-jquery/no-global-selector
			var $summary = $( 'input#wpSummary, #wpSummary > input' ),
				// The following messages are used here:
				// * proofreadpage_quality0_summary
				// * proofreadpage_quality1_summary
				// * proofreadpage_quality2_summary
				// * proofreadpage_quality3_summary
				// * proofreadpage_quality4_summary
				pageQuality = mw.message( 'proofreadpage_quality' + this.value + '_summary' ).plain(),
				summary = $summary.val().replace( /\/\*.*\*\/\s?/, '' );
			$summary.val( '/* ' + pageQuality + ' */ ' + summary );
		} );
	}

	/**
	 * Setup the editing interface
	 */
	function setupWikitextEditor() {
		var zoomInterface = {
			zoomIn: new OO.ui.ButtonWidget( {
				id: 'prp-page-zoomIn',
				icon: 'zoomIn',
				framed: false
			} ),
			zoomOut: new OO.ui.ButtonWidget( {
				id: 'prp-page-zoomOut',
				icon: 'zoomOut',
				framed: false
			} ),
			zoomReset: new OO.ui.ButtonWidget( {
				id: 'prp-page-zoomReset',
				icon: 'zoomReset',
				framed: false
			} ),
			rotateLeft: new OO.ui.ButtonWidget( {
				id: 'prp-page-rotateLeft',
				icon: 'undo',
				framed: false
			} ),
			rotateRight: new OO.ui.ButtonWidget( {
				id: 'prp-page-rotateRight',
				icon: 'redo',
				framed: false
			} )
		};

		var $viewportControls = $( '<div>' )
			.addClass( 'prp-page-zoom-interface' )
			.append(
				zoomInterface.zoomIn.$element,
				zoomInterface.zoomOut.$element,
				zoomInterface.zoomReset.$element,
				zoomInterface.rotateLeft.$element,
				zoomInterface.rotateRight.$element
			);

		$editForm
			.find( '.wikiEditor-ui-toolbar .section-main' )
			.after( $viewportControls );

		var tools = {
			other: {
				labelMsg: 'proofreadpage-group-other',
				tools: {
					'toggle-visibility': {
						labelMsg: 'proofreadpage-button-toggle-visibility-label',
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
						labelMsg: 'proofreadpage-button-toggle-layout-label',
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

		// this function is inside the wikiEditor.toolbar ready hook handler
		$editForm.find( '.prp-page-edit-body' ).append( $wpTextbox );
		$editForm.find( '.editOptions' ).before( $editForm.find( '.wikiEditor-ui' ) );
		$editForm.find( '.wikiEditor-ui-text' ).append( $editForm.find( '.prp-page-container' ) );

		$wpTextbox.wikiEditor( 'addToToolbar', {
			sections: {
				'proofreadpage-tools': {
					type: 'toolbar',
					labelMsg: 'proofreadpage-section-tools',
					groups: tools
				}
			}
		} );

		// Users can call $('#wpTextbox1').textSelection( 'getContents' ) to get the full wikitext
		// of the page, instead of just the body section.
		//
		// FIXME This is missing overrides for setContents, getSelection, getCaretPosition, setSelection
		// and so the textSelection API is really inconsistent. getContents behaves as if this textbox
		// contained the entire page wikitext (with header and footer), but the other methods don't. :(
		$wpTextbox.textSelection(
			'register',
			{
				getContents: function () {
					var
						// "[checked]" selector refers to the original state (the HTML attribute).
						// eslint-disable-next-line no-jquery/no-global-selector
						origLevel = +$( 'input[name=wpQuality][checked]' ).val(),
						// ":checked" selector refers to the current state (the DOM property).
						// eslint-disable-next-line no-jquery/no-global-selector
						level = +$( 'input[name=wpQuality]:checked' ).val(),
						origUser = mw.config.get( 'prpPageQualityUser' ),
						user = ( origLevel === level ? origUser : mw.config.get( 'wgUserName' ) ) || '';
					return '<noinclude>' +
						( !isNaN( level ) ? '<pagequality level="' + level + '" user="' + user + '" />' : '' ) +
						// eslint-disable-next-line no-jquery/no-global-selector
						$( '#wpHeaderTextbox' ).val() +
					'</noinclude>' +
					$( this ).val() +
					'<noinclude>' +
						// eslint-disable-next-line no-jquery/no-global-selector
						$( '#wpFooterTextbox' ).val() +
					'</noinclude>';
				},

				// FIXME This is brutally copypasted from MediaWiki core jquery.textSelection,
				// with all calls to `.textSelection( 'getContents' )` replaced with `.val()`
				// and all calls to `.textSelection( 'setContents', ... )` replaced with `.val( ... )`.
				// Ideally, we would not have these and instead implement matching overrides
				// also for setContents, getSelection, getCaretPosition, setSelection; but I think
				// no one really cares to have them.
				replaceSelection: function ( value ) {
					return this.each( function () {
						var allText, currSelection, startPos, endPos;

						allText = $( this ).val();
						currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
						startPos = currSelection[ 0 ];
						endPos = currSelection[ 1 ];

						$( this ).val( allText.slice( 0, startPos ) + value +
							allText.slice( endPos ) );
						$( this ).textSelection( 'setSelection', {
							start: startPos,
							end: startPos + value.length
						} );
					} );
				},
				encapsulateSelection: function ( options ) {
					return this.each( function () {
						var selText, allText, currSelection, insertText,
							combiningCharSelectionBug = false,
							isSample, startPos, endPos,
							pre = options.pre,
							post = options.post;

						/**
						 * @ignore
						 * Check if the selected text is the same as the insert text
						 */
						function checkSelectedText() {
							if ( !selText ) {
								selText = options.peri;
								isSample = true;
							} else if ( options.replace ) {
								selText = options.peri;
							} else {
								while ( selText.charAt( selText.length - 1 ) === ' ' ) {
									// Exclude ending space char
									selText = selText.slice( 0, -1 );
									post += ' ';
								}
								while ( selText.charAt( 0 ) === ' ' ) {
									// Exclude prepending space char
									selText = selText.slice( 1 );
									pre = ' ' + pre;
								}
							}
						}

						/**
						 * @ignore
						 * Do the splitlines stuff.
						 *
						 * Wrap each line of the selected text with pre and post
						 *
						 * @return {string} Wrapped text
						 */
						function doSplitLines() {
							var i,
								text = '',
								selTextArr = selText.split( '\n' );
							for ( i = 0; i < selTextArr.length; i++ ) {
								text += pre + selTextArr[ i ] + post;
								if ( i !== selTextArr.length - 1 ) {
									text += '\n';
								}
							}
							return text;
						}

						isSample = false;
						$( this ).trigger( 'focus' );
						if ( options.selectionStart !== undefined ) {
							$( this ).textSelection( 'setSelection', { start: options.selectionStart, end: options.selectionEnd } );
						}

						selText = $( this ).textSelection( 'getSelection' );
						allText = $( this ).val();
						currSelection = $( this ).textSelection( 'getCaretPosition', { startAndEnd: true } );
						startPos = currSelection[ 0 ];
						endPos = currSelection[ 1 ];
						checkSelectedText();
						if (
							options.selectionStart !== undefined &&
							endPos - startPos !== options.selectionEnd - options.selectionStart
						) {
							// This means there is a difference in the selection range returned by browser and what we passed.
							// This happens for Safari 5.1, Chrome 12 in the case of composite characters. Ref T32130
							// Set the startPos to the correct position.
							startPos = options.selectionStart;
							combiningCharSelectionBug = true;
							// TODO: The comment above is from 2011. Is this still a problem for browsers we support today?
							// Minimal test case: https://jsfiddle.net/z4q7a2ko/
						}

						insertText = pre + selText + post;
						if ( options.splitlines ) {
							insertText = doSplitLines();
						}
						if ( options.ownline ) {
							if ( startPos !== 0 && allText.charAt( startPos - 1 ) !== '\n' && allText.charAt( startPos - 1 ) !== '\r' ) {
								insertText = '\n' + insertText;
								pre += '\n';
							}
							if ( allText.charAt( endPos ) !== '\n' && allText.charAt( endPos ) !== '\r' ) {
								insertText += '\n';
								post += '\n';
							}
						}
						if ( combiningCharSelectionBug ) {
							$( this ).val( allText.slice( 0, startPos ) + insertText +
								allText.slice( endPos ) );
						} else {
							$( this ).textSelection( 'replaceSelection', insertText );
						}
						if ( isSample && options.selectPeri && ( !options.splitlines || ( options.splitlines && selText.indexOf( '\n' ) === -1 ) ) ) {
							$( this ).textSelection( 'setSelection', {
								start: startPos + pre.length,
								end: startPos + pre.length + selText.length
							} );
						} else {
							$( this ).textSelection( 'setSelection', {
								start: startPos + insertText.length
							} );
						}
						$( this ).trigger( 'encapsulateSelection', [ options.pre, options.peri, options.post, options.ownline,
							options.replace, options.splitlines ] );
					} );
				}
			}
		);
		// Store the original value for the whole wpTextbox1
		// so that it can be checked against in core's mediawiki.action.edit.editWarning.js
		// and not get a false positive.
		$wpTextbox.data( 'origtext', $wpTextbox.textSelection( 'getContents' ) );
	}

	/**
	 * Init global variables of the script
	 */
	function initEnvironment() {
		if ( $editForm === undefined ) {
			// eslint-disable-next-line no-jquery/no-global-selector
			$editForm = $( '#editform' );
		}

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

		if ( $img === undefined ) {
			// eslint-disable-next-line no-jquery/no-global-selector
			$img = $( '.prp-page-image' ).find( 'img' );

			if ( $img.length > 0 ) {
				var imgHeight = window.getComputedStyle( $img[ 0 ] ).getPropertyValue( 'height' );
				// eslint-disable-next-line no-jquery/no-global-selector
				$( '#prp-page-image-openseadragon-vertical' ).css( 'height', imgHeight );
			}
		}

		if ( $wpTextbox === undefined ) {
			$wpTextbox = $editForm.find( '#wpTextbox1' );
		}
	}

	$( function () {
		initEnvironment();
		setupPageQuality();

		mw.hook( 'wikiEditor.toolbarReady' ).add( function () {
			setupWikitextEditor();
			setupPreferences();
		} );

		// Always initialize the OSD viewer, even if no toolbar for the controls
		// (otherwise wait for toolbar init so the icons are ready)
		if ( !getBooleanUserOption( 'usebetatoolbar' ) ) {
			ensureImageZoomInitialization( 'prp-page-image-openseadragon-vertical' );
		}
	} );

}() );
