$( function () {
	'use strict';
	/* add backlink to index page */
	var urlLink;
	if ( mw.config.get( 'prpSourceIndexPage' ) ) {
		urlLink = mw.html.element( 'a',
			{
				href: mw.util.getUrl( mw.config.get( 'prpSourceIndexPage' ) ),
				title: mw.msg( 'proofreadpage_source_message' )
			},
			mw.msg( 'proofreadpage_source' ) );
	} else {
		// TODO(sohom): Remove 'proofreadpage_source_href' after a bit,
		// currently supported so as to not break the functionality
		// while trasfering to 'prpSourceIndexPage'
		urlLink = mw.config.get( 'proofreadpage_source_href' );
	}
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '#ca-nstab-main' ).after(
		$( '<li>' ).attr( 'id', 'ca-proofread-source' ).append(
			$( '<span>' ).html( urlLink )
		)
	);
} );
