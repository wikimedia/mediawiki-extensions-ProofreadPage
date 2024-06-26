const EditInSequence = require( './EditInSequence.js' );
const PageNavTools = require( './PageNavTools.js' );
const PreviewTool = require( './PreviewTool.js' );
const PageStatusTools = require( './PageStatusTools.js' );
const SaveTool = require( './SaveTool.js' );
const SaveOptionsTool = require( './SaveOptionsTool.js' );
const PageSelectionTool = require( './PageSelectionTool.js' );

/**
 * EditInSequence specific modifications to the standard toolbar
 *
 * @param {EditInSequence} editInSequence
 */
function Toolbar( editInSequence ) {
	Toolbar.super.apply( this, Array.prototype.slice.call( arguments, 1 ) );
	this.$element.addClass( 'prp-editinsequence-child-toolbar' );
	this.eis = editInSequence;
}

OO.inheritClass( Toolbar, OO.ui.Toolbar );

/**
 * Panel holding both the left and right toolbar for EditInSequence
 *
 * @class
 */
function EisToolbarPanel() {
	EisToolbarPanel.super.apply( this, {
		expanded: false
	} );

	this.toolFactory = new OO.ui.ToolFactory();
	this.toolGroupFactory = new OO.ui.ToolGroupFactory();

	this.toolFactory.register( PageNavTools.PrevTool );
	this.toolFactory.register( PageNavTools.NextTool );
	this.toolFactory.register( PreviewTool );
	this.toolFactory.register( SaveTool );
	this.toolFactory.register( SaveOptionsTool );
	this.toolFactory.register( PageSelectionTool );

	this.toolGroupFactory.register( PageStatusTools.PageStatusMenu );
	PageStatusTools.pageStatuses.forEach( ( elem ) => {
		this.toolFactory.register( elem );
	} );

	this.eis = new EditInSequence();

	this.toolbar = new Toolbar( this.eis, this.toolFactory, this.toolGroupFactory, {
		classes: [ 'prp-edit-in-sequence-toolbar-left' ]
	} );

	this.toolbar.setup( [
		{
			type: 'bar',
			include: [ 'prev', 'next', 'preview' ]
		},
		{
			type: 'pagestatusmenu',
			include: [ 'without_text', 'not_proofread', 'problematic', 'proofread', 'validated' ]
		},
		{
			type: 'bar',
			include: [ 'pageSelection', 'save', 'saveOptions' ],
			align: 'after'
		}
	] );

	this.$element.addClass( 'prp-edit-in-sequence-toolbar' );

	this.$element.append( [ this.toolbar.$element, this.eis.getPageSelectionLayout().$element ] );

	this.toolbar.initialize();
	this.toolbar.emit( 'updateState' );
}

OO.inheritClass( EisToolbarPanel, OO.ui.PanelLayout );

module.exports = EisToolbarPanel;
