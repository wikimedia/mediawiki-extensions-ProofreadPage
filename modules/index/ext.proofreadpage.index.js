$( function () {
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '.prp-fieldLayout-help' ).each( function () {
		OO.ui.infuse( this );
	} );

	// Categories
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '#wpPrpCategories' ).each( function () {
		var categoryInputWidget = OO.ui.infuse( this.parentNode ),
			categorySelector = new mw.widgets.CategoryMultiselectWidget( {} );

		categorySelector.setValue( categoryInputWidget.getValue().split( '|' ).filter( function ( value ) {
			return value !== '';
		} ) );
		categorySelector.on( 'change', function () {
			categoryInputWidget.setValue( categorySelector.getValue().join( '|' ) );
		} );

		categoryInputWidget.$element.before( categorySelector.$element );
		categoryInputWidget.toggle( false );
	} );
} );
