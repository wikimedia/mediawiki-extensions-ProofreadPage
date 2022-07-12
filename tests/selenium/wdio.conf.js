'use strict';

const { config } = require( 'wdio-mediawiki/wdio-defaults.conf.js' );
const fs = require( 'fs' ),
	path = require( 'path' );

const localSettingsPath = path.join( __dirname, '../../../../LocalSettings.php' );
const localSettingsOldText = fs.readFileSync( localSettingsPath );

exports.config = {
	// Override, or add to, the setting from wdio-mediawiki.
	// Learn more at https://webdriver.io/docs/configurationfile/
	//
	// Example:
	// logLevel: 'info',
	onPrepare: function () {
		fs.appendFileSync( localSettingsPath, `
		if ( file_exists( "$IP/extensions/ProofreadPage/tests/selenium/settings/ProofreadPage.LocalSettings.php" ) ) {
			require_once "$IP/extensions/ProofreadPage/tests/selenium/settings/ProofreadPage.LocalSettings.php";
		}
		` );
	},
	onComplete: function () {
		fs.writeFileSync( localSettingsPath, localSettingsOldText );
	},
	...config
};
