// Author : ThomasV - License : GPL

// the index template is a system message.
// another message in i18n lists the parameters

jQuery( function( $ ) {

	// Set up the help system
	$( '.mw-help-field-data' )
		.hide()
		.closest( '.mw-help-field-container' ).find( '.mw-help-field-hint' )
			.show()
			.click( function() {
				$(this).closest( '.mw-help-field-container' ).find( '.mw-help-field-data' ).slideToggle( 'fast' );
			} );
});
