mw.loader.using( 'oojs-ui-core' ).done( function () {
	$( function () {
		$( '.prp-fieldLayout-help' ).map( function () {
			return OO.ui.infuse( this );
		} );
	} );
} );
