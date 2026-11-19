'use strict';

const { defineConfig, globalIgnores } = require( 'eslint/config' );
const wikimedia = require( 'eslint-config-wikimedia/flat' );

// Directories with their own config, which other configs don't apply to.
// These were directories with `"root": true` in .eslintrc.json.
const rootDirs = {
	modules: [ 'modules/**' ],
	pagelist: [ 'modules/index.pagelist/**' ],
	qunit: [ 'tests/qunit/**' ],
	selenium: [ 'tests/selenium/**' ]
};

module.exports = defineConfig( [
	globalIgnores( [
		// Upstream code
		'modules/lib/',

		// Build
		'vendor/'
	] ),

	// Gruntfile.js, etc.
	{
		ignores: Object.values( rootDirs ).flat(),
		extends: [ wikimedia( 'server' ) ]
	},

	// Code which runs in MediaWiki
	{
		files: rootDirs.modules,
		ignores: rootDirs.pagelist,
		extends: [ wikimedia( 'client', 'jquery', 'mediawiki' ) ],
		rules: {
			'max-len': 'off',
			'es-x/no-regexp-prototype-flags': 'warn'
		}
	},
	{
		files: [ 'modules/page.editinsequence/**' ],
		languageOptions: {
			sourceType: 'commonjs'
		},
		rules: {
			'no-jquery/no-extend': 'warn',
			'no-jquery/no-done-fail': 'warn'
		}
	},
	{
		files: [ 'modules/page/**' ],
		languageOptions: {
			sourceType: 'commonjs'
		},
		rules: {
			'no-jquery/no-extend': 'warn',
			'mediawiki/no-unlabeled-buttonwidget': 'warn'
		}
	},
	{
		files: [ 'modules/ve/**' ],
		languageOptions: {
			globals: {
				ve: 'readonly'
			}
		},
		rules: {
			'no-jquery/no-done-fail': 'warn'
		}
	},
	{
		files: rootDirs.pagelist,
		extends: [ wikimedia( 'client', 'jquery', 'mediawiki' ) ],
		languageOptions: {
			sourceType: 'commonjs'
		},
		rules: {
			'max-len': 'off',
			'no-jquery/no-done-fail': 'warn',
			'mediawiki/no-unlabeled-buttonwidget': 'warn'
		}
	},

	// QUnit tests
	{
		files: rootDirs.qunit,
		extends: [ wikimedia( 'qunit' ) ],
		languageOptions: {
			sourceType: 'commonjs'
		}
	},

	// Selenium tests
	{
		files: rootDirs.selenium,
		extends: [ wikimedia( 'selenium' ) ],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				mw: 'readonly'
			}
		}
	}
] );
