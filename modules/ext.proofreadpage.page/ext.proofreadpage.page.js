// Author : ThomasV - License : GPL

function prSetSummary() {
	jQuery( "input[name='wpQuality']" ).click( function() {
		var text = mediaWiki.msg( 'proofreadpage_quality' + this.value + '_category' );
		document.editform.elements[ 'wpSummary' ].value = '/* ' + text + ' */ ';
	});

}

function prHideHeaderFooter() {
	jQuery( "button[name='hideHeader']" ).click( function() {
		if ( jQuery( "textarea[id='wpHeaderTextbox']" ).is( ":visible" ) ) {
			jQuery( "textarea[id='wpHeaderTextbox']" ).hide();
			jQuery( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			jQuery( "textarea[id='wpHeaderTextbox']" ).show();
			jQuery( "button[name='hideHeader']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
	jQuery( "button[name='hideFooter']" ).click( function() {
		if ( jQuery( "textarea[id='wpFooterTextbox']" ).is(":visible") ) {
			jQuery( "textarea[id='wpFooterTextbox']" ).hide();
			jQuery( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		} else {
			jQuery( "textarea[id='wpFooterTextbox']" ).show();
			jQuery( "button[name='hideFooter']" ).text( mw.msg( 'proofreadpage-toggle-headerfooter' ) );
		}
	} );
}


function prStartup() {
	jQuery( function() {
		prSetSummary();
		prHideHeaderFooter();
	} );
}

if ( mw.user.options.get( 'usebetatoolbar' ) && jQuery.inArray( 'ext.wikiEditor.toolbar', mw.loader.getModuleNames() ) > -1 ) {
	mw.loader.using( 'ext.wikiEditor.toolbar', function() {
		// Load the whole thing after the toolbar has been constructed
		prStartup();
	} );
} else {
	prStartup();
}
