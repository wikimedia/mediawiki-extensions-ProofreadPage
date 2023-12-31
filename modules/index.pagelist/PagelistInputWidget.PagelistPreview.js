/**
 * A module to display parsed pagelists and error messages on the same
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Model} model
 * @param {Object} config configurations for the parent OO.ui.Widget class
 * @class
 */
function PagelistPreview( model, config ) {
	PagelistPreview.super.call( this, config );
	this.model = model;
	this.selected = null;
	this.buttonArray = [];
	this.buttonSelectWidget = new OO.ui.ButtonSelectWidget( {
		classes: [ 'prp-pagelist-preview' ]
	} );
	this.messages = new OO.ui.MessageWidget( {
		inline: true,
		type: 'error',
		classes: [ 'prp-pagelist-preview-parsing-error' ]
	} );
	this.progressBar = new OO.ui.ProgressBarWidget( {
		progress: false
	} );

	this.model.connect( this, {
		parsingerror: 'displayError',
		enumeratedListCreated: 'updatePreview',
		enumeratedListGenerationStarted: 'showProgressBar'
	} );
	this.buttonSelectWidget.connect( this, {
		select: 'onSelect',
		add: 'restoreSelected'
	} );
	// hack to connect two functions to same widget and event
	this.buttonSelectWidget.connect( this, {
		select: 'saveSelected',
		add: 'hideProgressBar'
	} );
	this.model.connect( this, {
		parsingerror: 'hideProgressBar'
	} );

	this.$element.append(
		this.progressBar.$element,
		this.messages.$element,
		this.buttonSelectWidget.$element
	);
	this.progressBar.toggle( false );
	this.buttonSelectWidget.toggle( false );
	this.messages.toggle( false );
}

OO.inheritClass( PagelistPreview, OO.ui.Widget );

/**
 * Updates the preview of pagelist
 *
 * @param  {Array} parameters list of Objects containing info for generating preview
 * @event previewDisplayed preview displayed succesfully
 */
PagelistPreview.prototype.updatePreview = function ( parameters ) {
	const buttonArray = [];
	this.messages.toggle( false );
	this.buttonSelectWidget.toggle( true );
	this.buttonSelectWidget.clearItems();

	for ( let i = 0; i < parameters.length; i++ ) {
		// haphazardly put together, figure out the specifics later
		const button = new OO.ui.ButtonOptionWidget( {
			label: parameters[ i ].text,
			data: parameters[ i ],
			title: String( parameters[ i ].subPage )
		} );

		buttonArray.push( button );
	}

	this.buttonArray = buttonArray;
	this.buttonSelectWidget.addItems( buttonArray );
	this.emit( 'previewDisplayed' );
};

/**
 * Dislay error messages as a result of parsing pagelist
 *
 * @param  {string} message message id
 * @event errorDisplayed Error message displayed succesfully
 */
PagelistPreview.prototype.displayError = function () {
	const args = [].slice.call( arguments, 0 );
	this.buttonSelectWidget.toggle( false );
	this.messages.toggle( true );
	// pass on whatever arguments passed to the mw.msg function
	this.messages.setLabel( mw.msg.apply( null, args ) );
	this.emit( 'errorDisplayed' );
};

/**
 * Wrapper event handler that fires on a select event and
 * emits a 'pageselected' event. The pageselected event
 * has the same attributes as the select event.
 *
 * @event pageselected Page number selected
 */
PagelistPreview.prototype.onSelect = function () {
	// Apply takes all parameters as a array, so
	// we take the `arguments` variable and turn that
	// into a Array and then insert 'pageselected' in
	// position 0.
	const args = [].slice.call( arguments, 0 );
	args.splice( 0, 0, 'pageselected' );
	this.emit.apply( this, args );
};

PagelistPreview.prototype.saveSelected = function ( selectedItem ) {
	this.selected = selectedItem.getData();
};

/**
 * Convienience method to select a particular item without
 * triggering a 'pageselected' event
 *
 * @param {Object} data Data to select item
 */
PagelistPreview.prototype.selectItemByDataWithoutEvent = function ( data ) {
	this.buttonSelectWidget.disconnect( this, {
		select: 'onSelect'
	} );
	this.buttonSelectWidget.selectItemByData( data );
	this.buttonSelectWidget.connect( this, {
		select: 'onSelect'
	} );
};

/**
 * Restore the selected page number after regenerating the pagelist
 */
PagelistPreview.prototype.restoreSelected = function () {
	const buttonArray = this.buttonArray, selected = this.selected;

	if ( !selected ) {
		return;
	}

	for ( let i = 0; i < buttonArray.length; i++ ) {
		if ( buttonArray[ i ].getData().subPage === selected.subPage ) {
			this.selectItemByDataWithoutEvent( buttonArray[ i ].getData() );
			return;
		}
	}
};

/**
 * Shows progress bar while the pagelist data is being fetched.
 */
PagelistPreview.prototype.showProgressBar = function () {
	this.progressBar.toggle( true );
	this.buttonSelectWidget.toggle( false );
	this.messages.toggle( false );
};

/**
 * Hides the progress bar after update process has completed.
 */
PagelistPreview.prototype.hideProgressBar = function () {
	this.progressBar.toggle( false );
};

module.exports = PagelistPreview;
