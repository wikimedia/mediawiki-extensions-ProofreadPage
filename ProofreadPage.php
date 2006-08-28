<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "ProofreadPage extension\n" );
}

$wgExtensionFunctions[] = 'wfProofreadPageSetup';

function wfProofreadPageSetup() {
	global $wgParser, $wgHooks;
	$wgHooks['OutputPageParserOutput'][] = 'wfProofreadPageParserOutput';
}

function wfProofreadPageParserOutput( &$out, &$pout ) {
	global $wgTitle, $wgJsMimeType;
	if ( !isset( $wgTitle ) ) {
		return true;
	}
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

	$out->addScript( <<<EOT
<script type="$wgJsMimeType">
var proofreadPageWidth = $width;
var proofreadPageHeight = $height;
var proofreadPageViewURL = "$viewURL";
var proofreadPageThumbURL = "$thumbURL";
</script>

EOT
	);
	return true;
}

?>
