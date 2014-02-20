( function ( mw, $ ) {
	'use strict';

	$( document ).ready( function() {
		/* add backlink to index page */
		$( '#ca-nstab-main' ).after( '<li id="ca-proofread-source"><span>' + mw.config.get( 'proofreadpage_source_href' ) + '</span></li>' );
	} );

} ( mediaWiki, jQuery ) );