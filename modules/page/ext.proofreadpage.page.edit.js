( function ( mw, $ ) {
	'use strict';

	var
		toolbarDependencies,

		/**
		 * Is the layout horizontal (ie is the scan image on top of the edit area)
		 * @type {boolean}
		 */
		isLayoutHorizontal = false,

		/**
		 * The scan image
		 * @type {jQuery}
		 */
		$zoomImage,

		headersVisible = true,

		/**
		 * The edit form
		 * @type {jQuery}
		 */
		$editForm;

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
	 * Ensure that the zoom system is properly initialized
	 *
	 * @param {Function} success a function to use after making sure that the zoom system is activate
	 */
	function ensureImageZoomInitialization( success ) {
		if ( $zoomImage.data( 'prpZoom' ) ) {
			if ( success ) {
				success();
			}
			return;
		}

		mw.loader.using( 'jquery.prpZoom', function () {
			$zoomImage.prpZoom();
			if ( success ) {
				success();
			}
		} );
	}

	/**
	 * Show or hide header and footer areas
	 *
	 * @param {boolean} [visible] Visibility, inverts if undefined
	 * @param {string} [speed] Speed of the toggle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( visible, speed ) {
		var method;
		headersVisible = visible === undefined ? !headersVisible : visible;

		method = headersVisible ? 'show' : 'hide';
		$editForm.find( '.prp-page-edit-header' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-body label' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-footer' )[ method ]( speed );

		if ( $( '.tool[rel=toggle-visibility]' ).data( 'setActive' ) ) {
			$( '.tool[rel=toggle-visibility]' ).data( 'setActive' )( headersVisible );
		}
	}

	/**
	 * Put the scan image on top or on the left of the edit area
	 *
	 * @param {boolean} [horizontal] Use horizontal layout, inverts if undefined
	 */
	function toggleLayout( horizontal ) {
		var $container, newHeight;

		isLayoutHorizontal = horizontal === undefined ? !isLayoutHorizontal : horizontal;

		if ( $zoomImage.data( 'prpZoom' ) ) {
			$zoomImage.prpZoom( 'destroy' );
		}

		$container = $zoomImage.parent();

		if ( !isLayoutHorizontal ) {
			$container.appendTo( $editForm.find( '.prp-page-container' ) );

			// Switch CSS widths and heights back to the default side-by-size layout.
			$container.css( {
				width: '',
				height: ''
			} );
			$editForm.find( '.prp-page-content' ).css( {
				width: ''
			} );
			$( '#wpTextbox1' ).css( { height: '' } );
			ensureImageZoomInitialization();
		} else {
			$container.insertBefore( $editForm );

			// Set the width and height of the image and form.
			$container.css( {
				width: '100%',
				overflow: 'auto',
				height: $( window ).height() / 2.7 + 'px'
			} );
			$editForm.find( '.prp-page-content' ).css( {
				width: '100%'
			} );

			// Turn on image zoom before setting the image height, or it'll be overridden.
			ensureImageZoomInitialization();

			// Set the image and the edit box to the same height (of 1/3 of the window each).
			newHeight = $( window ).height() / 3 + 'px';
			$container.css( { height: newHeight } );
			$( '#wpTextbox1' ).css( { height: newHeight } );
		}
		if ( $( '.tool[rel=toggle-layout]' ).data( 'setActive' ) ) {
			$( '.tool[rel=toggle-layout]' ).data( 'setActive' )( isLayoutHorizontal );
		}
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
		$( 'input[name="wpQuality"]' ).click( function () {
			var $summary = $( 'input#wpSummary, #wpSummary > input' ),
				pageQuality = mw.message( 'proofreadpage_quality' + this.value + '_category' ).plain(),
				summary = $summary.val().replace( /\/\*.*\*\/\s?/, '' );
			$summary.val( '/* ' + pageQuality + ' */ ' + summary );
		} );
	}

	/**
	 * Setup the editing interface
	 */
	function setupWikitextEditor() {
		var iconPath = mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/page/images/',
			tools = {
				zoom: {
					labelMsg: 'proofreadpage-group-zoom',
					tools: {
						'zoom-in': {
							labelMsg: 'proofreadpage-button-zoom-in-label',
							type: 'button',
							oouiIcon: 'zoomIn',
							oldIcon: iconPath + 'Button_zoom_in.png',
							action: {
								type: 'callback',
								execute: function () {
									ensureImageZoomInitialization( function () {
										$zoomImage.prpZoom( 'zoomIn' );
									} );
								}
							}
						},
						'zoom-out': {
							labelMsg: 'proofreadpage-button-zoom-out-label',
							type: 'button',
							oouiIcon: 'zoomOut',
							oldIcon: iconPath + 'Button_zoom_out.png',
							action: {
								type: 'callback',
								execute: function () {
									ensureImageZoomInitialization( function () {
										$zoomImage.prpZoom( 'zoomOut' );
									} );
								}
							}
						},
						'reset-zoom': {
							labelMsg: 'proofreadpage-button-reset-zoom-label',
							type: 'button',
							oouiIcon: 'zoomReset',
							oldIcon: iconPath + 'Button_examine.png',
							action: {
								type: 'callback',
								execute: function () {
									ensureImageZoomInitialization( function () {
										$zoomImage.prpZoom( 'reset' );
									} );
								}
							}
						}
					}
				},
				other: {
					labelMsg: 'proofreadpage-group-other',
					tools: {
						'toggle-visibility': {
							labelMsg: 'proofreadpage-button-toggle-visibility-label',
							type: 'button',
							oouiIcon: 'headerFooter',
							oldIcon: iconPath + 'Button_category_plus.png',
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
							oldIcon: iconPath + 'Button_multicol.png',
							action: {
								type: 'callback',
								execute: toggleLayout.bind( this, undefined )
							}
						}
					}
				}
			},
			$edit = $( '#wpTextbox1' );

		if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
			// 'ext.wikiEditor' was loaded before calling this function
			$editForm.find( '.prp-page-edit-body' ).append( $( '#wpTextbox1' ) );
			$editForm.find( '.editOptions' ).before( $editForm.find( '.wikiEditor-ui' ) );
			$editForm.find( '.wikiEditor-ui-text' ).append( $editForm.find( '.prp-page-container' ) );

			$edit.wikiEditor( 'addToToolbar', {
				sections: {
					'proofreadpage-tools': {
						type: 'toolbar',
						labelMsg: 'proofreadpage-section-tools',
						groups: tools
					}
				}
			} );

			setupPreferences();

		} else if ( getBooleanUserOption( 'showtoolbar' ) ) {
			// 'mediawiki.toolbar' was loaded before calling this function
			$.each( tools, function ( group, list ) {
				$.each( list.tools, function ( id, def ) {
					mw.toolbar.addButton( {
						imageFile: def.oldIcon,
						speedTip: mw.msg( def.labelMsg ),
						onClick: def.action.execute
					} );
				} );
			} );
		}

		// Users can call $('#wpTextbox1').textSelection( 'getContents' ) to get the full wikitext
		// of the page, instead of just the body section.
		//
		// FIXME This is missing overrides for setContents, getSelection, getCaretPosition, setSelection
		// and so the textSelection API is really inconsistent. getContents behaves as if this textbox
		// contained the entire page wikitext (with header and footer), but the other methods don't. :(
		$edit.textSelection(
			'register',
			{
				getContents: function () {
					var level = +$( 'input[name=wpQuality][checked]' ).val();
					return '<noinclude>' +
						// The user attribute is populated later
						( !isNaN( level ) ? '<pagequality level="' + level + '" user="" />' : '' ) +
						$( '#wpHeaderTextbox' ).val() +
					'</noinclude>' +
					$( this ).val() +
					'<noinclude>' +
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
						 * @param {string} selText Selected text
						 * @param {string} pre Text before
						 * @param {string} post Text after
						 * @return {string} Wrapped text
						 */
						function doSplitLines( selText, pre, post ) {
							var i,
								insertText = '',
								selTextArr = selText.split( '\n' );
							for ( i = 0; i < selTextArr.length; i++ ) {
								insertText += pre + selTextArr[ i ] + post;
								if ( i !== selTextArr.length - 1 ) {
									insertText += '\n';
								}
							}
							return insertText;
						}

						isSample = false;
						$( this ).focus();
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
							insertText = doSplitLines( selText, pre, post );
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
	}

	/**
	 * Init global variables of the script
	 */
	function initEnvironment() {
		if ( $editForm === undefined ) {
			$editForm = $( '#editform' );
		}
		if ( $zoomImage === undefined ) {
			$zoomImage = $editForm.find( '.prp-page-image img' );
		}
	}

	$( function () {
		initEnvironment();
		setupPreferences();
		setupPageQuality();
	} );

	toolbarDependencies = [];
	if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
		toolbarDependencies.push( 'ext.wikiEditor' );
	} else if ( getBooleanUserOption( 'showtoolbar' ) ) {
		toolbarDependencies.push( 'mediawiki.toolbar' );
	}

	mw.loader.using( toolbarDependencies ).done( function () {
		$( function () {
			setupWikitextEditor();
		} );
	} );

	// zoom should be initialized after the page is rendered
	$( window ).load( function () {
		initEnvironment();
		ensureImageZoomInitialization();
	} );

}( mediaWiki, jQuery ) );
