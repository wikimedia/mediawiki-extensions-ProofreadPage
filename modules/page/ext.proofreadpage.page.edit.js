( function ( mw, $ ) {
	'use strict';

	var
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
	 * @param {string} speed string speed of the toggle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( speed ) {
		$editForm.find( '.prp-page-edit-header' ).toggle( speed );
		$editForm.find( '.prp-page-edit-body label' ).toggle( speed );
		$editForm.find( '.prp-page-edit-footer' ).toggle( speed );
	}

	/**
	 * Put the scan image on top or on the left of the edit area
	 */
	function toggleLayout() {
		var $container, newHeight;
		if ( $zoomImage.data( 'prpZoom' ) ) {
			$zoomImage.prpZoom( 'destroy' );
		}

		$container = $zoomImage.parent();

		if ( isLayoutHorizontal ) {
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

			isLayoutHorizontal = false;

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

			isLayoutHorizontal = true;
		}
	}

	/**
	 * Apply user preferences
	 */
	function setupPreferences() {
		if ( !getBooleanUserOption( 'proofreadpage-showheaders' ) ) {
			toggleHeaders();
		}
		if ( getBooleanUserOption( 'proofreadpage-horizontal-layout' ) ) {
			toggleLayout();
		}
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
							icon: iconPath + 'wikieditor-zoom-in.png',
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
							icon: iconPath + 'wikieditor-zoom-out.png',
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
							icon: iconPath + 'wikieditor-zoom-reset.png',
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
							icon: iconPath + 'wikieditor-visibility.png',
							oldIcon: iconPath + 'Button_category_plus.png',
							action: {
								type: 'callback',
								execute: function () {
									toggleHeaders( 'fast' );
								}
							}
						},
						'toggle-layout': {
							labelMsg: 'proofreadpage-button-toggle-layout-label',
							type: 'button',
							icon: iconPath + 'wikieditor-layout.png',
							oldIcon: iconPath + 'Button_multicol.png',
							action: {
								type: 'callback',
								execute: toggleLayout
							}
						}
					}
				}
			},
			$edit = $( '#wpTextbox1' );

		if ( getBooleanUserOption( 'usebetatoolbar' ) ) {
			mw.loader.using( 'ext.wikiEditor', function () {
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
			} );

		} else if ( getBooleanUserOption( 'showtoolbar' ) ) {
			mw.loader.using( 'mediawiki.toolbar', function () {
				$.each( tools, function ( group, list ) {
					$.each( list.tools, function ( id, def ) {
						mw.toolbar.addButton( {
							imageFile: def.oldIcon,
							speedTip: mw.msg( def.labelMsg ),
							onClick: def.action.execute
						} );
					} );
				} );
			} );
		}

		// Users can call $('#wpTextbox1').textSelection( 'getContents' ) to get the full wikitext
		// of the page, instead of just the body section.
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
		setupWikitextEditor();
		setupPreferences();
		setupPageQuality();
	} );

	// zoom should be initialized after the page is rendered
	$( window ).load( function () {
		initEnvironment();
		ensureImageZoomInitialization();
	} );

}( mediaWiki, jQuery ) );
