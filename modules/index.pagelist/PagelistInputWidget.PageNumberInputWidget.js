/**
 * A OOUI widget used to enter the page nuber type,
 * that uses values stored at MediaWiki:Proofreadpage_pagelist_dropdown_values.json
 * to provide suggestions of labels for scans.
 *
 * @param {Object} config
 */
function PageNumberInputWidget( config ) {
	config = config || {};
	config.classes = ( config.classes &&
		config.classes.push( 'prp-pagelist-dialog-visual-page-number-input' ) ) ||
		[ 'prp-pagelist-dialog-visual-page-number-input' ];
	PageNumberInputWidget.super.call( this, config );
	this.api = new mw.Api();
	this.numberTypeConfig = null;
	this.getNumberTypeConfig().then( this.asyncSetup.bind( this ) );

	this.pageNumberTypeInput = new OO.ui.ComboBoxInputWidget( {
		// Some sort of limit...
		maxLength: 65
	} );

	this.pageNumberTypeInput.connect( this, {
		change: 'updateFormPanel'
	} );

	this.messageWidget = new OO.ui.MessageWidget( {
		inline: true,
		type: 'warning',
		classes: [ 'prp-pagelist-dialog-config-not-found-message' ]
	} );
	this.messageWidget.toggle( false );

	this.$element.append( this.pageNumberTypeInput.$element, this.messageWidget.$element );
	this.$element.addClass( 'prp-pagelist-page-number-input-widget' );
}

OO.inheritClass( PageNumberInputWidget, OO.ui.Widget );

/**
 * Sets up the suggestions asynchronously
 * seperately from the constructor
 *
 * @param  {Object} response
 */
PageNumberInputWidget.prototype.asyncSetup = function ( response ) {
	this.numberTypeConfig = response;
	this.pageNumberTypeInput.setOptions( this.numberTypeConfig );
};

/**
 * Get's current value of the Combobox Input
 *
 * @return {string} value
 */
PageNumberInputWidget.prototype.getValue = function () {
	return this.pageNumberTypeInput.getValue();
};

/**
 * Set's current value of the Combobox Input
 *
 * @param {string} type
 */
PageNumberInputWidget.prototype.setValue = function ( type ) {
	if ( type ) {
		this.pageNumberTypeInput.setValue( type );
		this.pageNumberTypeInput.menu.toggle( false );
	}
};

/**
 * Retrieves the suggestions based on MediaWiki:Proofreadpage pagelist dropdown values.json
 *
 * @return {jQuery.Promise}
 */
PageNumberInputWidget.prototype.getNumberTypeConfig = function () {
	var promise = this.api.get( {
		action: 'query',
		prop: 'revisions',
		titles: 'MediaWiki:Proofreadpage pagelist dropdown values.json',
		rvslots: '*',
		rvprop: 'content',
		formatversion: '2'
	} ).then( function ( response ) {
		try {
			return JSON.parse( response.query.pages[ 0 ].revisions[ 0 ].slots.main.content );
		} catch ( e ) {
			this.messageWidget.setLabel( mw.msg( 'proofreadpage-pagelist-dialog-visual-config-not-found-message' ) );
			this.messageWidget.toggle( true );
			return [
				{
					label: 'highroman',
					data: 'highroman'
				},
				{
					label: 'roman',
					data: 'roman'
				},
				{
					label: 'number',
					data: 'Number'
				}
			];
		}
	}.bind( this ) );
	return promise;
};

/**
 * Emits events based on the current value of the ComboboxInput
 */
PageNumberInputWidget.prototype.updateFormPanel = function () {
	var data = this.pageNumberTypeInput.getValue();

	// Check if the label is one that is provided by ProofreadPage by default
	// and changes the number format being displayed.
	if ( mw.config.get( 'prpPagelistBuiltinLabels' ) &&
	mw.config.get( 'prpPagelistBuiltinLabels' ).indexOf( data.label ) === -1 && data.label !== 'empty' ) {
		if ( data === 'Number' ) {
			this.emit( 'changedToSinglePageIncompatibleValue' );
		} else {
			this.emit( 'changedToNumberingCompatibleValue' );
		}
	} else {
		this.emit( 'changedToNumberingIncompatibleValue' );
	}
};

module.exports = PageNumberInputWidget;
