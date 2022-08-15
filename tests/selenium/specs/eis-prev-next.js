'use strict';

const assert = require( 'assert' ),
	EisPagePage = require( '../pageobjects/eispage.page' ),
	utils = require( '../util/eis-util' ),
	path = require( 'path' ),
	MWBot = require( 'mwbot' );

const bot = new MWBot( {
	apiUrl: browser.config.baseUrl + '/api.php'
} );

describe( 'For a page with eis enabled', function () {
	before( async function () {
		await utils.setupPrpTemplates( bot );
		await bot.uploadOverwrite( 'File:LoremIpsum.djvu', path.join( __dirname, '../../data/media/LoremIpsum.djvu' ), 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Index:LoremIpsum.djvu', '{{:MediaWiki:Proofreadpage_index_template|Type=book\n|Source=_empty_\n|Image=1\n|Progress=X\n|Pages=<pagelist />\n}}', 'Selenium test initialization for edit-in-sequence' );
	} );

	it( 'toolbar loads', function () {
		EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( EisPagePage.prevButton !== undefined );
		assert.ok( EisPagePage.nextButton !== undefined );

	} );

	it( 'prev to page 1', function () {
		EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		assert.ok( utils.isEnabledInOOUI( EisPagePage.prevButton ) === false, 'is disabled' );
		EisPagePage.nextButton.click();
		assert.ok( utils.isEnabledInOOUI( EisPagePage.prevButton ), 'but gets enabled when next is clicked' );
	} );

	it( 'next to last page', function () {
		EisPagePage.openEis( 'Page:LoremIpsum.djvu/5' );
		assert.ok( utils.isEnabledInOOUI( EisPagePage.nextButton ) === false, 'is disabled' );
		EisPagePage.prevButton.click();
		assert.ok( utils.isEnabledInOOUI( EisPagePage.nextButton ), 'but gets enabled when prev is clicked' );
	} );

	after( function () {
		bot.delete( 'Index:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
		bot.delete( 'File:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
	} );

} );
