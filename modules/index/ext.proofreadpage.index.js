( function ( mw, $ ) {
	'use strict';

	$( document ).ready( function() {
		// Set up the help system
		$( '.prp-help-field' ).tipsy( {
			'gravity': 'nw'
		} );
	} );

} ( mediaWiki, jQuery ) );