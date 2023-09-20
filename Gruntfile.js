'use strict';

module.exports = function ( grunt ) {
	const conf = grunt.file.readJSON( 'extension.json' );

	grunt.loadNpmTasks( 'grunt-banana-checker' );
	grunt.loadNpmTasks( 'grunt-eslint' );
	grunt.loadNpmTasks( 'grunt-stylelint' );

	grunt.initConfig( {
		eslint: {
			options: {
				cache: true,
				fix: grunt.option( 'fix' )
			},
			all: '.'
		},
		stylelint: {
			options: {
				fix: grunt.option( 'fix' )
			},
			all: [
				'modules/**/*.{css,less}'
			]
		},
		banana: conf.MessagesDirs

	} );

	grunt.registerTask( 'test', [ 'eslint', 'stylelint', 'banana' ] );
	grunt.registerTask( 'fix', function () {
		grunt.config.set( 'eslint.options.fix', true );
		grunt.config.set( 'stylelint.options.fix', true );
		grunt.task.run( [ 'eslint', 'stylelint' ] );
	} );
	grunt.registerTask( 'default', 'test' );
};
