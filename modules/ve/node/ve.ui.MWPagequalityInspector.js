/**
 * MediaWiki pagequality tag inspector.
 *
 * @class
 * @extends ve.ui.MWExtensionInspector
 *
 * @constructor
 */
ve.ui.MWPagequalityInspector = function VeUiMWPagequalityInspector() {
	// Parent constructor
	ve.ui.MWPagequalityInspector.super.apply( this, arguments );

	this.validatedLevelRemoved = false;
};

/* Inheritance */
OO.inheritClass( ve.ui.MWPagequalityInspector, ve.ui.MWExtensionInspector );

/* Static properties */
ve.ui.MWPagequalityInspector.static.name = 'pagequality';

ve.ui.MWPagequalityInspector.static.title = OO.ui.deferMsg( 'proofreadpage-visualeditor-node-pagequality-inspector-title' );

ve.ui.MWPagequalityInspector.static.modelClasses = [ ve.dm.MWPagequalityNode ];

ve.ui.MWPagequalityInspector.static.allowedEmpty = true;

/* Methods */
/**
 * @inheritdoc
 */
ve.ui.MWPagequalityInspector.prototype.initialize = function () {
	// Parent method
	ve.ui.MWPagequalityInspector.super.prototype.initialize.apply( this, arguments );

	this.buildQualitySelector();
	this.form.$element.html( this.qualitySelector.$element );
};

/**
 * Creates the quality selector
 */
ve.ui.MWPagequalityInspector.prototype.buildQualitySelector = function () {
	var i = [];

	this.qualityOptions = [];
	for ( i = 0; i <= 4; i++ ) {
		this.qualityOptions[ i ] = new OO.ui.ButtonOptionWidget( {
			data: i,
			icon: 'prp-quality' + i,
			title: OO.ui.msg( 'proofreadpage_quality' + i + '_category' )
		} );
	}

	this.qualitySelector = new OO.ui.ButtonSelectWidget( {
		items: this.qualityOptions,
		classes: [ 'center' ]
	} );

	this.qualitySelector.on( 'choose', this.onChangeHandler );
};

/**
 * @inheritdoc
 */
ve.ui.MWPagequalityInspector.prototype.getSetupProcess = function ( data ) {
	// Parent method
	return ve.ui.MWPagequalityInspector.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			var currentLevel;
			this.mwData = ( this.selectedNode !== null && this.selectedNode.getAttribute( 'mw' ) ) ||
				{ attrs: {} };
			currentLevel = parseInt( this.mwData.attrs.level );

			if ( this.couldNotValidatePage( currentLevel, this.mwData.attrs.user ) ) {
				this.validatedLevelRemoved = true;
				this.qualitySelector.removeItems( [ this.qualityOptions[ 4 ] ] );
			}
			this.qualitySelector.selectItem( this.qualityOptions[ currentLevel ] );
		}, this );
};

/**
 * Does the user could not validate the page
 *
 * @param {int} currentLevel current proofreading level
 * @param {string} currentUser last user who set the proofreading level
 * @return {boolean}
 */
ve.ui.MWPagequalityInspector.prototype.couldNotValidatePage = function ( currentLevel, currentUser ) {
	return currentLevel < 3 || ( currentLevel === 3 && mw.user.getName() === currentUser );
};

/**
 * @inheritdoc
 */
ve.ui.MWPagequalityInspector.prototype.getTeardownProcess = function ( data ) {
	// Parent method
	return ve.ui.MWPagequalityInspector.super.prototype.getTeardownProcess.call( this, data )
		.next( function () {
			if ( this.validatedLevelRemoved ) {
				this.qualitySelector.addItems( [ this.qualityOptions[ 4 ] ] );
			}
			this.validatedLevelRemoved = false;
		}, this );
};

/**
 * @inheritdoc
 */
ve.ui.MWPagequalityInspector.prototype.updateMwData = function ( mwData ) {
	var selectedQualityLevel = this.getSelectedQualityLevel();

	// Parent method
	ve.ui.MWPagequalityInspector.super.prototype.updateMwData.call( this, mwData );

	mwData.attrs = mwData.attrs || {};

	if ( selectedQualityLevel === null || selectedQualityLevel === parseInt( mwData.attrs.level ) ) {
		return;
	}

	mwData.attrs.level = selectedQualityLevel.toString();
	mwData.attrs.user = mw.user.getName();
};

/**
 * Retrieve the selected level
 *
 * @return {int|null}
 */
ve.ui.MWPagequalityInspector.prototype.getSelectedQualityLevel = function () {
	var selectedQualityOption = this.qualitySelector.findSelectedItem();
	if ( selectedQualityOption === null ) {
		return null;
	}
	return selectedQualityOption.getData();
};

/* Registration */
ve.ui.windowFactory.register( ve.ui.MWPagequalityInspector );
