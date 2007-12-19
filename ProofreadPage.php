<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgHooks['OutputPageParserOutput'][] = 'wfPRParserOutput';
$wgHooks['LoadAllMessages'][] = 'wfPRLoadMessages';
$wgHooks['GetLinkColours'][] = 'wfPRLinkColours';
$wgHooks['ArticleViewHeader'][] = 'wfPRRenderLinks';
$wgHooks['ImageOpenShowImageInlineBefore'][] = 'wfPRImageMessage';




$wgExtensionCredits['other'][] = array(
	'name' => 'ProofreadPage',
	'author' => 'ThomasV',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Proofread_Page',
	'description' => 'Allow easy comparison of text to the original scan',
);

# Bump the version number every time you change proofread.js
$wgProofreadPageVersion = 5;

/**
 * 
 * Query the database to find if the current page is referred in an
 * Index page. If yes, return the URLs of the index, previous and next pages.
 * 
 */

function wfPRNavigation( $image ) {
	global $wgTitle;
	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
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
	$dbr->freeResult( $result ) ;

	if( !$x ) { // there is no index page; but maybe we can create one (for multipage documents like .pdf and .djvu)
		$handler = $image->getHandler();
		if( $image->exists() && $handler && $handler->isMultiPage() ) {
			$pagenr = 1;
			$parts = explode( '/', $wgTitle->getText() );
			if( count( $parts ) > 1 ) {
				$pagenr = intval( array_pop( $parts ) );
			}
			$count = $handler->pageCount( $image );
			if( $pagenr < 1 || $pagenr > $count || $count == 1 )
				return $err;

			$name = $image->getTitle()->getText();
			$prev_name = "$page_namespace:$name/" . ( $pagenr - 1 );
			$next_name = "$page_namespace:$name/" . ( $pagenr + 1 );
			$index_name = "$index_namespace:$name";
			$prev_url = ( $pagenr == 1 ) ? '' : Title::newFromText( $prev_name )->getFullURL();
			$next_url = ( $pagenr == $count ) ? '' : Title::newFromText( $next_name )->getFullURL();
			$index_url = Title::newFromText( $index_name )->getFullURL();
			return array( $index_url, $prev_url, $next_url );
		}
		return $err;
	}

	$index_title = $ref_title;
	$index_url = $index_title->getFullURL();
	$rev = Revision::newFromTitle( $index_title );
	$text =	$rev->getText();

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

function wfPRParserOutput( &$out, &$pout ) {
	global $wgTitle, $wgJsMimeType, $wgScriptPath,  $wgRequest, $wgProofreadPageVersion;

	wfPRLoadMessages();
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
	
	list( $index_url, $prev_url, $next_url ) = wfPRNavigation( $image );

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

function wfPRLoadMessages() {
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
function wfPRLinkColours( $page_ids, &$colours ) {
	global $wgTitle;

	if ( !isset( $wgTitle ) ) {
		return true;
	}
	wfPRLoadMessages();

	// abort if we are not an index page
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	if ( !preg_match( "/^$index_namespace:(.*?)$/", $wgTitle->getPrefixedText() ) ) {
		return true;
	}

	$dbr = wfGetDB( DB_SLAVE );
	$catlinks = $dbr->tableName( 'categorylinks' );
	foreach ( $page_ids as $id => $pdbk ) {

		// consider only link in page namespace
		$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
		if ( preg_match( "/^$page_namespace:(.*?)$/", $pdbk ) ) {

			$colours[$pdbk] = 'quality1';

			if ( !isset( $query ) ) {
				$query =  "SELECT cl_from, cl_to FROM $catlinks WHERE cl_from IN(";
			} else {
				$query .= ', ';
			}
			$query .= $id;
		}
	}
	if ( isset( $query ) ) {
		$query .= ')';
		$res = $dbr->query( $query, __METHOD__ );

		while ( $x = $dbr->fetchObject($res) ) {

			$pdbk = $page_ids[$x->cl_from];

			if($x->cl_to == wfMsgForContent('proofreadpage_quality1_category')) $colours[$pdbk] = 'quality1';
			if($x->cl_to == wfMsgForContent('proofreadpage_quality2_category')) $colours[$pdbk] = 'quality2';
			if($x->cl_to == wfMsgForContent('proofreadpage_quality3_category')) $colours[$pdbk] = 'quality3';
			if($x->cl_to == wfMsgForContent('proofreadpage_quality4_category')) $colours[$pdbk] = 'quality4';
			
		}
	}

	return true;
}

function wfPRImageMessage(  &$imgpage , &$wgOut ) {

	global $wgUser;
	$sk = $wgUser->getSkin();

	$image = $imgpage->img;
	if ( !$image->isMultipage() ) {
		return true;
	}

	wfPRLoadMessages();
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	$name = $image->getTitle()->getText();

	$link = $sk->makeKnownLink( "$index_namespace:$name", wfMsg('proofreadpage_image_message') );
	$wgOut->addHTML( "{$link}" );

	return true;
}


function wfPRRenderLinks( &$article, &$outputDone, &$pcache){

	global $wgUser, $wgOut, $wgTitle;

	wfPRLoadMessages();
	$index_namespace = preg_quote( wfMsgForContent( 'proofreadpage_index_namespace' ), '/' );
	if ( !preg_match( "/^$index_namespace:(.*?)(\/([0-9]*)|)$/", $wgTitle->getPrefixedText(), $m ) ) {
		return true;
	}

	$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
	if ( !$imageTitle ) {
		return true;
	}
	$image = Image::newFromTitle( $imageTitle );

	if ( $image->isMultipage() ) {

		$name = $imageTitle->getText();
		$count = $image->pageCount();
		$wgOut->addHTML( '<h2>' . wfMsg('proofreadpage_index_listofpages'). '</h2>' );

		$dbr = wfGetDB( DB_SLAVE );
		$pagetable = $dbr->tableName( 'page' );

		$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ), '/' );
		$page_ns_index = Namespace::getCanonicalIndex( strtolower( $page_namespace ) );
		if( $page_ns_index == NULL ) {
			$page_ns_index = NS_MAIN;
		}

		for( $i=0; $i<$count ; $i++) { 

			if ( !isset( $query ) ) {
				$query =  "SELECT page_id, page_title, page_namespace";
				$query .= " FROM $pagetable WHERE (page_namespace=$page_ns_index AND page_title IN(";
			} else {
				$query .= ', ';
			}
			$link_name = "$page_namespace:$name" . '/'. ($i+1) ;	
			$query .= $dbr->addQuotes( $link_name );
		}
		$query .= '))';
		$res = $dbr->query( $query, __METHOD__ );

		$colours = array();
		$linkcolour_ids = array();
		while ( $s = $dbr->fetchObject($res) ) {
			$title = Title::makeTitle( $s->page_namespace, $s->page_title );
			$pdbk = $title->getPrefixedDBkey();
			$colours[$pdbk] = 'known';
			$linkcolour_ids[$s->page_id] = $pdbk;
		}
		wfPRLinkColours( $linkcolour_ids, $colours );

		$sk = $wgUser->getSkin();
		for( $i=1; $i<$count+1 ; $i++) { 

			$pdbk = "$page_namespace:$name" . '/'. $i ;
			$title = Title::newFromText( $pdbk );
			if ( !isset( $colours[$pdbk] ) ) {
				$link = $sk->makeBrokenLinkObj( $title, ' '.$i );
			} else {
				$link = $sk->makeColouredLinkObj( $title, $colours[$pdbk], ' '.$i );
			}
			$wgOut->addHTML( "{$link}" );
		}
		$wgOut->addHTML( "<br/>" );

	}
	return true;
}
