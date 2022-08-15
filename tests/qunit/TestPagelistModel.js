var PagelistModel = require( 'ext.proofreadpage.page.editinsequence' ).PagelistModel;

var TestPagelistModel = PagelistModel;
/**
 * Convert fetchPagelistData to a no-op to aid in testing
 * @override
 */
TestPagelistModel.prototype.fetchPagelistData = function ( contd ) {
	/* no-op */
};

module.exports = TestPagelistModel;
