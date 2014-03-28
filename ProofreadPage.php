<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}


/**
 * Array that contain the ids of namespaces used by ProofreadPage
 * Exemple (with default namespace ids):
 * $wgProofreadPageNamespaceIds = array(
 * 	'page' => 250,
 * 	'index' => 252
 * );
 */
$wgProofreadPageNamespaceIds = array();


//Constants
define( 'CONTENT_MODEL_PROOFREAD_PAGE', 'proofread-page' );

$dir = __DIR__ . '/';
$wgMessagesDirs['ProofreadPage'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['ProofreadPage'] = $dir . 'ProofreadPage.i18n.php';
$wgExtensionMessagesFiles['ProofreadPageAlias'] = $dir . 'ProofreadPage.alias.php';

$wgAutoloadClasses['ProofreadPage'] = $dir . 'ProofreadPage.body.php';
$wgAutoloadClasses['ProofreadPage\Context'] = $dir . 'includes/Context.php';
$wgAutoloadClasses['ProofreadPageInit'] = $dir . 'includes/ProofreadPageInit.php';
$wgAutoloadClasses['ProofreadPageRenderer'] = $dir . 'includes/ProofreadPageRenderer.php';
$wgAutoloadClasses['ProofreadDiffFormatterUtils'] = $dir . 'includes/ProofreadDiffFormatterUtils.php';
$wgAutoloadClasses['ProofreadPage\FileNotFoundException'] = $dir . 'includes/FileNotFoundException.php';
$wgAutoloadClasses['ProofreadPage\FileProvider'] = $dir . 'includes/FileProvider.php';

$wgAutoloadClasses['EditProofreadIndexPage'] = $dir . 'includes/index/EditProofreadIndexPage.php';
$wgAutoloadClasses['ProofreadIndexEntry'] = $dir . 'includes/index/ProofreadIndexEntry.php';
$wgAutoloadClasses['ProofreadIndexValue'] = $dir . 'includes/index/ProofreadIndexValue.php';
$wgAutoloadClasses['ProofreadIndexPage'] = $dir . 'includes/index/ProofreadIndexPage.php';
$wgAutoloadClasses['ProofreadIndexDbConnector'] = $dir . 'includes/index/ProofreadIndexDbConnector.php';

$wgAutoloadClasses['ProofreadPageDbConnector'] = $dir . 'includes/page/ProofreadPageDbConnector.php';
$wgAutoloadClasses['ProofreadPage\Page\EditPagePage'] = $dir . 'includes/page/EditPagePage.php';
$wgAutoloadClasses['ProofreadPage\Page\PageContentBuilder'] = $dir . 'includes/page/PageContentBuilder.php';
$wgAutoloadClasses['ProofreadPagePage'] = $dir.'includes/page/ProofreadPagePage.php';
$wgAutoloadClasses['ProofreadPageContent'] = $dir.'includes/page/ProofreadPageContent.php';
$wgAutoloadClasses['ProofreadPageLevel'] = $dir.'includes/page/ProofreadPageLevel.php';
$wgAutoloadClasses['ProofreadPageContentHandler'] = $dir.'includes/page/ProofreadPageContentHandler.php';
$wgAutoloadClasses['ProofreadPage\Page\PageEditAction'] = $dir . 'includes/page/PageEditAction.php';
$wgAutoloadClasses['ProofreadPage\Page\PageSubmitAction'] = $dir . 'includes/page/PageSubmitAction.php';
$wgAutoloadClasses['ProofreadPageViewAction'] = $dir . 'includes/page/ProofreadPageViewAction.php';
$wgAutoloadClasses['ProofreadPageDifferenceEngine'] = $dir . 'includes/page/ProofreadPageDifferenceEngine.php';

$wgAutoloadClasses['ProofreadPage\Parser\ParserEntryPoint'] = $dir . 'includes/Parser/ParserEntryPoint.php';
$wgAutoloadClasses['ProofreadPage\Parser\TagParser'] = $dir . 'includes/Parser/TagParser.php';
$wgAutoloadClasses['ProofreadPage\Parser\PagelistTagParser'] = $dir . 'includes/Parser/PagelistTagParser.php';
$wgAutoloadClasses['ProofreadPage\Parser\PagesTagParser'] = $dir . 'includes/Parser/PagesTagParser.php';

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'ProofreadPage',
	'author'         => 'ThomasV',
	'url'            => 'https://www.mediawiki.org/wiki/Extension:Proofread_Page',
	'descriptionmsg' => 'proofreadpage_desc',
);

# OAI-PMH
$wgAutoloadClasses['SpecialProofreadIndexOai'] = $dir . 'includes/index/oai/SpecialProofreadIndexOai.php';
$wgAutoloadClasses['ProofreadIndexOaiRecord'] = $dir . 'includes/index/oai/ProofreadIndexOaiRecord.php';
$wgAutoloadClasses['ProofreadIndexOaiSets'] = $dir . 'includes/index/oai/ProofreadIndexOaiSets.php';
$wgSpecialPages['ProofreadIndexOai'] = 'SpecialProofreadIndexOai';
$wgAutoloadClasses['SpecialProofreadIndexOaiSchema'] = $dir . 'includes/index/oai/SpecialProofreadIndexOaiSchema.php';
$wgSpecialPages['ProofreadIndexOaiSchema'] = 'SpecialProofreadIndexOaiSchema';

# special page
$wgAutoloadClasses['ProofreadPages'] = $dir . 'SpecialProofreadPages.php';
$wgSpecialPages['IndexPages'] = 'ProofreadPages';
$wgSpecialPageGroups['IndexPages'] = 'pages';

# special page
$wgAutoloadClasses['PagesWithoutScans'] = $dir . 'SpecialPagesWithoutScans.php';
$wgSpecialPages['PagesWithoutScans'] = 'PagesWithoutScans';
$wgSpecialPageGroups['PagesWithoutScans'] = 'maintenance';

# api prop
$wgAutoloadClasses['ApiQueryProofread'] = $dir . 'ApiQueryProofread.php';
$wgAPIPropModules['proofread'] = 'ApiQueryProofread';

# api proofreadinfo
$wgAutoloadClasses['ApiQueryProofreadInfo'] = $dir . 'ApiQueryProofreadInfo.php';
$wgAPIMetaModules['proofreadinfo'] = 'ApiQueryProofreadInfo';

//maintenance scripts
$wgAutoloadClasses['FixProofreadPagePagesContentModel'] = $dir . 'maintenance/fixProofreadPagePagesContentModel.php';

# Group allowed to modify pagequality
$wgGroupPermissions['user']['pagequality'] = true;

# Client-side resources
$prpResourceTemplate = array(
	'localBasePath' => $dir . 'modules',
	'remoteExtPath' => 'ProofreadPage/modules'
);
$wgResourceModules += array(
	'jquery.mousewheel' => $prpResourceTemplate + array(
		'scripts' => 'jquery/jquery.mousewheel.js'
	),
	'jquery.prpZoom' => $prpResourceTemplate + array(
		'scripts' => 'jquery/jquery.prpZoom.js',
		'dependencies' => array( 'jquery.ui.widget', 'jquery.ui.draggable', 'jquery.mousewheel' )
	),
	'ext.proofreadpage.base' => $prpResourceTemplate + array(
		'styles'  => 'ext.proofreadpage.base.css',
		'targets' => array( 'mobile', 'desktop' ),
	),
	'ext.proofreadpage.page' => $prpResourceTemplate + array(
		'styles'  => 'page/ext.proofreadpage.page.css',
		'dependencies' => array( 'ext.proofreadpage.base' )
	),
	'ext.proofreadpage.page.edit' => $prpResourceTemplate + array(
		'styles'  => 'page/ext.proofreadpage.page.edit.css',
		'scripts' => 'page/ext.proofreadpage.page.edit.js',
		'dependencies' => array( 'ext.proofreadpage.page', 'jquery.prpZoom', 'mediawiki.user' ),
		'messages' => array(
			'proofreadpage_quality0_category',
			'proofreadpage_quality1_category',
			'proofreadpage_quality2_category',
			'proofreadpage_quality3_category',
			'proofreadpage_quality4_category',
			'proofreadpage-section-tools',
			'proofreadpage-group-zoom',
			'proofreadpage-group-other',
			'proofreadpage-button-toggle-visibility-label',
			'proofreadpage-button-zoom-out-label',
			'proofreadpage-button-reset-zoom-label',
			'proofreadpage-button-zoom-in-label',
			'proofreadpage-button-toggle-layout-label',
			'proofreadpage-preferences-showheaders-label',
		)
	),
	'ext.proofreadpage.page.navigation' => $prpResourceTemplate + array(
		'scripts' => 'page/ext.proofreadpage.page.navigation.js',
		'skinStyles' => array(
			'vector' => 'page/ext.proofreadpage.page.navigation.vector.css',
		)
	),
	'ext.proofreadpage.article' => $prpResourceTemplate + array(
		'scripts' => 'article/ext.proofreadpage.article.js',
		'dependencies' => array( 'ext.proofreadpage.base' )
	),
	'ext.proofreadpage.index' => $prpResourceTemplate + array(
		'scripts' => 'index/ext.proofreadpage.index.js',
		'styles'  => 'index/ext.proofreadpage.index.css',
		'dependencies' => array( 'ext.proofreadpage.base', 'jquery.tipsy' )
	),
);

//Hooks
$wgHooks['SetupAfterCache'][] = 'ProofreadPageInit::initNamespaces';
$wgHooks['ParserFirstCallInit'][] = 'ProofreadPage::onParserFirstCallInit';
$wgHooks['BeforePageDisplay'][] = 'ProofreadPage::onBeforePageDisplay';
$wgHooks['GetLinkColours'][] = 'ProofreadPage::onGetLinkColours';
$wgHooks['ImageOpenShowImageInlineBefore'][] = 'ProofreadPage::onImageOpenShowImageInlineBefore';
$wgHooks['ArticleSaveComplete'][] = 'ProofreadPage::onArticleSaveComplete';
$wgHooks['ArticleDelete'][] = 'ProofreadPage::onArticleDelete';
$wgHooks['ArticleUndelete'][] = 'ProofreadPage::onArticleUndelete';
$wgHooks['ArticlePurge'][] = 'ProofreadPage::onArticlePurge';
$wgHooks['SpecialMovepageAfterMove'][] = 'ProofreadPage::onSpecialMovepageAfterMove';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'ProofreadPage::onLoadExtensionSchemaUpdates';
$wgHooks['OutputPageParserOutput'][] = 'ProofreadPage::onOutputPageParserOutput';
$wgHooks['wgQueryPages'][] = 'ProofreadPage::onwgQueryPages';
$wgHooks['GetPreferences'][] = 'ProofreadPage::onGetPreferences';
$wgHooks['CustomEditor'][] = 'ProofreadPage::onCustomEditor';
$wgHooks['CanonicalNamespaces'][] = 'ProofreadPage::addCanonicalNamespaces';
$wgHooks['SkinTemplateNavigation'][] = 'ProofreadPage::onSkinTemplateNavigation';
$wgHooks['ContentHandlerDefaultModelFor'][] = 'ProofreadPage::onContentHandlerDefaultModelFor';
$wgHooks['APIEditBeforeSave'][] = 'ProofreadPage::onAPIEditBeforeSave';
$wgHooks['EditFormPreloadText'][] = 'ProofreadPage::onEditFormPreloadText';
$wgHooks['ParserTestTables'][] = 'ProofreadPage::onParserTestTables';
$wgHooks['InfoAction'][] = 'ProofreadPage::onInfoAction';



/**
 * Hook to add PHPUnit test cases
 * @param array $files
 * @return boolean
 */
$wgHooks['UnitTestsList'][] = function( array &$files ) {
	$dir = __DIR__ . '/tests/includes/';

	$files[] = $dir . 'FileProviderMock.php';
	$files[] = $dir . 'ProofreadPageTestCase.php';
	$files[] = $dir . 'FileProviderTest.php';

	$files[] = $dir . 'index/ProofreadIndexPageTest.php';

	$files[] = $dir . 'page/ProofreadPageLevelTest.php';
	$files[] = $dir . 'page/ProofreadPageContentTest.php';
	$files[] = $dir . 'page/ProofreadPageContentHandlerTest.php';
	$files[] = $dir . 'page/ProofreadPagePageTest.php';
	$files[] = $dir . 'page/PageContentBuilderTest.php';
	$files[] = $dir . 'ProofreadDiffFormatterUtilsTest.php';

	return true;
};

//Parser tests
$wgParserTestFiles[] = __DIR__ . '/tests/parser/pages.txt';

//Handlers
$wgContentHandlers[CONTENT_MODEL_PROOFREAD_PAGE] = 'ProofreadPageContentHandler';

//inclusion of i18n file. $wgExtensionMessagesFiles[] doesn't works
include_once( $dir . 'ProofreadPage.namespaces.php' );
