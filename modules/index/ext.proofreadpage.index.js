mw.loader.using( 'mediawiki.widgets.CategoryMultiselectWidget' ).done( function () {
	$( function () {
		$( '.prp-fieldLayout-help' ).map( function () {
			return OO.ui.infuse( this );
		} );

		// Categories
		$( '#wpPrpCategories' ).map( function () {
			var categoryInputWidget = OO.ui.infuse( this.parentElement ),
				categorySelector = new mw.widgets.CategoryMultiselectWidget( {} );

			categorySelector.setValue( categoryInputWidget.getValue().split( '|' ) );
			categorySelector.on( 'change', function () {
				categoryInputWidget.setValue( categorySelector.getValue().join( '|' ) );
			} );

			categoryInputWidget.$element.before( categorySelector.$element );
			categoryInputWidget.toggle( false );
		} );
	} );
} );
