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

function prStartup() {
	jQuery( function() {
		prInitTabs();
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
