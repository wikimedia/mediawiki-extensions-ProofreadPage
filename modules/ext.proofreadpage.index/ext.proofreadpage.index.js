// Author : ThomasV - License : GPL

// the index template is a system message.
// another message in i18n lists the parameters

jQuery( function( $ ) {

	$( 'input.prp-input-page' ).autocomplete( {
		source: function( request, response ) {
			$.getJSON(
				mw.util.wikiScript( 'api' ), {
					'action': 'opensearch',
					'search': request.term
				}, function ( data ) {
					if ( $.isArray( data ) && 1 in data ) {
						response( $.map( data[1], function( item ) {
							return {
								label: item,
								value: item
							};
						}));
					}
				}
			);
		},
		minLength: 2,
		select: function(event, ui) {
			this.value = ui.item.value;
		}
	});

	// Set up the help system
	$( '.mw-help-field-data' )
		.hide()
		.closest( '.mw-help-field-container' ).find( '.mw-help-field-hint' )
			.show()
			.click( function() {
				$(this).closest( '.mw-help-field-container' ).find( '.mw-help-field-data' ).slideToggle( 'fast' );
			} );
});
