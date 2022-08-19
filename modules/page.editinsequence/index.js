( function () {
	var Toolbar = require( './Toolbar.js' );
	var PageNavTools = require( './PageNavTools.js' );
	var PreviewTool = require( './PreviewTool.js' );
	var PageStatusTools = require( './PageStatusTools.js' );
	var toolFactory = new OO.ui.ToolFactory(),
		toolGroupFactory = new OO.ui.ToolGroupFactory(),
		toolbar = new Toolbar( toolFactory, toolGroupFactory, {
			classes: [ 'prp-edit-in-sequence-toolbar' ]
		} ),
		// eslint-disable-next-line no-jquery/no-global-selector
		$content = $( '#content' );
	toolFactory.register( PageNavTools.PrevTool );
	toolFactory.register( PageNavTools.NextTool );
	toolFactory.register( PreviewTool );
	toolGroupFactory.register( PageStatusTools.PageStatusMenu );
	PageStatusTools.pageStatuses.forEach( function ( elem ) {
		toolFactory.register( elem );
	} );

	toolbar.setup( [
		{
			type: 'bar',
			include: [ 'prev', 'next', 'preview' ]
		},
		{
			type: 'pagestatusmenu',
			include: [ 'without_text', 'not_proofread', 'problematic', 'proofread', 'validated' ]
		}
	] );

	if ( $content.length ) {
		$content.prepend( toolbar.$element );
	}

	toolbar.initialize();
	toolbar.emit( 'updateState' );
}() );

// exposing for tests
module.exports = {
	PageModel: require( './PageModel.js' ),
	PagelistModel: require( './PagelistModel.js' )
};
