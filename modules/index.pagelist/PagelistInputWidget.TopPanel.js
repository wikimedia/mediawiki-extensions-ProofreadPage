/**
 * Panel that displays above the Image Panel and provides information about the scan.
 *
 * @param {mw.proofreadpage.PagelistInputWidget.DialogModel} dialogModel Model to coordinate the dialog UI
 * @param {Object} config
 */
function TopPanel( dialogModel, config ) {
	config = config || {};
	config.padded = true;
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-top-panel' ) ) ||
		[ 'prp-pagelist-dialog-top-panel' ];
	TopPanel.super.call( this, config );
	this.data = null;
	this.dialogModel = dialogModel;
	this.dialogModel.connect( this, {
		aftersetpagedata: 'setPageData'
	} );
	this.$messages = $( '<div>' );
	this.$buttons = $( '<div>' );
	this.$buttons.attr( 'class', 'prp-pagelist-openseadragon-nav-div' );
	this.$zoomIn = new OO.ui.ButtonWidget( {
		id: 'prp-openseadragon-zoomIn',
		icon: 'zoomIn'
	} );
	this.$zoomOut = new OO.ui.ButtonWidget( {
		id: 'prp-openseadragon-zoomOut',
		icon: 'zoomOut'
	} );
	this.$home = new OO.ui.ButtonWidget( {
		id: 'prp-openseadragon-home',
		icon: 'zoomReset'
	} );
	this.$buttons.append( this.$zoomIn.$element, this.$zoomOut.$element, this.$home.$element );
	this.$element.append( this.$messages, this.$buttons );
}
OO.inheritClass( TopPanel, OO.ui.PanelLayout );

/**
 * Wraps message in a OO.ui.MessageWidget
 *
 * @param  {string|jQuery} msg Message
 * @param  {string} icon Icon
 * @return {jQuery} $element of the OO.ui.MessageWidget
 */
TopPanel.prototype.createMessage = function ( msg, icon ) {
	return ( new OO.ui.MessageWidget( {
		label: msg,
		inline: true,
		icon: icon

	} ) ).$element;
};

/**
 * Sets page data and creates and appends messages
 *
 * @param {Object} data
 */
TopPanel.prototype.setPageData = function ( data ) {
	var messageArr = [];
	this.$messages.empty();
	this.data = data;
	messageArr.push( this.createMessage( mw.msg( 'proofreadpage-pagelist-scan-number', data.subPage ), 'article' ) );
	messageArr.push( this.createMessage( $( '<div>' ).html( mw.msg( 'proofreadpage-pagelist-display-number', data.text ) ), 'browser' ) );
	messageArr.push( this.createMessage( mw.msg( 'proofreadpage-pagelist-type', data.type ), 'tag' ) );
	if ( data.assignedPageNumber ) {
		messageArr.push( this.createMessage( mw.msg( 'proofreadpage-pagelist-assignedpagenumber', data.assignedPageNumber ) ) );
	}
	this.$messages.append( messageArr );
};

module.exports = TopPanel;
