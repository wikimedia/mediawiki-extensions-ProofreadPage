'use strict';

module.exports = function ( grunt ) {
	const conf = grunt.file.readJSON( 'extension.json' );

	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-stylelint' );
	grunt.loadNpmTasks( 'grunt-replace' );

	grunt.initConfig( {
		eslint: {
			options: {
				cache: true,
				fix: grunt.option( 'fix' )
			},
			all: [
				'**/*.{js,json}',
				'!node_modules/**',
				'!modules/jquery/**',
				'!modules/lib/**',
				'!vendor/**'
			]
		},
		stylelint: {
			options: {
				fix: grunt.option( 'fix' )
			},
			all: [
				'modules/**/*.{css,less}'
			]
		},
		replace: {
			dist: {
				options: {
					patterns: [
						{
							match: /^\/\/# sourceMappingURL=openseadragon\.js\.map$/gm,
							replacement: ''
						},
						{
							match: /^\/\/! Built on [0-9]{4}-[0-9]{2}-[0-9]{2}/gm,
							replacement: ''
						},
						{
							match: /^\/\/! Git commit: \W*\w*/gm,
							replacement: ''
						}

					]
				},
				files: [
					{
						expand: true,
						flatten: true,
						src: 'node_modules/openseadragon/build/openseadragon/openseadragon.js',
						dest: 'modules/lib/'
					}
				]
			}
		},
		banana: conf.MessagesDirs

	} );

	grunt.registerTask( 'libcheck', function () {
		const done = this.async();
		// Are there unstaged changes after synchronizing from upstream libraries?
		require( 'child_process' ).exec( 'git ls-files modules/lib/ --modified', function ( err, stdout, stderr ) {
			// Before we try to rebuild lib file, let's make sure there aren't any local unstaged
			// changes first in those files, so we don't override uncommitted work
			const ret = err || stderr || stdout;
			if ( ret ) {
				grunt.log.error( 'There are uncommitted changes to external library files. Please change these files upstream, instead.' );
				grunt.log.error( ret );
				done( false );
			} else {
				// Build the lib file and verify there isn't a difference
				require( 'child_process' ).exec( 'npm run build', function () {
					require( 'child_process' ).exec( 'git ls-files modules/lib/ --modified', function ( err2, stdout2, stderr2 ) {
						const ret2 = err2 || stderr2 || stdout2;
						if ( ret2 ) {
							grunt.log.error( 'These library files were directly changed. Please change them upstream, instead:' );
							grunt.log.error( ret2 );
							const changes = require( 'child_process' ).spawn( 'git diff ', [ 'modules/lib/openseadragon.js' ], { shell: true } );
							changes.stdout.on( 'data', function ( data ) {
								grunt.log.ok( data );
							} );
							changes.on( 'exit', function ( code, signal ) {
								if ( code ) {
									grunt.log.ok( 'code', code );
								}
								if ( code ) {
									grunt.log.ok( 'signal', signal );
								}
								grunt.log.ok( 'done' );
								done( false );
							} );

						} else {
							grunt.log.ok( 'Library folder is synchronized with upstream libraries\' states.' );
							done();
						}
					} );
				} );
			}

		} );
	} );
	grunt.registerTask( 'test', [ 'eslint', 'stylelint', 'banana', 'libcheck' ] );
	grunt.registerTask( 'fix', function () {
		grunt.config.set( 'eslint.options.fix', true );
		grunt.config.set( 'stylelint.options.fix', true );
		grunt.task.run( [ 'eslint', 'stylelint' ] );
	} );
	grunt.registerTask( 'default', 'test' );
	grunt.registerTask( 'build', 'replace' );
};
