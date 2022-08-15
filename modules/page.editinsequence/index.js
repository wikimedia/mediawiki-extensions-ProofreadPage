( function () {
	var Toolbar = require( './Toolbar.js' );
	var PageNavTools = require( './PageNavTools.js' );
	var toolFactory = new OO.ui.ToolFactory(),
		toolGroupFactory = new OO.ui.ToolGroupFactory(),
		toolbar = new Toolbar( toolFactory, toolGroupFactory, {
			classes: [ 'prp-edit-in-sequence-toolbar' ]
		} ),
		// eslint-disable-next-line no-jquery/no-global-selector
		$content = $( '#content' );

	toolFactory.register( PageNavTools.PrevTool );
	toolFactory.register( PageNavTools.NextTool );

	toolbar.setup( [
		{
			type: 'bar',
			include: [ 'prev', 'next' ]
		}
	] );

	if ( $content.length ) {
		$content.prepend( toolbar.$element );
	}

	toolbar.initialize();
}() );

// exposing for tests
module.exports = {
	PageModel: require( './PageModel.js' ),
	PagelistModel: require( './PagelistModel.js' )
};
