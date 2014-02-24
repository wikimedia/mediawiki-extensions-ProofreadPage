( function ( mw, $ ) {
	'use strict';

	/**
	 * Is the layout horizontal (ie is the scan image on top of the edit area)
	 * @type {boolean}
	 */
	var isLayoutHorizontal = false;

	/**
	 * The scan image
	 * @type {jQuery}
	 */
	var $zoomImage;

	/**
	 * The edit form
	 * @type {jQuery}
	 */
	var $editForm;

	/**
	 * Show or hide header and footer areas
	 *
	 * @param speed string speed of the toogle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( speed ) {
		$editForm.find( '.prp-page-edit-header' ).toggle( speed );
		$editForm.find( '.prp-page-edit-body label' ).toggle( speed );
		$editForm.find( '.prp-page-edit-footer' ).toggle( speed );
	}

	/**
	 * Put the scan image on top or on the left of the edit area
	 */
	function toogleLayout() {
		if( $zoomImage.data( 'prpZoom' ) ) {
			$zoomImage.prpZoom( 'destroy' );
		}

		var $container = $zoomImage.parent();

		if( isLayoutHorizontal ) {
			$container.appendTo( $editForm.find( '.prp-page-container' ) );

			$container.css( {
				width: ''
			} );
			$editForm.find( '.prp-page-content' ).css( {
				width: ''
			} );

			$zoomImage.prpZoom();

			isLayoutHorizontal = false;

		} else {
			$container.insertBefore( $editForm );

			$container.css( {
				width: '100%',
				overflow: 'auto',
				height: $( window ).height() / 2.7 + 'px'
			} );
			$editForm.find( '.prp-page-content' ).css( {
				width: '100%'
			} );

			$zoomImage.prpZoom();
			$container.css( {
				height: $( window ).height() / 3 + 'px'
			} );

			isLayoutHorizontal = true;
		}
	}

	/**
	 * Apply user preferences
	 */
	function setupPreferences() {
		if( !mw.user.options.get( 'proofreadpage-showheaders' ) ) {
			toggleHeaders();
		}
		if( mw.user.options.get( 'proofreadpage-horizontal-layout' ) ) {
			toogleLayout();
		}
	}

	/**
	 * Init the automatic fill of the summary input box
	 */
	function setupPageQuality() {
		$( 'input[name="wpQuality"]' ).click( function() {
			var $summary = $( '#wpSummary' );
			var pageQuality = mw.message( 'proofreadpage_quality' + this.value + '_category').plain();
			var summary = $summary.val().replace( /\/\*.*\*\/\s?/, '' );
			$summary.val( '/* ' + pageQuality + ' */ ' + summary );
		} );
	}

	/**
	 * Add some buttons to the toolbar
	 */
	function addButtons() {
		var iconPath = mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/page/images/';
		var tools = {
			zoom: {
				labelMsg: 'proofreadpage-group-zoom',
				tools: {
					'zoom-in': {
						labelMsg: 'proofreadpage-button-zoom-in-label',
						type: 'button',
						icon: iconPath + 'Button_zoom_in.png',
						action: {
							type: 'callback',
							execute: function() {
								$zoomImage.prpZoom( 'zoomIn' );
							}
						}
					},
					'zoom-out': {
						labelMsg: 'proofreadpage-button-zoom-out-label',
						type: 'button',
						icon: iconPath + 'Button_zoom_out.png',
						action: {
							type: 'callback',
							execute: function() {
								$zoomImage.prpZoom( 'zoomOut' );
							}
						}
					},
					'reset-zoom': {
						labelMsg: 'proofreadpage-button-reset-zoom-label',
						type: 'button',
						icon: iconPath + 'Button_examine.png',
						action: {
							type: 'callback',
							execute: function() {
								$zoomImage.prpZoom( 'reset' );
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
						icon: iconPath + 'Button_category_plus.png',
						action: {
							type: 'callback',
							execute: function() {
								toggleHeaders( 'fast' );
							}
						}
					},
					'toggle-layout': {
						labelMsg: 'proofreadpage-button-toggle-layout-label',
						type: 'button',
						icon: iconPath + 'Button_multicol.png',
						action: {
							type: 'callback',
							execute: function() {
								toogleLayout();
							}
						}
					}
				}
			}
		};

		var $edit = $( '#wpTextbox1' );
		if( mw.user.options.get( 'usebetatoolbar' ) ) {
			mw.loader.using( 'ext.wikiEditor.toolbar', function() {
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
		} else if( mw.user.options.get( 'showtoolbar' ) ) {
			mw.loader.using( 'mediawiki.action.edit', function() {
				var $toolbar = $( '#toolbar' );

				$.each( tools, function( group, list ) {
					$.each( list.tools, function( id, def ) {
						$( '<img>' )
							.attr( {
								width: 23,
								height: 22,
								src: def.icon,
								alt: mw.msg( def.labelMsg ),
								title: mw.msg( def.labelMsg ),
								'class': 'mw-toolbar-editbutton' //quotes needed for IE
							} )
							.click( def.action.execute )
							.appendTo( $toolbar );
					} );
				} );
			} );
		}
	}

	/**
	 * Improve the WikiEditor interface
	 */
	function setupWikiEditor() {
		if( !mw.user.options.get( 'showtoolbar' ) || !mw.user.options.get( 'usebetatoolbar' ) ) {
			return;
		}
		mw.loader.using( 'ext.wikiEditor', function() {
			$editForm.find( '.prp-page-edit-body' ).append( $( '#wpTextbox1' ) );
			$editForm.find( '.wikiEditor-oldToolbar' ).after( $editForm.find( '.wikiEditor-ui' ) );
			$editForm.find( '.wikiEditor-ui-text' ).append( $editForm.find( '.prp-page-container' ) );
		} );
	}

	/**
	 * Init global variables of the script
	 */
	function initEnvironment() {
		if( $editForm === undefined ) {
			$editForm = $( '#editform' );
		}
		if( $zoomImage === undefined ) {
			$zoomImage = $editForm.find( '.prp-page-image img' );
		}
	}

	/**
	 * Init the zoom system
	 */
	function initZoom() {
		mw.loader.using( 'jquery.prpZoom', function() {
			$zoomImage.prpZoom();
		} );
	}

	$( document ).ready( function() {
		initEnvironment();
		setupWikiEditor();
		setupPreferences();
		setupPageQuality();
		addButtons();
	} );

	//zoom should be init after the page is rendered
	$( window ).load( function() {
		initEnvironment();
		initZoom();
	} );

} ( mediaWiki, jQuery ) );
