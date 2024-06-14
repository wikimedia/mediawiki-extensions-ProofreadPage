/**
 * @class
 * @extends OO.ui.RadioSelectInputWidget
 *
 * @constructor
 * @param {Object} [config] Configuration options
 */
function PageQualityInputWidget( config ) {
	PageQualityInputWidget.super.call( this, config );

	this.connect( this, { change: 'updateSummaryPrefix' } );

	this.radioSelectWidget.items.forEach( ( e ) => {
		// Add two classes that were added PHP-side (to a different structure).
		// The following class names may be added here:
		// * quality0
		// * quality1
		// * quality2
		// * quality3
		// * quality4
		e.radio.$element.addClass( 'prp-quality-radio quality' + e.radio.getValue() );
	} );
}

OO.inheritClass( PageQualityInputWidget, OO.ui.RadioSelectInputWidget );

/**
 * @inheritdoc
 */
PageQualityInputWidget.prototype.setDisabled = function ( disabled ) {
	PageQualityInputWidget.super.prototype.setDisabled.call( this, disabled );
	this.radioSelectWidget.items.forEach( ( e ) => {
		e.setDisabled( disabled );
	} );
};

/**
 * Update the prefix in the edit-summary input field when the quality is changed.
 */
PageQualityInputWidget.prototype.updateSummaryPrefix = function () {
	// eslint-disable-next-line no-jquery/no-global-selector
	const $summary = $( 'input#wpSummary, #wpSummary > input' ),
		// The following messages are used here:
		// * proofreadpage_quality0_summary
		// * proofreadpage_quality1_summary
		// * proofreadpage_quality2_summary
		// * proofreadpage_quality3_summary
		// * proofreadpage_quality4_summary
		pageQuality = mw.message( 'proofreadpage_quality' + this.value + '_summary' ).plain(),
		summary = $summary.val().replace( /\/\*.*\*\/\s?/, '' );
	$summary.val( '/* ' + pageQuality + ' */ ' + summary );
};

module.exports = PageQualityInputWidget;
