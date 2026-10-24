import IndexPage from '../pageobjects/index.page.js';

describe( 'Pages in the Index namespace', () => {

	it( 'load the custom editing form in edit mode', async () => {
		await IndexPage.open();

		// inputs and textareas load
		await expect( IndexPage.formFields ).toBeElementsArrayOfSize( { gte: 1 } );

	} );

} );
