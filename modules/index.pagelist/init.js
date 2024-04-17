( function () {
	const PagelistInputWidget = require( './PagelistInputWidget.js' );

	mw.proofreadpage = {};
	/**
	 * @internal not a stable API.
	 */
	mw.proofreadpage.PagelistInputWidget = PagelistInputWidget;

	try {
		// Until I can fgure out a better way to do this
		// eslint-disable-next-line no-jquery/no-global-selector
		$( '.prp-pagelistInputWidget' ).each( function ( index, $widget ) {
			mw.proofreadpage.PagelistInputWidget.static.infuse( $widget );
		} );
	} catch ( e ) {
		mw.log.warn( e );
	}

}() );
