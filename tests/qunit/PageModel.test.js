var PageModel = require( 'ext.proofreadpage.page.editinsequence' ).PageModel;
var PagelistModel = require( 'ext.proofreadpage.page.editinsequence' ).PagelistModel;

QUnit.module( 'PageModel', QUnit.newMwEnvironment() );

QUnit.test( 'PageModel.parsePageData', function ( assert ) {
	// create a fake pagelist model
	var testPagelistModel = new PagelistModel( 'Page:War and Peace.djvu/1', 'Index:War and Peace.djvu', true ),
		testPageModel = new PageModel( testPagelistModel ),
		testcases = [
			{
				wikitext: '<noinclude><pagequality user="User" level="0" />header</noinclude>body<noinclude>footer</noinclude>',
				expectedEditorData: {
					header: 'header',
					body: 'body',
					footer: 'footer',
					pageStatus: {
						status: 0,
						lastUser: 'User'
					}
				},
				message: 'Normal page'
			},
			{
				wikitext: '<noinclude><pagequality user="User" level="1" />header</noinclude>body<noinclude>footer<noinclude>other-stuff</noinclude></noinclude>',
				expectedEditorData: {
					header: 'header',
					body: 'body',
					footer: 'footer<noinclude>other-stuff</noinclude>',
					pageStatus: {
						status: 1,
						lastUser: 'User'
					}
				},
				message: 'Noinclude in footer'
			},
			{
				wikitext: '<noinclude><pagequality user="User" level="1" />header</noinclude>body<noinclude>stuff</noinclude><noinclude>more-stuff</noinclude><noinclude>footer</noinclude>',
				expectedEditorData: {
					header: 'header',
					body: 'body<noinclude>stuff</noinclude><noinclude>more-stuff</noinclude>',
					footer: 'footer',
					pageStatus: {
						status: 1,
						lastUser: 'User'
					}
				},
				message: 'Noincludes in body'
			},
			{
				wikitext: '<noinclude><pagequality user="User" level="1" />header<noinclude>stuff</noinclude><noinclude>more-stuff</noinclude></noinclude>body<noinclude>footer</noinclude>',
				expectedEditorData: {
					header: 'header<noinclude>stuff</noinclude><noinclude>more-stuff</noinclude>',
					body: 'body',
					footer: 'footer',
					pageStatus: {
						status: 1,
						lastUser: 'User'
					}
				},
				message: 'Noincludes in header'
			},
			{
				wikitext: '<noinclude><pagequality user="User" level="1" />\n\n\nheader\n\n\n</noinclude>\n\n\nbody\n\n\n<noinclude>footer\n\ntrwyirtrewitrew\n\n</noinclude>',
				expectedEditorData: {
					header: '\n\n\nheader\n\n\n',
					body: '\n\n\nbody\n\n\n',
					footer: 'footer\n\ntrwyirtrewitrew\n\n',
					pageStatus: {
						status: 1,
						lastUser: 'User'
					}
				},
				message: 'Newlines'
			}
		];

		for ( var i = 0; i < testcases.length; i++ ) {
			assert.deepEqual( testPageModel.parsePageData( testcases[i].wikitext ), testcases[i].expectedEditorData, testcases[i].message );
		}
} );
