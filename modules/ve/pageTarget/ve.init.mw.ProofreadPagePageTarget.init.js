/*!
 * VisualEditor MediaWiki Initialization ProofreadPagePageTarget initialization.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

if ( mw.loader.getState( 'ext.visualEditor.desktopArticleTarget.init' ) ) {
	mw.loader.using( 'ext.visualEditor.desktopArticleTarget.init', function () {
		mw.libs.ve.addPlugin( 'ext.proofreadpage.ve.pageTarget' );
	} );
}
