'use strict';

const assert = require( 'assert' ),
	IndexPage = require( '../pageobjects/index.page' );

describe( 'Pages in the Index namespace', function () {

	it( 'load the custom editing form in edit mode', async function () {
		await IndexPage.open();

		// inputs and textareas load
		assert( await IndexPage.formFields.length > 0 );

	} );

} );
