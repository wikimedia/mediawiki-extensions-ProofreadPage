'use strict';

const assert = require( 'assert' );
const EisPagePage = require( '../pageobjects/eispage.page' );
const utils = require( '../util/eis-util' );
const path = require( 'path' );
const MWBot = require( 'mwbot' );

const bot = new MWBot( {
	apiUrl: browser.config.baseUrl + '/api.php'
} );

describe( 'For a page with eis enabled', function () {
	before( async function () {
		await utils.setupPrpTemplates( bot );
		await bot.uploadOverwrite( 'File:LoremIpsum.djvu', path.join( __dirname, '../../data/media/LoremIpsum.djvu' ), 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Index:LoremIpsum.djvu', '{{:MediaWiki:Proofreadpage_index_template|Type=book\n|Source=_empty_\n|Image=1\n|Progress=X\n|Pages=<pagelist />\n}}', 'Selenium test initialization for edit-in-sequence' );
	} );

	it( 'toolbar loads', async function () {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( await EisPagePage.prevButton !== undefined );
		assert.ok( await EisPagePage.nextButton !== undefined );

	} );

	it( 'prev to page 1', async function () {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.prevButton ) === false, 'is disabled' );
		await EisPagePage.nextButton.click();
		await EisPagePage.waitForOOUIElementToBeActive( await EisPagePage.prevButton );
		assert.ok( await utils.isEnabledInOOUI( EisPagePage.prevButton ), 'but gets enabled when next is clicked' );
	} );

	it( 'next to last page', async function () {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/5' );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.nextButton ) === false, 'is disabled' );
		await EisPagePage.prevButton.click();
		await EisPagePage.waitForOOUIElementToBeActive( await EisPagePage.nextButton );
		assert.ok( await utils.isEnabledInOOUI( await EisPagePage.nextButton ), 'but gets enabled when prev is clicked' );
	} );

	after( async function () {
		await bot.delete( 'Index:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'File:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
	} );

} );
