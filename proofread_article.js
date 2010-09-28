// Author : ThomasV - License : GPL

/* add backlink to index page */
function pr_add_source() {
	$( '#ca-nstab-main' ).after( '<li><span>' + proofreadpage_source_href + '</span></li>' );
}

$(document).ready( pr_add_source );
