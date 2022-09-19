// TODO: remove when all pages will be purged
$( function () {
	'use strict';
	/* add backlink to index page */
	var linkUrl;

	if ( mw.config.get( 'prpSourceIndexPage' ) ) {
		linkUrl = mw.util.getUrl( mw.config.get( 'prpSourceIndexPage' ) );
	} else if ( mw.config.get( 'proofreadpage_source_href' ) ) {
		var anchorEl = $.parseHTML(
			mw.config.get( 'proofreadpage_source_href' )
		);
		linkUrl = anchorEl.attr( 'href' );
	}

	if ( linkUrl ) {
		mw.util.addPortletLink(
			// Namespaces menu
			'p-namespaces',
			// link URL
			linkUrl,
			// visual label message
			mw.msg( 'proofreadpage_source' ),
			// element ID
			'ca-proofread-source',
			// tooltip (title attribute) message
			mw.msg( 'proofreadpage_source_message' ),
			// accesskey
			null,
			// insert after talk page link
			'#ca-talk'
		);
	}
} );
