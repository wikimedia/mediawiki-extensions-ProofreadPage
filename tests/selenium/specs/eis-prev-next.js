import assert from 'assert';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import EisPagePage from '../pageobjects/eispage.page.js';
import { createApiClient } from 'wdio-mediawiki/Api.js';
import utils from '../util/eis-util.js';

// eslint-disable-next-line no-underscore-dangle
const __dirname = path.dirname( fileURLToPath( import.meta.url ) );

describe( 'For a page with eis enabled', () => {
	let api;
	before( async () => {
		api = await createApiClient();
		await utils.setupPrpTemplates( api );
		await utils.uploadFile( api, 'File:LoremIpsum.djvu', path.join( __dirname, '../../data/media/LoremIpsum.djvu' ), 'Selenium test initialization for edit-in-sequence' );
		await api.edit( 'Index:LoremIpsum.djvu', '{{:MediaWiki:Proofreadpage_index_template|Type=book\n|Source=_empty_\n|Image=1\n|Progress=X\n|Pages=<pagelist />\n}}', 'Selenium test initialization for edit-in-sequence' );
	} );

	it( 'toolbar loads', async () => {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( await EisPagePage.prevButton !== undefined );
		assert.ok( await EisPagePage.nextButton !== undefined );

	} );

	it( 'prev to page 1', async () => {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.prevButton ) === false, 'is disabled' );
		await EisPagePage.nextButton.click();
		await EisPagePage.waitForOOUIElementToBeActive( await EisPagePage.prevButton );
		assert.ok( await utils.isEnabledInOOUI( EisPagePage.prevButton ), 'but gets enabled when next is clicked' );
	} );

	it( 'next to last page', async () => {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/5' );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.nextButton ) === false, 'is disabled' );
		await EisPagePage.prevButton.click();
		await EisPagePage.waitForOOUIElementToBeActive( await EisPagePage.nextButton );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.nextButton ), 'but gets enabled when prev is clicked' );
	} );

	after( async () => {
		await api.delete( 'Index:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
		await api.delete( 'File:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
	} );

} );
