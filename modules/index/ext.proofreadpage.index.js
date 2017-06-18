mw.loader.using( 'oojs-ui-core' ).done( function () {
	$( function () {
		$( '.prp-fieldLayout-help' ).map( function ( _, e ) {
			return OO.ui.infuse( e.id );
		} );
	} );
} );
