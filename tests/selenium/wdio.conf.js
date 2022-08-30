'use strict';

const { config } = require( 'wdio-mediawiki/wdio-defaults.conf.js' );
const fs = require( 'fs' ),
	childProcess = require( 'child_process' ),
	path = require( 'path' );

const phpFpmServiceName = `php${process.env.PHP_VERSION}-fpm`;
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

		// per T315214#8153130 and GrowthExperiments implementation of the same
		// at https://gerrit.wikimedia.org/r/plugins/gitiles/mediawiki/extensions/GrowthExperiments/+/refs/heads/master/tests/selenium/wdio.conf.js#5
		if ( process.env.QUIBBLE_APACHE ) {
			childProcess.spawnSync(
				'service',
				[ phpFpmServiceName, 'restart' ]
			);
			// Super ugly hack: Run this twice because sometimes the first invocation hangs.
			childProcess.spawnSync(
				'service',
				[ phpFpmServiceName, 'restart' ]
			);
		}
	},
	onComplete: function () {
		fs.writeFileSync( localSettingsPath, localSettingsOldText );
	},
	...config
};
