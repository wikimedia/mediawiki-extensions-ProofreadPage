/**
 * Programmatically generate classes for the various page statuses
 *
 * @param {string} name Name of the tool
 * @param {string} title Title of the tool
 * @param {number} pageStatus Page status that must set when the tool is clicked
 * @return {Object} PageStatusTool class
 */
function createPageStatusTool( name, title, pageStatus ) {
	/**
	 * PageStatusTool class that implements all the tools in the Page status dropdown
	 *
	 * @class
	 */
	const PageStatusTool = function () {
		PageStatusTool.super.apply( this, arguments );
		this.isCurrentSelection = false;
		this.pageStatus = pageStatus;
	};

	OO.inheritClass( PageStatusTool, OO.ui.Tool );

	PageStatusTool.static.name = name;
	PageStatusTool.static.title = title;
	PageStatusTool.static.icon = 'pagequality-level' + pageStatus;
	PageStatusTool.static.displayBothIconAndLabel = true;

	PageStatusTool.prototype.onSelect = function () {
		this.isCurrentSelection = true;
		this.toolbar.emit( 'updateState' );
		this.toolbar.eis.pageModel.setPageStatus( this.pageStatus );
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
	this.toolbar.eis.pageModel.on( 'pageModelUpdated', this.onPageModelUpdated.bind( this ) );
	this.toolbar.eis.pagelistModel.on( 'pageUpdated', this.onPageUpdated.bind( this ) );
	this.toolbar.eis.pageModel.on( 'loadUnsavedEdit', this.onPageModelUpdated.bind( this ) );
	this.setDisabled( true );
	this.hasPageQualityRight = false;
	this.hasPageQualityAdminRight = false;
	this.getUserRights().then( ( rights ) => {
		this.hasPageQualityRight = rights.indexOf( 'pagequality' ) !== -1;
		this.hasPageQualityAdminRight = rights.indexOf( 'pagequality-admin' ) !== -1;
	} ).then( this.onPageModelUpdated.bind( this ) );
	this.$element.addClass( 'prp-editinsequence-page-status' );
}

OO.inheritClass( PageStatusMenu, OO.ui.MenuToolGroup );

PageStatusMenu.static.name = 'pagestatusmenu';

PageStatusMenu.prototype.getUserRights = function () {
	const api = new mw.Api();
	return api.get( {
		action: 'query',
		meta: 'userinfo',
		uiprop: 'rights'
	} ).then( ( data ) => {
		if ( data.query && data.query.userinfo && data.query.userinfo.rights ) {
			return data.query.userinfo.rights;
		} else {
			return [];
		}
	} );
};

PageStatusMenu.prototype.onUpdateState = function () {
	for ( const name in this.tools ) {
		if ( this.tools[ name ].isActive() ) {
			this.setIcon( this.tools[ name ].getIcon() );
		}
	}

	PageStatusMenu.super.prototype.onUpdateState.call( this );
};

/**
 * Set the page status when the page is updated by navigating
 */
PageStatusMenu.prototype.onPageModelUpdated = function () {
	const status = this.toolbar.eis.pageModel.getPageStatus().status,
		user = this.toolbar.eis.pageModel.getPageStatus().lastUser;
	for ( const name in this.tools ) {
		if ( this.tools[ name ].getPageStatus() === status ) {
			this.tools[ name ].setActive( true );
			this.tools[ name ].onSelect();
		}

		if ( name === 'validated' ) {
			if ( this.hasPageQualityAdminRight ||
				( status === 3 && user !== mw.config.get( 'wgUserName' ) ) ) {
				this.tools[ name ].setDisabled( false );
			} else {
				this.tools[ name ].setDisabled( true );
			}
		}
	}
	if ( this.hasPageQualityRight ) {
		this.setDisabled( false );
	}
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
const pageStatuses = [
	createPageStatusTool( 'without_text', mw.msg( 'proofreadpage_quality0_summary' ), 0 ),
	createPageStatusTool( 'not_proofread', mw.msg( 'proofreadpage_quality1_summary' ), 1 ),
	createPageStatusTool( 'problematic', mw.msg( 'proofreadpage_quality2_summary' ), 2 ),
	createPageStatusTool( 'proofread', mw.msg( 'proofreadpage_quality3_summary' ), 3 ),
	createPageStatusTool( 'validated', mw.msg( 'proofreadpage_quality4_summary' ), 4 ) ];

module.exports = {
	pageStatuses: pageStatuses,
	PageStatusMenu: PageStatusMenu
};
