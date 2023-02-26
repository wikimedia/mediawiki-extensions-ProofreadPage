var PagelistModel = require( './PagelistModel.js' );

/**
 * Page selection widget
 *
 * @param {PagelistModel} pagelistModel
 * @param {Object} [config]
 * @class
 */
function PageSelectionWidget( pagelistModel, config ) {
	config = config || {};
	PageSelectionWidget.super.call( this, config );
	this.pagelistModel = pagelistModel;
	this.pagelistModel.on( 'pageListModelReady', this.setupPagelist.bind( this ) );
	this.pagelistModel.on( 'pageUpdated', this.onPagelistSelection.bind( this ) );
	this.pagelistButtonGroup = new OO.ui.ButtonSelectWidget();
	this.pagelistButtonGroup.on( 'choose', this.onChoose.bind( this ) );
	this.pagelistFieldLayout = new OO.ui.FieldLayout( this.pagelistButtonGroup,
		{ label: mw.msg( 'prp-editinsequence-page-selection-widget-label' ), align: 'top' }
	);
	this.$element.append( [ this.pagelistFieldLayout.$element ] );
}

OO.inheritClass( PageSelectionWidget, OO.ui.Widget );

/**
 * Setup the pagelist when the PagelistModel finishes loading the pagelist
 */
PageSelectionWidget.prototype.setupPagelist = function () {
	var pagelist = this.pagelistModel.getPagelistData(),
		pagelistButtons = [];
	for ( var i = 0; i < pagelist.length; i++ ) {
		pagelistButtons.push( new OO.ui.ButtonOptionWidget( { label: String( pagelist[ i ].formattedPageNumber ), data: pagelist[ i ].pageNumber } ) );
	}
	this.pagelistButtonGroup.addItems( pagelistButtons );
};

/**
 * When a specific button is chosen, navigate to that specific page
 *
 * @param {OO.ui.ButtonOptionWidget} item
 */
PageSelectionWidget.prototype.onChoose = function ( item ) {
	this.pagelistModel.setCurrent( item.getData() - 1 );
};

/**
 * When we navigate to a specific page, update the button UI to display the same
 */
PageSelectionWidget.prototype.onPagelistSelection = function () {
	this.pagelistButtonGroup.selectItemByData( this.pagelistModel.getCurrent().pageNumber );
};

/**
 * Highlights specific page buttons based on a highlight map passed to it
 *
 * @param {Array<boolean>} highlightMap A map of which buttons to highlight
 * @public
 */
PageSelectionWidget.prototype.setHighlightedButtons = function ( highlightMap ) {
	var pagelist = this.pagelistModel.getPagelistData();
	for ( var i = 0; i < highlightMap.length; i++ ) {
		var button = this.pagelistButtonGroup.findItemFromData( pagelist[ i ].pageNumber );
		button.toggleFramed( highlightMap[ i ] );
	}
};

module.exports = PageSelectionWidget;
