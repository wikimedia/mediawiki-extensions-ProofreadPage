var TestPagelistModel = require( './TestPagelistModel.js' );

QUnit.module( 'PagelistModel', QUnit.newMwEnvironment() );

QUnit.test( 'PagelistModel.setPagelistData', function ( assert ) {
	var testPagelistModel = new TestPagelistModel( 'Page:War and Peace.djvu/1', 'Index:War and Peace.djvu', true ),
		testcases = [ {
			pageid: 0,
			title: 'Page:War and Peace.djvu/1',
			pageoffset: 1
		},
		{
			pageid: 1,
			title: 'Page:War and Peace.djvu/2',
			pageoffset: 3
		} ];
	testPagelistModel.setPageListData( testcases );

	for( var i = 0; i < testcases.length; i++ ) {
		assert.strictEqual( testPagelistModel.pagelist[i].pageNumber, testcases[i].pageoffset );
		assert.strictEqual( testPagelistModel.pagelist[i].title, testcases[i].title );
		assert.strictEqual( testPagelistModel.pagelist[i].exists, !!testcases[i].pageid );
	}
} );

QUnit.test( 'PagelistModel.getCurrent', function ( assert ) {
	var testPagelistModel = new TestPagelistModel( 'Page:War and Peace.djvu/4', 'Index:War and Peace.djvu', true ),
		testcases = [ {
			pageid: 0,
			title: 'Page:War and Peace.djvu/1',
			pageoffset: 1
		},
		{
			pageid: 1,
			title: 'Page:War and Peace.djvu/2',
			pageoffset: 3
		} ];
	testPagelistModel.setPageListData( testcases );
	assert.deepEqual( testPagelistModel.getCurrent(), {
		exists: false,
		pageid: 0,
		title: 'Page:War and Peace.djvu/1',
		pageNumber: 1
	} );
} );
