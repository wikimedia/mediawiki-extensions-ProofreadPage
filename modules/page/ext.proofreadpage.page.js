( function ( mw, $ ) {
	'use strict';

	//init zoom system. The load event is used because the page needs to be rendered before calling fitWidth
	$( document).ready( function() {
		var $image = $( '.prp-page-image img' );
		if( $image.length === 0 ) {
			return;
		}
		mw.loader.using( 'jquery.panZoom', function() {
			$image.panZoom();
			$image.panZoom( 'loadImage' );
			$image.panZoom( 'fitWidth' );
		} );
	} );

} ( mediaWiki, jQuery ) );