<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgHooks['OutputPageParserOutput'][] = 'wfProofreadPageParserOutput';
$wgHooks['LoadAllMessages'][] = 'wfProofreadPageLoadMessages';
$wgHooks['GetLinkColour'][] = 'wfProofreadPageLinkColour';
$wgHooks['GetLinkColourCode'][] = 'wfProofreadPageColourCode';

$wgExtensionCredits['other'][] = array(
	'name' => 'ProofreadPage',
	'author' => 'ThomasV',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Proofread_Page',
	'description' => 'Allow easy comparison of text to the original scan',
);

# Bump the version number every time you change proofread.js
$wgProofreadPageVersion = 2;

/**
 * 
 * Query the database to find if the current page is referred in an
 * Index page. If yes, return the URLs of the index, previous and next pages.
 * 
 */

function wfProofreadPageNavigation() {
	global $wgTitle, $wgExtraNamespaces;
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	$err = array( '', '', '' );

	$dbr = wfGetDB( DB_SLAVE );
	$result = $dbr->select(
			array( 'page', 'pagelinks' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'pl_namespace' => $wgTitle->getNamespace(),
				'pl_title' => $wgTitle->getDBkey(),
				'pl_from=page_id'
			),
			__METHOD__);
	while( $x = $dbr->fetchObject( $result ) ) {
		$ref_title = Title::makeTitle( $x->page_namespace, $x->page_title );
		if( preg_match( "/^$index_namespace:(.*)$/", $ref_title->getPrefixedText() ) ) {
			break;
		}
	}
	if( !$x ) {
		return $err;
	}
	$dbr->freeResult( $result ) ;

	$index_title = $ref_title;
	$index_url = $index_title->getFullURL();
	$rev = Revision::newFromTitle( $index_title );
	$text =	$rev->getText();

	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
	$tag_pattern = "/\[\[($page_namespace:.*?)(\|.*?|)\]\]/i";
	preg_match_all( $tag_pattern, $text, $links, PREG_PATTERN_ORDER );

	for( $i=0; $i<count( $links[1] ); $i++) { 
		$a_title = Title::newFromText( $links[1][$i] );
		if(!$a_title) continue; 
		if( $a_title->getPrefixedText() == $wgTitle->getPrefixedText() ) break;
	}
	if( ($i>0) && ($i<count($links[1])) ){
		$prev_title = Title::newFromText( $links[1][$i-1] );
		if(!$prev_title) return $err; 
		$prev_url = $prev_title->getFullURL();
	}
	else $prev_url = '';
	if( ($i>=0) && ($i+1<count($links[1])) ){
		$next_title = Title::newFromText( $links[1][$i+1] );
		if(!$next_title) return $err; 
		$next_url = $next_title->getFullURL();
	} 
	else $next_url = '';

	return array( $index_url, $prev_url, $next_url );

}


/**
 * 
 * Append javascript variables and code to the page.
 * 
 */

function wfProofreadPageParserOutput( &$out, &$pout ) {
	global $wgTitle, $wgJsMimeType, $wgScriptPath,  $wgRequest, $wgProofreadPageVersion;

	wfProofreadPageLoadMessages();
	$action = $wgRequest->getVal('action');
	$isEdit = ( $action == 'submit' || $action == 'edit' ) ? 1 : 0;
	if ( !isset( $wgTitle ) || ( !$out->isArticle() && !$isEdit ) || isset( $out->proofreadPageDone ) ) {
		return true;
	}
	$out->proofreadPageDone = true;

	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
	if ( !preg_match( "/^$page_namespace:(.*?)(\/([0-9]*)|)$/", $wgTitle->getPrefixedText(), $m ) ) {
		return true;
	}

	list($index_url,$prev_url,$next_url ) = wfProofreadPageNavigation();

	$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
	if ( !$imageTitle ) {
		return true;
	}

	$image = Image::newFromTitle( $imageTitle );
	if ( $image->exists() ) {
		$width = intval( $image->getWidth() );
		$height = intval( $image->getHeight() );
		if($m[2]) { 
			$viewName = $image->thumbName( array( 'width' => $width, 'page' => $m[3] ) );
			$viewURL = $image->getThumbUrl( $viewName );

			$thumbName = $image->thumbName( array( 'width' => '##WIDTH##', 'page' => $m[3] ) );
			$thumbURL = $image->getThumbUrl( $thumbName );
		}
		else {
			$viewURL = Xml::escapeJsString(	$image->getViewURL() );
			$thumbName = $image->thumbName( array( 'width' => '##WIDTH##' ) );
			$thumbURL = $image->getThumbUrl( $thumbName );
		}
		$thumbURL = Xml::escapeJsString( str_replace( '%23', '#', $thumbURL ) );
	} 
	else {	
		$width = 0;
		$height = 0;
		$viewURL = '';
		$thumbURL = '';
	}

	$jsFile = htmlspecialchars( "$wgScriptPath/extensions/ProofreadPage/proofread.js?$wgProofreadPageVersion" );

	$out->addScript( <<<EOT
<script type="$wgJsMimeType">
var proofreadPageWidth = $width;
var proofreadPageHeight = $height;
var proofreadPageViewURL = "$viewURL";
var proofreadPageThumbURL = "$thumbURL";
var proofreadPageIsEdit = $isEdit;
var proofreadPageIndexURL = "$index_url";
var proofreadPagePrevURL = "$prev_url";
var proofreadPageNextURL = "$next_url";
</script>
<script type="$wgJsMimeType" src="$jsFile"></script>

EOT
	);

        # Add messages from i18n
        $out->addScript( "<script type=\"{$wgJsMimeType}\"> 
var proofreadPageMessageIndex = \"" . Xml::escapeJsString(wfMsg('proofreadpage_index')) . "\";
var proofreadPageMessageNextPage = \"" . Xml::escapeJsString(wfMsg('proofreadpage_nextpage')) . "\";
var proofreadPageMessagePrevPage = \"" . Xml::escapeJsString(wfMsg('proofreadpage_prevpage')) . "\";
var proofreadPageMessageImage = \"" . Xml::escapeJsString(wfMsg('proofreadpage_image')) . "\";
var proofreadPageMessageHeader = \"" . Xml::escapeJsString(wfMsg('proofreadpage_header')) . "\";
var proofreadPageMessagePageBody = \"" . Xml::escapeJsString(wfMsg('proofreadpage_body')) . "\";
var proofreadPageMessageFooter = \"" . Xml::escapeJsString(wfMsg('proofreadpage_footer')) . "\";
var proofreadPageMessageToggleHeaders = \"" . Xml::escapeJsString(wfMsg('proofreadpage_toggleheaders')) . "\";
</script>\n" 
        );
	return true;
}

function wfProofreadPageLoadMessages() {
	global $wgMessageCache;
	static $done = false;
	if ( $done ) return true;

	require( dirname( __FILE__ ) . '/ProofreadPage.i18n.php' );
	foreach ( $messages as $lang => $messagesForLang ) {
		$wgMessageCache->addMessages( $messagesForLang, $lang );
	}
	return true;
}





/**
 *  Give quality colour codes to pages linked from an index page
 */
function wfProofreadPageLinkColour( $dbr, $id, &$colour ) {
	global $wgTitle;

	if ( !isset( $wgTitle ) ) {
		return true;
	}
	wfProofreadPageLoadMessages();

	// abort if we are not an index page
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	if ( !preg_match( "/^$index_namespace:(.*?)$/", $wgTitle->getPrefixedText() ) ) {
		return true;
	}

	// abort if link is not in page namespace
	$title = Title::newFromId($id);
	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
	if ( !preg_match( "/^$page_namespace:(.*?)$/", $title->getPrefixedText() ) ) {
		return true;
	}

	// make 'quality1' the default value
	$colour = 2;

	// check if page belongs to one of the special categories
	$result = $dbr->select('categorylinks',
				array('cl_to'),
				array('cl_from' => $id),
				__METHOD__ );

	while($x = $dbr->fetchObject($result)){
		if($x->cl_to == wfMsgForContent('proofreadpage_quality1_category')) $colour = 2;
		if($x->cl_to == wfMsgForContent('proofreadpage_quality2_category')) $colour = 3;
		if($x->cl_to == wfMsgForContent('proofreadpage_quality3_category')) $colour = 4;
		if($x->cl_to == wfMsgForContent('proofreadpage_quality4_category')) $colour = 5;
	}

	return true;
}


/**
 *  Convert colour numbers to css class names. 
 */
function wfProofreadPageColourCode( &$colourcode ) {
	global $wgTitle;


	if ( !isset( $wgTitle ) ) {
		return true;
	}
	wfProofreadPageLoadMessages();

	// abort if we are not an index page
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	if ( !preg_match( "/^$index_namespace:(.*?)$/", $wgTitle->getPrefixedText() ) ) {
		return true;
	}

	$colourcode = array(
		0 => 'new',
		1 => '',
		2 => 'quality1',
		3 => 'quality2',
		4 => 'quality3',
		5 => 'quality4',
	);

	return true;
}

