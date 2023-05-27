var PagelistModel = require( './PagelistModel.js' );
var PageSelectionWidget = require( './PageSelectionWidget.js' );

/**
 * Manages displaying the filters for the page selection UI
 *
 * @param {PagelistModel} pagelistModel
 * @param {Object} [config]
 * @class
 * @extends OO.ui.Widget
 */
function PageSelectionLayout( pagelistModel, config ) {
	PageSelectionLayout.super.call( this, config );
	this.actions = { all: { label: mw.msg( 'prp-editinsequence-page-filter-label-all' ), func: function () {} } };
	this.pagelistModel = pagelistModel;
	this.opened = false;
	this.bookletLayout = new OO.ui.BookletLayout( {
		outlined: true,
		expanded: true
	} );
	this.currentPage = 'all';
	this.pageSelectionWidgets = {};
	this.pagelistModel.on( 'pageUpdated', this.onPageUpdated.bind( this ) );
	this.bookletLayout.on( 'set', this.onFilterSelected.bind( this ) );
	this.$element.addClass( 'prp-eis-page-selection-container' );
	this.bookletLayout.$element.addClass( [ 'prp-eis-page-selection-widget', 'prp-eis-page-selection-widget-closed' ] );
	this.$element.append( this.bookletLayout.$element );
}

OO.inheritClass( PageSelectionLayout, OO.ui.Widget );

/**
 * Register a specific filter action
 *
 * @param {string} name Name of the operation
 * @param {Object} config A object/dictionary containing information about the filter
 * @param {string} config.label Label for the filter
 * @param {Function} config.func Function to be called when the filter is selected
 * @public
 */
PageSelectionLayout.prototype.register = function ( name, config ) {
	this.actions[ name ] = config;
};

/**
 * Display the actions/filters in the UI
 *
 * @public
 */
PageSelectionLayout.prototype.initialize = function () {
	var keys = Object.keys( this.actions );
	this.pages = [];
	for ( var i = 0; i < keys.length; i++ ) {
		var action = this.actions[ keys[ i ] ],
			pageSelectionWidget = new PageSelectionWidget( this.pagelistModel );
		this.pageSelectionWidgets[ keys[ i ] ] = pageSelectionWidget;
		this.pages.push(
			new PageSelectionPage( keys[ i ], {
				label: action.label,
				icon: action.icon,
				pageSelectionWidget: pageSelectionWidget.$element
			} )
		);
	}

	this.bookletLayout.addPages( this.pages );
};

PageSelectionLayout.prototype.onPageUpdated = function () {
	this.actions[ this.currentPage ].func( this.pagelistModel.getPagelistData(), this.pageSelectionWidgets[ this.currentPage ] );
};

PageSelectionLayout.prototype.onFilterSelected = function ( page ) {
	this.actions[ page.getName() ].func( this.pagelistModel.getPagelistData(), this.pageSelectionWidgets[ page.getName() ] );
	this.currentPage = page.getName();
};

PageSelectionLayout.prototype.toggle = function () {
	this.opened = !this.opened;
	this.bookletLayout.$element.toggleClass( 'prp-eis-page-selection-widget-open', this.opened );
	this.bookletLayout.$element.toggleClass( 'prp-eis-page-selection-widget-closed', !this.opened );
};

/**
 * Implements the UI for a specific filter
 *
 * @param {string} name name of filter
 * @param {Object} config configuration for the filter
 * @class
 * @extends OO.ui.PageLayout
 */
function PageSelectionPage( name, config ) {
	PageSelectionPage.super.call( this, name, config );
	this.label = config.label;
	this.icon = config.icon;
	this.$element.addClass( 'prp-eis-page-selection-page' );
	this.$element.append( config.pageSelectionWidget );
}

OO.inheritClass( PageSelectionPage, OO.ui.PageLayout );

PageSelectionPage.prototype.setupOutlineItem = function () {
	PageSelectionPage.super.prototype.setupOutlineItem.call( this );
	this.outlineItem
		.setIcon( this.icon )
		.setLabel( this.label );
};

module.exports = PageSelectionLayout;
