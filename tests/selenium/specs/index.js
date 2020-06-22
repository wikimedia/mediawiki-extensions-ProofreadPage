'use strict';

const assert = require( 'assert' ),
	IndexPage = require( '../pageobjects/index.page' );

describe( 'Pages in the Index namespace', function () {

	it( 'load the custom editing form in edit mode', function () {
		IndexPage.open();

		// inputs and textareas load
		assert( IndexPage.formFields.length > 0 );

	} );

} );
