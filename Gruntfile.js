/* eslint-env node, es6 */
module.exports = function ( grunt ) {
	var conf = grunt.file.readJSON( 'extension.json' );

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
		var done = this.async();
		// Are there unstaged changes after synchronizing from upstream libraries?
		require( 'child_process' ).exec( 'git ls-files modules/lib/ --modified', function ( err, stdout, stderr ) {
			// Before we try to rebuild lib file, let's make sure there aren't any local unstaged changes
			// first in those files, so we don't override uncommitted work
			var ret = err || stderr || stdout;
			if ( ret ) {
				grunt.log.error( 'There are uncommitted changes to external library files. Please change these files upstream, instead.' );
				grunt.log.error( ret );
				done( false );
			} else {
				// Build the lib file and verify there isn't a difference
				require( 'child_process' ).exec( 'npm run build', function () {
					require( 'child_process' ).exec( 'git ls-files modules/lib/ --modified', function ( error, stdoutp, stderror ) {
						ret = error || stderror || stdoutp;
						if ( ret ) {
							grunt.log.error( 'These library files were directly changed. Please change them upstream, instead:' );
							grunt.log.error( ret );
							var changes = require( 'child_process' ).spawn( 'git diff ', [ 'modules/lib/openseadragon.js' ], { shell: true } );
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
