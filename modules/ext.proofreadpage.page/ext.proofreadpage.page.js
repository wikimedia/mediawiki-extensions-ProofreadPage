// Author : ThomasV - License : GPL

( function( $, mw ) {
	$( "input[name='wpQuality']" ).click( function() {
		var text = mw.msg( 'proofreadpage_quality' + this.value + '_category' );
		document.editform.elements[ 'wpSummary' ].value = '/* ' + text + ' */ ';
	});

	$( "button[name='hideHeader']" ).click( function() {
		if ( $( "textarea[id='wpHeaderTextbox']" ).is( ":visible" ) ) {
			$( "textarea[id='wpHeaderTextbox']" ).hide();
			$( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			$( "textarea[id='wpHeaderTextbox']" ).show();
			$( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
	$( "button[name='hideFooter']" ).click( function() {
		if ( $( "textarea[id='wpFooterTextbox']" ).is(":visible") ) {
			$( "textarea[id='wpFooterTextbox']" ).hide();
			$( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			$( "textarea[id='wpFooterTextbox']" ).show();
			$( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
} ( jQuery, mediaWiki ) );

function initPanZoom() {
	$('.prp-page-image img').panZoom({
		'debug'	: false
	});
}

function prStartup() {
	jQuery( function() {
		prAddButtons();
		initPanZoom();
	});
}

function prAddButtons() {

	if( !proofreadPageIsEdit ) {
		return;
	}
	var tools = {
		'section': 'proofreadpage-tools',
		'groups': {
			'zoom': {
				'label': mw.msg( 'proofreadpage-group-zoom' ),
				'tools': {
					'zoom-in': {
						label: mw.msg( 'proofreadpage-button-zoom-in-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_zoom_in.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('zoomIn');
							}
						}
					},
					'zoom-out': {
						label: mw.msg( 'proofreadpage-button-zoom-out-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_zoom_out.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('zoomOut');
							}
						}
					},
					'reset-zoom': {
						label: mw.msg( 'proofreadpage-button-reset-zoom-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_examine.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('fit');
							}
						}
					},
					'pan-up': {
						label: mw.msg( 'proofreadpage-button-pan-up-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_pan_up.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('panUp');
							}
						}
					},
					'pan-down': {
						label: mw.msg( 'proofreadpage-button-pan-down-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_pan_down.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('panDown');
							}
						}
					},
					'pan-left': {
						label: mw.msg( 'proofreadpage-button-pan-left-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_pan_left.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('panLeft');
							}
						}
					},
					'pan-right': {
						label: mw.msg( 'proofreadpage-button-pan-right-label' ),
						type: 'button',
						icon: mw.config.get( 'wgExtensionAssetsPath' ) + '/ProofreadPage/modules/ext.proofreadpage.page/images/Button_pan_right.png',
						action: {
							type: 'callback',
							execute: function() {
								$( '.prp-page-image img' ).panZoom('panRight');
							}
						}
					}
				}
			},
		}
	};

	var $edit = $( '#wpTextbox1' );
	if( mw.user.options.get('usebetatoolbar') ) {
		mw.loader.using( 'ext.wikiEditor.toolbar', function() {
			$edit.wikiEditor( 'addToToolbar', {
				'sections': {
					'proofreadpage-tools': {
						'type': 'toolbar',
						'label': mw.msg( 'proofreadpage-section-tools' )
					}
				}
			} )
			.wikiEditor( 'addToToolbar', tools);
		});
	} else {
		$.each( tools.groups, function( group, list ) {
			$.each( list.tools, function( id, def ) {
				mw.toolbar.addButton( {
					imageFile: def.icon,
					imageId: 'mw-editbutton-' + id,
					speedTip: def.label
				} );
			$( '#mw-editbutton-' + id ).click( def.action.execute );
			} );
		} );
	}
}

if ( mw.user.options.get( 'usebetatoolbar' ) && jQuery.inArray( 'ext.wikiEditor.toolbar', mw.loader.getModuleNames() ) > -1 ) {
	mw.loader.using( 'ext.wikiEditor.toolbar', function() {
		// Load the whole thing after the toolbar has been constructed
		prStartup();
	} );
} else {
	prStartup();
}
