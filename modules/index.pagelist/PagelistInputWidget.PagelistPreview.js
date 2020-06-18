/**
 * A module to display parsed pagelists and error messages on the same
 * @param {Object} model Instance of PagelistInputWidget.Model
 * @param {Object} config configurations for the parent OO.ui.Widget class
 * @class
 */
function PagelistPreview( model, config ) {
	PagelistPreview.super.call( this, config );

	this.model = model;
	this.buttonSelectWidget = new OO.ui.ButtonSelectWidget( {
		classes: [ 'prp-pagelist-preview' ]
	} );
	this.messages = new OO.ui.MessageWidget( {
		inline: true,
		type: 'error',
		classes: [ 'prp-pagelist-preview-parsing-error' ]
	} );
	this.model.connect( this, {
		parsingerror: 'displayError',
		enumeratedListCreated: 'updatePreview'
	} );

	this.$element.append( this.messages.$element, this.buttonSelectWidget.$element );
	this.buttonSelectWidget.toggle( false );
	this.messages.toggle( false );
}

OO.inheritClass( PagelistPreview, OO.ui.Widget );

/**
 * Updates the preview of pagelist
 * @param  {Array} parameters list of Objects containing info for generating preview
 * @event previewDisplayed preview displayed succesfully
 */
PagelistPreview.prototype.updatePreview = function ( parameters ) {
	var buttonArray = [], button, i;
	this.messages.toggle( false );
	this.buttonSelectWidget.toggle( true );
	this.buttonSelectWidget.clearItems();

	for ( i = 0; i < parameters.length; i++ ) {
		// haphazardly put together, figure out the specifics later
		button = new OO.ui.ButtonOptionWidget( {
			label: $( '<span>' ).html( parameters[ i ].text ),
			data: parameters[ i ],
			title: parameters[ i ].type,
			flags: [ 'progressive' ],
			framed: false
		} );

		buttonArray.push( button );
	}

	this.buttonSelectWidget.addItems( buttonArray );
	this.emit( 'previewDisplayed' );
};

/**
 * Dislay error messages as a result of parsing pagelist
 * @param  {string} message message id
 * @event errorDisplayed Error message displayed succesfully
 */
PagelistPreview.prototype.displayError = function () {
	var args = [].slice.call( arguments, 0 );
	this.buttonSelectWidget.toggle( false );
	this.messages.toggle( true );
	// pass on whatever arguments passed to the mw.msg function
	this.messages.setLabel( mw.msg.apply( null, args ) );
	this.emit( 'errorDisplayed' );
};
module.exports = PagelistPreview;
