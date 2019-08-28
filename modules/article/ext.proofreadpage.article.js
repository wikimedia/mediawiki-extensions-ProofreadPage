$( function () {
	'use strict';
	/* add backlink to index page */
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '#ca-nstab-main' ).after(
		$( '<li>' ).attr( 'id', 'ca-proofread-source' ).append(
			$( '<span>' ).html( mw.config.get( 'proofreadpage_source_href' ) )
		)
	);
} );
