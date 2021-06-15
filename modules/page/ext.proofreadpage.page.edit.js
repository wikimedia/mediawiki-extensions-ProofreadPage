( function () {
	'use strict';

	var
		/**
		 * Is the layout horizontal (ie is the scan image on top of the edit area)
		 *
		 * @type {boolean}
		 */
		isLayoutHorizontal = false,

		/**
		 * The scan image
		 *
		 * @type {jQuery}
		 */
		$zoomImage,

		headersVisible = true,

		/**
		 * The edit form
		 *
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
			if ( $zoomImage.prop( 'complete' ) ) {
				$zoomImage.prpZoom();
				if ( success ) {
					success();
				}
			} else {
				$zoomImage.on( 'load', function () {
					$zoomImage.prpZoom();
					if ( success ) {
						success();
					}
				} );
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
		var method, setActive;
		headersVisible = visible === undefined ? !headersVisible : visible;

		method = headersVisible ? 'show' : 'hide';
		$editForm.find( '.prp-page-edit-header' )[ method ]( speed );
		$editForm.find( '.prp-page-edit-body label.ext-proofreadpage-label' )[ method ]( speed );
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
		var $container, newHeight, setActive;

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
			// eslint-disable-next-line no-jquery/no-global-selector
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
			// eslint-disable-next-line no-jquery/no-global-selector
			$( '#wpTextbox1' ).css( { height: newHeight } );
		}
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
		var tools = {
				zoom: {
					labelMsg: 'proofreadpage-group-zoom',
					tools: {
						'zoom-in': {
							labelMsg: 'proofreadpage-button-zoom-in-label',
							type: 'button',
							oouiIcon: 'zoomIn',
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
			},
			// eslint-disable-next-line no-jquery/no-global-selector
			$edit = $( '#wpTextbox1' );

		if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
			// 'ext.wikiEditor' was loaded before calling this function
			$editForm.find( '.prp-page-edit-body' ).append( $edit );
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
		$edit.data( 'origtext', $edit.textSelection( 'getContents' ) );
	}

	/**
	 * Init global variables of the script
	 */
	function initEnvironment() {
		if ( $editForm === undefined ) {
			// eslint-disable-next-line no-jquery/no-global-selector
			$editForm = $( '#editform' );
		}
		if ( $zoomImage === undefined ) {
			$zoomImage = $editForm.find( '.prp-page-image img' );
		}
	}

	function getLoadedEditorPromise() {
		var deferred = $.Deferred();
		if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
			mw.loader.using( 'ext.wikiEditor' ).done( function () {
				// eslint-disable-next-line no-jquery/no-global-selector
				$( '#wpTextbox1' ).on( 'wikiEditor-toolbar-doneInitialSections', deferred.resolve.bind( deferred ) );
			} );
			return deferred.promise();
		}

		return deferred.resolve().promise();
	}

	$( function () {
		initEnvironment();
		setupPreferences();
		setupPageQuality();
		getLoadedEditorPromise().done( setupWikitextEditor );

		// zoom should be initialized after the page is rendered
		if ( document.readyState === 'complete' ) {
			ensureImageZoomInitialization();
		} else {
			$( window ).on( 'load', function () {
				ensureImageZoomInitialization();
			} );
		}
	} );

}() );
