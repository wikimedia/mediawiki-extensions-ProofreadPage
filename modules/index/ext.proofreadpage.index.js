$( () => {
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '.prp-fieldLayout-help' ).each( function () {
		OO.ui.infuse( this );
	} );

	// Categories
	// eslint-disable-next-line no-jquery/no-global-selector
	$( '#wpPrpCategories' ).each( function () {
		const categoryInputWidget = OO.ui.infuse( this.parentNode ),
			categorySelector = new mw.widgets.CategoryMultiselectWidget( {} );

		categorySelector.setValue( categoryInputWidget.getValue().split( '|' ).filter( ( value ) => value !== '' ) );
		categorySelector.on( 'change', () => {
			categoryInputWidget.setValue( categorySelector.getValue().join( '|' ) );
		} );

		categoryInputWidget.$element.before( categorySelector.$element );
		categoryInputWidget.toggle( false );
	} );
} );
