'use strict';

const assert = require( 'assert' );
const EisPagePage = require( '../pageobjects/eispage.page' );
const utils = require( '../util/eis-util' );
const path = require( 'path' );
const MWBot = require( 'mwbot' );

const bot = new MWBot( {
	apiUrl: browser.config.baseUrl + '/api.php'
} );

describe( 'For a page with the eis, the page status module', () => {
	before( async () => {
		await utils.setupPrpTemplates( bot );
		await bot.uploadOverwrite( 'File:LoremIpsum.djvu', path.join( __dirname, '../../data/media/LoremIpsum.djvu' ), 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Index:LoremIpsum.djvu', '{{:MediaWiki:Proofreadpage_index_template|Type=book\n|Source=_empty_\n|Image=1\n|Progress=X\n|Pages=<pagelist />\n}}', 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Page:LoremIpsum.djvu/1', '<noinclude><pagequality level="2" user="Admin" />abc</noinclude>123<noinclude>jkl</noinclude>', 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Page:LoremIpsum.djvu/2', '<noinclude><pagequality level="1" user="Admin" />def</noinclude>456<noinclude>mno</noinclude>', 'Selenium test initialization for edit-in-sequence' );
	} );

	it( 'should initialize with the value of current page', async () => {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		await EisPagePage.waitForPageStatusButtonToBeResponsive();
		assert.ok( await EisPagePage.pageStatusButtonLabel.getText() === 'Problematic' );
	} );

	it( 'should update on page change', async () => {
		await EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		await EisPagePage.waitForPageStatusButtonToBeResponsive();
		await EisPagePage.selectPageStatusFromDropdown( 'proofread' );
		await EisPagePage.nextButton.click();
		await EisPagePage.waitForPageStatusButtonToBeResponsive();
		assert.ok( await EisPagePage.pageStatusButtonLabel.getText() === 'Not proofread' );
	} );

	after( async () => {
		await bot.delete( 'Page:LoremIpsum.djvu/1', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'Page:LoremIpsum.djvu/2', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'Index:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'File:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
	} );

} );
