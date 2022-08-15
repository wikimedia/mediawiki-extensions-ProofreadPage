'use strict';

const MWBot = require( 'mwbot' );
const fs = require( 'fs' );
const path = require( 'path' );

// copied/modified from MWBot.upload() at
// https://github.com/Fannon/mwbot/blob/2f13281e53f3c515120dfff10889b2393819ceef/src/index.js#L708
async function importTemplates( bot ) {
	const pathToFile = path.join( __dirname, '../../data/en-wikisource-2021-template-dump.xml' );
	const templates = fs.createReadStream( pathToFile );

	const params = {
		action: 'import',
		filename: path.basename( pathToFile ),
		xml: templates,
		interwikiprefix: 'enws',
		token: bot.editToken,
		format: 'json'
	};

	const uploadRequestOptions = MWBot.merge( bot.globalRequestOptions, {

		// https://www.npmjs.com/package/request#support-for-har-12
		har: {
			method: 'POST',
			postData: {
				mimeType: 'multipart/form-data',
				params: []
			}
		}
	} );

	// Convert params to HAR 1.2 notation
	for ( const paramName in params ) {
		const param = params[ paramName ];
		uploadRequestOptions.har.postData.params.push( {
			name: paramName,
			value: param
		} );
	}

	return bot.request( {}, uploadRequestOptions );
}

async function setupPrpTemplates( bot ) {
	await bot.loginGetEditToken( {
		username: browser.config.mwUser,
		password: browser.config.mwPwd
	} );

	await importTemplates( bot );
}

// Check if a element is enabled or disabled via OOUI
// (the aria-disabled attribute will be set to true)
function isEnabledInOOUI( element ) {
	return element.getAttribute( 'aria-disabled' ) === null;
}

module.exports = {
	setupPrpTemplates,
	isEnabledInOOUI
};
