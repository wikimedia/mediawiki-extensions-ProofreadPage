/**
 * MediaWiki UserInterface pagequality tool.
 *
 * @class
 * @extends ve.ui.FragmentInspectorTool
 *
 * @constructor
 */
ve.ui.MWPagequalityInspectorTool = function VeUiMWPagequalityInspectorTool() {
	ve.ui.MWPagesInspectorTool.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.ui.MWPagequalityInspectorTool, ve.ui.FragmentInspectorTool );

/* Static properties */
ve.ui.MWPagequalityInspectorTool.static.name = 'pagequality';

ve.ui.MWPagequalityInspectorTool.static.autoAddToCatchall = false;

ve.ui.MWPagequalityInspectorTool.static.icon = 'clip';

ve.ui.MWPagequalityInspectorTool.static.title = OO.ui.deferMsg( 'proofreadpage-visualeditor-node-pagequality-inspector-tooltip' );

ve.ui.MWPagequalityInspectorTool.static.modelClasses = [ ve.dm.MWPagequalityNode ];

ve.ui.MWPagequalityInspectorTool.static.commandName = 'pagequality';

/* Registration */
ve.ui.toolFactory.register( ve.ui.MWPagequalityInspectorTool );

ve.ui.commandRegistry.register(
	new ve.ui.Command(
		'pagequality', 'window', 'open',
		{ args: [ 'pagequality' ], supportedSelections: [ 'linear' ] }
	)
);
