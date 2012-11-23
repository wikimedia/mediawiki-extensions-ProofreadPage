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
 * 	'page' => 252
 * );
 */
$wgProofreadPageNamespaceIds = array();


$dir = __DIR__ . '/';
$wgExtensionMessagesFiles['ProofreadPage'] = $dir . 'ProofreadPage.i18n.php';
$wgExtensionMessagesFiles['ProofreadPageAlias'] = $dir . 'ProofreadPage.alias.php';

$wgAutoloadClasses['ProofreadPage'] = $dir . 'ProofreadPage.body.php';
$wgAutoloadClasses['ProofreadPageInit'] = $dir . 'includes/ProofreadPageInit.php';


$wgAutoloadClasses['EditProofreadIndexPage'] = $dir . 'includes/index/EditProofreadIndexPage.php';
$wgAutoloadClasses['ProofreadIndexEntry'] = $dir . 'includes/index/ProofreadIndexEntry.php';
$wgAutoloadClasses['ProofreadIndexValue'] = $dir . 'includes/index/ProofreadIndexValue.php';
$wgAutoloadClasses['ProofreadIndexPage'] = $dir . 'includes/index/ProofreadIndexPage.php';

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

# Group allowed to modify pagequality
$wgGroupPermissions['user']['pagequality'] = true;

# Client-side resources
$prpResourceTemplate = array(
	'localBasePath' => dirname( __FILE__ ). '/modules',
	'remoteExtPath' => 'ProofreadPage/modules'
);
$wgResourceModules += array(
	'ext.proofreadpage.base' => $prpResourceTemplate + array(
		'styles'  => 'ext.proofreadpage.base/ext.proofreadpage.base.css',
	),
	'ext.proofreadpage.page' => $prpResourceTemplate + array(
		'scripts' => 'ext.proofreadpage.page/ext.proofreadpage.page.js',
		'styles'  => 'ext.proofreadpage.page/ext.proofreadpage.page.css',
		'dependencies' => array( 'ext.proofreadpage.base', 'mediawiki.legacy.wikibits', 'mediawiki.util' ),
		'messages' => array(
			'proofreadpage_header',
			'proofreadpage_body',
			'proofreadpage_footer',
			'proofreadpage_toggleheaders',
			'proofreadpage_page_status',
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
	'ext.proofreadpage.article' => $prpResourceTemplate + array(
		'scripts' => 'ext.proofreadpage.article/ext.proofreadpage.article.js',
		'dependencies' => array( 'ext.proofreadpage.base' )
	),
	'ext.proofreadpage.index' => $prpResourceTemplate + array(
		'scripts' => 'ext.proofreadpage.index/ext.proofreadpage.index.js',
		'styles'  => 'ext.proofreadpage.index/ext.proofreadpage.index.css',
		'dependencies' => array( 'ext.proofreadpage.base', 'jquery.ui.autocomplete' )
	),
);

$wgHooks['SetupAfterCache'][] = 'ProofreadPageInit::initNamespaces';
$wgHooks['ParserFirstCallInit'][] = 'ProofreadPage::onParserFirstCallInit';
$wgHooks['BeforePageDisplay'][] = 'ProofreadPage::onBeforePageDisplay';
$wgHooks['GetLinkColours'][] = 'ProofreadPage::onGetLinkColours';
$wgHooks['ImageOpenShowImageInlineBefore'][] = 'ProofreadPage::onImageOpenShowImageInlineBefore';
$wgHooks['EditPage::attemptSave'][] = 'ProofreadPage::onEditPageAttemptSave';
$wgHooks['ArticleSaveComplete'][] = 'ProofreadPage::onArticleSaveComplete';
$wgHooks['ArticleDelete'][] = 'ProofreadPage::onArticleDelete';
$wgHooks['ArticleUndelete'][] = 'ProofreadPage::onArticleUndelete';
$wgHooks['EditFormPreloadText'][] = 'ProofreadPage::onEditFormPreloadText';
$wgHooks['ArticlePurge'][] = 'ProofreadPage::onArticlePurge';
$wgHooks['SpecialMovepageAfterMove'][] = 'ProofreadPage::onSpecialMovepageAfterMove';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'ProofreadPage::onLoadExtensionSchemaUpdates';
$wgHooks['EditPage::importFormData'][] = 'ProofreadPage::onEditPageImportFormData';
$wgHooks['OutputPageParserOutput'][] = 'ProofreadPage::onOutputPageParserOutput';
$wgHooks['wgQueryPages'][] = 'ProofreadPage::onwgQueryPages';
$wgHooks['GetPreferences'][] = 'ProofreadPage::onGetPreferences';
$wgHooks['LinksUpdateConstructed'][] = 'ProofreadPage::onLinksUpdateConstructed';
$wgHooks['CustomEditor'][] = 'ProofreadPage::onCustomEditor';


//inclusion of i18n file. $wgExtensionMessagesFiles[] doesn't works
include_once( $dir . 'ProofreadPage.namespaces.php' );
