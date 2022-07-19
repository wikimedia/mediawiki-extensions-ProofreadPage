( function () {
	var Toolbar = require( './EditInSequence.Toolbar.js' );
	var toolFactory = new OO.ui.ToolFactory(),
		toolGroupFactory = new OO.ui.ToolGroupFactory(),
		toolbar = new Toolbar( toolFactory, toolGroupFactory ),
		// eslint-disable-next-line no-jquery/no-global-selector
		$content = $( '#content' );
	if ( $content.length ) {
		$content.prepend( toolbar.$element );
	}
}() );
