/*!
 * VisualEditor ProofreadPagePageTarget initialization.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

( function () {
	mw.loader.using( 'ext.visualEditor.desktopArticleTarget.init', function () {
		// Adding this module to VisualEditorPluginModules would cause it to load on
		// all VE-loading pages (even non PRP namespaces). Instead add it to the plugin
		// list here, only on pages that have loaded 'ext.proofreadpage.page' first.
		mw.libs.ve.addPlugin( 'ext.proofreadpage.ve.pageTarget' );
	} );
}() );
