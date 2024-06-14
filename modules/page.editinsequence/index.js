( function () {
	const Toolbar = require( './Toolbar.js' ),
		// eslint-disable-next-line no-jquery/no-global-selector
		$content = $( '#content' ),
		eisToolbar = new Toolbar();
	if ( $content.length ) {
		$content.prepend( eisToolbar.$element );
	}
}() );

// exposing for tests
module.exports = {
	PageModel: require( './PageModel.js' ),
	PagelistModel: require( './PagelistModel.js' )
};
