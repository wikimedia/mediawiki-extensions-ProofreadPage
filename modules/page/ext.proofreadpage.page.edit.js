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

		var $wpTextbox = $editForm.find( '#wpTextbox1' );
		$wpTextbox.wikiEditor( 'addToToolbar', {
			section: 'secondary',
			groups: {
				'proofreadpage-secondary': {
					tools: zoomInterface
				}
			}
		} );

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
				$editForm.find( '#wpTextbox1' ).removeAttr( 'rows cols' );
				// eslint-disable-next-line no-jquery/no-global-selector
				$( '.prp-page-container' ).css( 'height', imgHeight );
			}
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
