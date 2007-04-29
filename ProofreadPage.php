<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgHooks['OutputPageParserOutput'][] = 'wfProofreadPageParserOutput';
$wgHooks['LoadAllMessages'][] = 'wfProofreadPageLoadMessages';

$wgExtensionCredits['parserhook'][] = array(
	'name' => 'ProofreadPage',
	'author' => 'ThomasV'
);



/**
 * 
 * Query the database to find if the current page is referred in an
 * Index page. If yes, return the URLs of the index, previous and next pages.
 * 
 */

function wfProofreadPageNavigation( ) {
	global $wgTitle, $wgExtraNamespaces;
	$index_namespace = preg_quote( wfMsgForContent('proofreadpage_index_namespace' ) );
	$err = array( '', '', '' );

	$dbr =& wfGetDB( DB_SLAVE );
	$pagelinks = $dbr->tableName( 'pagelinks' );
	$sql = "SELECT pl_from FROM $pagelinks WHERE pl_title=? LIMIT 100" ;
	$result = $dbr->safeQuery ( $sql, $wgTitle->getDBKey() ) ;
	while( $x = $dbr->fetchObject ( $result )) {

		$page_table = $dbr->tableName( 'page' );
		$sql = "SELECT page_title, page_namespace, page_latest	FROM $page_table WHERE page_id=? LIMIT 1" ;
		$result2 = $dbr->safeQuery ( $sql, $x->pl_from ) ;
		$y = $dbr->fetchObject ( $result2 );
		$dbr->freeResult( $result2 ) ;
		if(!$y) { continue; }

		$ref_title = Title::makeTitle( $y->page_namespace, $y->page_title );
		if(!$ref_title) { continue; }

		if ( preg_match( "/^$index_namespace:(.*)$/", $ref_title->getPrefixedText() ) ) {
			break;
		}
		else { continue;}

	}
	if( !$x ) { return $err;}
	$dbr->freeResult( $result ) ;

	$index_title = $ref_title;
	$index_url = $index_title->getFullURL();

	$revision_table = $dbr->tableName( 'revision' );
	$sql = "SELECT rev_text_id FROM $revision_table WHERE rev_id=? LIMIT 1" ;
	$result = $dbr->safeQuery ( $sql, $y->page_latest ) ;
	$z = $dbr->fetchObject ( $result ); 
	$dbr->freeResult ( $result ) ;
	if( !$z ) return $err;

	$text = $dbr->tableName( 'text' );
	$sql = "SELECT old_text FROM $text WHERE old_id=? LIMIT 1" ;
	$result = $dbr->safeQuery ( $sql, $z->rev_text_id ) ;
	$zz = $dbr->fetchObject ( $result ); 
	$dbr->freeResult( $result ) ;
	if( !$zz ) return $err;

	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ) );
	$tag_pattern = "/\[\[($page_namespace:[\s\S]*?)\]\]/m";
	preg_match_all( $tag_pattern, $zz->old_text, $links, PREG_PATTERN_ORDER );

	for( $i=0; $i<count( $links[1] ); $i++) { 
		if( $links[1][$i]==$wgTitle->getPrefixedText() ) break;
	}
	if( $i>0 ){
		$prev_title = Title::newFromText( $links[1][$i-1] );
		$prev_url = $prev_title->getFullURL();
	} else $prev_url = '';

	if( $i+1<count($links[1])){ 
		$next_title = Title::newFromText( $links[1][$i+1] );
		$next_url = $next_title->getFullURL();
	} else $next_url = '';

	return array( $index_url, $prev_url, $next_url );

}


/**
 * 
 * Append javascript variables and code to the page.
 * 
 */

function wfProofreadPageParserOutput( &$out, &$pout ) {

	global $wgTitle, $wgJsMimeType, $wgScriptPath,  $wgRequest;

	wfProofreadPageLoadMessages();
	$action = $wgRequest->getVal('action');
	$isEdit = ( $action == 'submit' || $action == 'edit' ) ? 1 : 0;
	if ( !isset( $wgTitle ) || ( !$out->isArticle() && !$isEdit ) || isset( $out->proofreadPageDone ) ) {
		return true;
	}
	$out->proofreadPageDone = true;

	$page_namespace = preg_quote( wfMsgForContent( 'proofreadpage_namespace' ) );
	if ( !preg_match( "/^$page_namespace:(.*)$/", $wgTitle->getPrefixedText(), $m ) ) {
		return true;
	}

	list($index_url,$prev_url,$next_url ) = wfProofreadPageNavigation();

	$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
	if ( !$imageTitle ) {
		return true;
	}

	$image = new Image( $imageTitle );
	if ( $image->exists() ) {
		$width = intval( $image->getWidth() );
		$height = intval( $image->getHeight() );
		$viewURL = Xml::escapeJsString( $image->getViewURL() );
		list( $isScript, $thumbURL ) = $image->thumbUrl( '##WIDTH##' );
		$thumbURL = Xml::escapeJsString( str_replace( '%23', '#', $thumbURL ) );
	} 
	else {	
		$width = 0;
		$height = 0;
		$viewURL = '';
		$thumbURL = '';
	}

	$jsFile = htmlspecialchars( "$wgScriptPath/extensions/ProofreadPage/proofread.js" );

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
	return true;
}

function wfProofreadPageLoadMessages() {
	global $wgMessageCache;
	static $done = false;
	if ( $done ) return;

	require( dirname( __FILE__ ) . '/ProofreadPage.i18n.php' );
	foreach ( $messages as $lang => $messagesForLang ) {
		$wgMessageCache->addMessages( $messagesForLang, $lang );
	}
}

?>
