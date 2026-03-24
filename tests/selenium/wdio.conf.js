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
	...wdioDefaults,
	// Override, or add to, the setting from wdio-mediawiki.
	// Learn more at https://webdriver.io/docs/configurationfile/
	//
	// Example:
	// logLevel: 'info',
	onPrepare: async ( configuration, param ) => {
		await wdioDefaults.onPrepare?.( configuration, param );
		fs.appendFileSync( localSettingsPath, `
		if ( file_exists( "$IP/extensions/ProofreadPage/tests/selenium/settings/ProofreadPage.LocalSettings.php" ) ) {
			require_once "$IP/extensions/ProofreadPage/tests/selenium/settings/ProofreadPage.LocalSettings.php";
		}
		` );

		/**
		 * Reset the PHP-Fpm opcache under Quibble environment
		 *
		 * In the CI environment, PHP Fpm is never revalidating files once they have
		 * entered the opcache (`opcache.validate_timestamps=0`).
		 *
		 * The first request hitting MediaWiki triggers caching of `LocalSettings.php`
		 * and subsequent changes to it (via `overrideLocalSettings()` will thus not
		 * been taken in account since PHP serves it from the stalled cache (as
		 * intended).
		 *
		 * The issue notably happens when running tests in parallel, some test suites
		 * might not alter the `LocalSettings.php`, the stock one is thus cached and
		 * when another tests changes the file, the new settings are now taken in
		 * account on any test relying on the change ends up failing.
		 *
		 * We hit that case for the API Testing suite and Selenium:
		 * https://phabricator.wikimedia.org/T276428#7194025
		 *
		 * The PHP-Fpm opcache is held in shared memory and we thus can not clear it
		 * using `opcache_reset()` from the PHP CLI. However the opcache is cleared
		 * when reloading PHP-Fpm https://phabricator.wikimedia.org/T418369#11703410
		 */
		if ( process.env.QUIBBLE_APACHE ) {
			spawnSync(
				'service',
				[ phpFpmServiceName, 'reload' ]
			);
		}
	},
	onComplete: async ( exitCode, configuration, capabilities, results ) => {
		await wdioDefaults.onComplete?.( exitCode, configuration, capabilities, results );
		fs.writeFileSync( localSettingsPath, localSettingsOldText );
	}
};
