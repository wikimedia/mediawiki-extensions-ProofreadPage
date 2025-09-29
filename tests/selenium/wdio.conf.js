import { config as wdioDefaults } from 'wdio-mediawiki/wdio-defaults.conf.js';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';
import { spawnSync } from 'node:child_process';

// TODO Update to use https://gerrit.wikimedia.org/r/c/mediawiki/core/+/1216880
// when we are on the right wdio-mediawiki version T417643
// eslint-disable-next-line no-underscore-dangle
const __dirname = path.dirname( fileURLToPath( import.meta.url ) );

const phpFpmServiceName = `php${ process.env.PHP_VERSION }-fpm`;
const localSettingsPath = path.join( __dirname, '../../../../LocalSettings.php' );
const localSettingsOldText = fs.readFileSync( localSettingsPath );

export const config = {
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
			spawnSync(
				'service',
				[ phpFpmServiceName, 'restart' ]
			);
			// Super ugly hack: Run this twice because sometimes the first invocation hangs.
			spawnSync(
				'service',
				[ phpFpmServiceName, 'restart' ]
			);
		}
	},
	onComplete: function () {
		fs.writeFileSync( localSettingsPath, localSettingsOldText );
	},
	...wdioDefaults
};
