/**
 * MediaWiki UserInterface pages tool.
 *
 * @class
 * @extends ve.ui.FragmentInspectorTool
 *
 * @constructor
 */
ve.ui.MWPagesInspectorTool = function VeUiMWPagesInspectorTool() {
	ve.ui.MWPagesInspectorTool.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.ui.MWPagesInspectorTool, ve.ui.FragmentInspectorTool );

/* Static properties */
ve.ui.MWPagesInspectorTool.static.name = 'pages';

ve.ui.MWPagesInspectorTool.static.group = 'object';

ve.ui.MWPagesInspectorTool.static.icon = 'articles';

ve.ui.MWPagesInspectorTool.static.title = OO.ui.deferMsg( 'proofreadpage-visualeditor-node-pages-inspector-tooltip' );

ve.ui.MWPagesInspectorTool.static.modelClasses = [ ve.dm.MWPagesNode ];

ve.ui.MWPagesInspectorTool.static.commandName = 'pages';

/* Registration */
ve.ui.toolFactory.register( ve.ui.MWPagesInspectorTool );

ve.ui.commandRegistry.register(
	new ve.ui.Command(
		'pages', 'window', 'open',
		{ args: [ 'pages' ], supportedSelections: [ 'linear' ] }
	)
);
