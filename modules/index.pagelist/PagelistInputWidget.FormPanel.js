/**
 * Panel that shows current page number status
 * and provides a form to change the page number.
 *
 * @param {mw.proofreadpage.PagelistInputWidget.PageModel} pageModel
 * @param {Object} config config variable for PanelLayout
 * @class
 */
function FormPanel( pageModel, config ) {
	config = config || {};
	config.padded = true;
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-form-panel' ) ) ||
		[ 'prp-pagelist-dialog-form-panel' ];
	FormPanel.super.call( this, config );

	this.data = null;
	this.pageModel = pageModel;
	this.pageModel.connect( this, {
		aftersetpagedata: 'setPageData'
	} );
	this.$messages = $( '<div>' );
	this.$element.append( this.$messages );
}
OO.inheritClass( FormPanel, OO.ui.PanelLayout );

/**
 * Sets page data and creates and appends messages
 *
 * @param {Object} data
 */
FormPanel.prototype.setPageData = function ( data ) {
	var messageArr = [];
	this.$messages.empty();
	this.data = data;
	messageArr.push( mw.msg( 'proofreadpage-pagelist-type', data.type ) );
	if ( data.notCreated ) {
		messageArr.push( mw.msg( 'proofreadpage-pagelist-notcreated' ) );
	} else {
		messageArr.push( mw.msg( 'proofreadpage-pagelist-quality', data.quality ) );
	}
	if ( data.assignedPageNumber ) {
		messageArr.push( mw.msg( 'proofreadpage-pagelist-assignedpagenumber', data.assignedPageNumber ) );
	}
	this.$messages.append( messageArr.join( '<br>' ) );
};

module.exports = FormPanel;
