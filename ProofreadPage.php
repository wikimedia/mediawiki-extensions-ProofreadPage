<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgHooks['OutputPageParserOutput'][] = 'wfProofreadPageParserOutput';

function wfProofreadPageParserOutput( &$out, &$pout ) {
	global $wgTitle, $wgJsMimeType, $wgScriptPath,  $wgRequest;
	$action = $wgRequest->getVal('action');
	$isEdit = ( $action == 'submit' || $action == 'edit' ) ? 1 : 0;
	if ( !isset( $wgTitle ) || ( !$out->isArticle() && !$isEdit ) || isset( $out->proofreadPageDone ) ) {
		return true;
	}
	$out->proofreadPageDone = true;
	if ( !preg_match( '/^Page:(.*)$/', $wgTitle->getPrefixedText(), $m ) ) {
		return true;
	}
	$imageTitle = Title::makeTitleSafe( NS_IMAGE, $m[1] );
	if ( !$imageTitle ) {
		return true;
	}
	$image = new Image( $imageTitle );
	if ( !$image->exists() ) {
		return true;
	}
	$width = intval( $image->getWidth() );
	$height = intval( $image->getHeight() );
	$viewURL = Xml::escapeJsString( $image->getViewURL() );
	list( $isScript, $thumbURL ) = $image->thumbUrl( '##WIDTH##' );
	$thumbURL = Xml::escapeJsString( str_replace( '%23', '#', $thumbURL ) );
	$jsFile = htmlspecialchars( "$wgScriptPath/extensions/ProofreadPage/proofread.js" );

	$out->addScript( <<<EOT
<script type="$wgJsMimeType">
var proofreadPageWidth = $width;
var proofreadPageHeight = $height;
var proofreadPageViewURL = "$viewURL";
var proofreadPageThumbURL = "$thumbURL";
var proofreadPageIsEdit = $isEdit;
</script>
<script type="$wgJsMimeType" src="$jsFile"></script>

EOT
	);
	return true;
}

?>
