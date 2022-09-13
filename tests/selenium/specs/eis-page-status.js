'use strict';

const assert = require( 'assert' ),
	EisPagePage = require( '../pageobjects/eispage.page' ),
	utils = require( '../util/eis-util' ),
	path = require( 'path' ),
	MWBot = require( 'mwbot' );

const bot = new MWBot( {
	apiUrl: browser.config.baseUrl + '/api.php'
} );

describe( 'For a page with the eis, the page status module', function () {
	before( async function () {
		await utils.setupPrpTemplates( bot );
		await bot.uploadOverwrite( 'File:LoremIpsum.djvu', path.join( __dirname, '../../data/media/LoremIpsum.djvu' ), 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Index:LoremIpsum.djvu', '{{:MediaWiki:Proofreadpage_index_template|Type=book\n|Source=_empty_\n|Image=1\n|Progress=X\n|Pages=<pagelist />\n}}', 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Page:LoremIpsum.djvu/1', '<noinclude><pagequality level="2" user="Admin" />abc</noinclude>123<noinclude>jkl</noinclude>', 'Selenium test initialization for edit-in-sequence' );
		await bot.create( 'Page:LoremIpsum.djvu/2', '<noinclude><pagequality level="1" user="Admin" />def</noinclude>456<noinclude>mno</noinclude>', 'Selenium test initialization for edit-in-sequence' );
	} );

	it( 'should initialize with the value of current page', function () {
		EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		EisPagePage.waitForPageStatusButtonToBeResponsive();
		assert.ok( EisPagePage.pageStatusButtonLabel.getText() === 'Problematic' );
	} );

	it( 'should update on page change', function () {
		EisPagePage.openEis( 'Page:LoremIpsum.djvu/1' );
		EisPagePage.waitForPageStatusButtonToBeResponsive();
		EisPagePage.selectPageStatusFromDropdown( 'proofread' );
		EisPagePage.nextButton.click();
		EisPagePage.waitForPageStatusButtonToBeResponsive();
		assert.ok( EisPagePage.pageStatusButtonLabel.getText() === 'Not proofread' );
	} );

	after( async function () {
		await bot.delete( 'Page:LoremIpsum.djvu/1', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'Page:LoremIpsum.djvu/2', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'Index:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
		await bot.delete( 'File:LoremIpsum.djvu', 'Selenium test teardown for edit-in-sequence' );
	} );

} );
