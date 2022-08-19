/**
 * Programmatically generate classes for the various page statuses
 *
 * @param {string} name Name of the tool
 * @param {string} title Title of the tool
 * @param {number} pageStatus Page status that must set when the tool is clicked
 * @return {object} PageStatusTool class
 */
function createPageStatusTool( name, title, pageStatus ) {
	/**
	 * PageStatusTool class that implements all the tools in the Page status dropdown
	 *
	 * @class
	 */
	var PageStatusTool = function () {
		PageStatusTool.super.apply( this, arguments );
		this.isCurrentSelection = false;
		this.pageStatus = pageStatus;
	};

	OO.inheritClass( PageStatusTool, OO.ui.Tool );

	PageStatusTool.static.name = name;
	PageStatusTool.static.title = title;
	PageStatusTool.static.displayBothIconAndLabel = false;

	PageStatusTool.prototype.onSelect = function () {
		this.isCurrentSelection = true;
		this.toolbar.emit( 'updateState' );
		this.toolbar.pageModel.setPageStatus( this.pageStatus );
		this.isCurrentSelection = false;
	};

	PageStatusTool.prototype.onUpdateState = function () {
		if ( !this.isCurrentSelection ) {
			this.setActive( false );
		}
	};

	PageStatusTool.prototype.getPageStatus = function () {
		return this.pageStatus;
	};

	return PageStatusTool;
}
/**
 * Implements the dropdown menu for PageStatuses
 */
function PageStatusMenu() {
	PageStatusMenu.super.apply( this, arguments );
	this.toolbar.pageModel.on( 'pageModelUpdated', this.onPageModelUpdated.bind( this ) );
	this.toolbar.pagelistModel.on( 'pageUpdated', this.onPageUpdated.bind( this ) );
	this.setDisabled( true );
	this.$element.addClass( 'prp-editinsequence-page-status' );
}

OO.inheritClass( PageStatusMenu, OO.ui.MenuToolGroup );

PageStatusMenu.static.name = 'pagestatusmenu';

/**
 * Set the page status when the page is updated by navigating
 */
PageStatusMenu.prototype.onPageModelUpdated = function () {
	var status = this.toolbar.pageModel.getPageStatus().status, name;
	for ( name in this.tools ) {
		if ( this.tools[ name ].getPageStatus() === status ) {
			this.tools[ name ].setActive( true );
			this.tools[ name ].onSelect();
		}
	}
	this.setDisabled( false );
};

/**
 * Navigation has been started, prevented page status changes and
 * set the page status to null
 */
PageStatusMenu.prototype.onPageUpdated = function () {
	this.setLabel( '' );
	this.setDisabled( true );
};

/**
 * Array of PageStatusTools which will be added to the toolbar
 */
var pageStatuses = [
	createPageStatusTool( 'without_text', mw.msg( 'proofreadpage_quality0_summary' ), 0 ),
	createPageStatusTool( 'not_proofread', mw.msg( 'proofreadpage_quality1_summary' ), 1 ),
	createPageStatusTool( 'problematic', mw.msg( 'proofreadpage_quality2_summary' ), 2 ),
	createPageStatusTool( 'proofread', mw.msg( 'proofreadpage_quality3_summary' ), 3 ),
	createPageStatusTool( 'validated', mw.msg( 'proofreadpage_quality4_summary' ), 4 ) ];

module.exports = {
	pageStatuses: pageStatuses,
	PageStatusMenu: PageStatusMenu
};
