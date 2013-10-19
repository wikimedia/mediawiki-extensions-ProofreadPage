( function ( mw, $ ) {
	'use strict';

	/**
	 * Show or hide header and footer areas
	 *
	 * @param string speed of the toogle. May be 'fast', 'slow' or undefined
	 */
	function toggleHeaders( speed ) {
		$( '.prp-page-edit-header' ).toggle( speed );
		$( '.prp-page-edit-body label' ).toggle( speed );
		$( '.prp-page-edit-footer' ).toggle( speed );
	}

	/**
	 * Apply user preferences
	 */
	function setupPreferences() {
		if( !mw.user.options.get( 'proofreadpage-showheaders' ) ) {
			toggleHeaders();
		}
		//TODO: scan on top of the edit system
	}

	/**
	 * Init the automatic fill of the summary input box
	 */
	function setupPageQuality() {
		$( 'input[name="wpQuality"]' ).click( function() {
			var text = mw.msg( 'proofreadpage_quality' + this.value + '_category' );
			$( 'input#wpSummary' ).val( '/* ' + text + ' */ ' );
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
								$( '#editform .prp-page-image img' ).prpZoom( 'zoomIn' );
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
								$( '#editform .prp-page-image img' ).prpZoom( 'zoomOut' );
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
								$( '#editform .prp-page-image img' ).prpZoom( 'reset' );
							}
						}
					},
					'move-up': {
						labelMsg: 'proofreadpage-button-pan-up-label',
						type: 'button',
						icon: iconPath + 'Button_pan_up.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '#editform .prp-page-image img' ).prpZoom( 'moveUp' );
							}
						}
					},
					'move-down': {
						labelMsg: 'proofreadpage-button-pan-down-label',
						type: 'button',
						icon: iconPath + 'Button_pan_down.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '#editform .prp-page-image img' ).prpZoom( 'moveDown' );
							}
						}
					},
					'move-left': {
						labelMsg: 'proofreadpage-button-pan-left-label',
						type: 'button',
						icon: iconPath + 'Button_pan_left.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '#editform .prp-page-image img' ).prpZoom( 'moveLeft' );
							}
						}
					},
					'move-right': {
						labelMsg: 'proofreadpage-button-pan-right-label',
						type: 'button',
						icon: iconPath + 'Button_pan_right.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '#editform .prp-page-image img' ).prpZoom( 'moveRight' );
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
								//TODO
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
		} else {
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
								class: 'mw-toolbar-editbutton'
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
		if( !mw.user.options.get( 'usebetatoolbar' ) ) {
			return;
		}
		mw.loader.using( 'ext.wikiEditor', function() {
			$( '.prp-page-edit-body' ).append( $( '#wpTextbox1' ) );
			$( '.wikiEditor-oldToolbar' ).after( $( '.wikiEditor-ui' ) );
			$( '.wikiEditor-ui-text' ).append( $( '#editform .prp-page-container' ) );
		} );
	}

	/**
	 * Init the zoom system
	 */
	function initZoom() {
		var $image = $( '#editform .prp-page-image img' );
		if( $image.length === 0 ) {
			return;
		}
		mw.loader.using( 'jquery.prpZoom', function() {
			$image.prpZoom();
		} );
	}

	$( document ).ready( function() {
		setupPreferences();
		setupWikiEditor();
		setupPageQuality();
		addButtons();
	} );

	//zoom should be init after the page is rendered
	$( window ).load( function() {
		initZoom();
	} );

} ( mediaWiki, jQuery ) );