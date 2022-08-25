$( function () {
	'use strict';
	/* add backlink to index page */
	var linkUrl;

	if ( mw.config.get( 'prpSourceIndexPage' ) ) {
		linkUrl = mw.util.getUrl( mw.config.get( 'prpSourceIndexPage' ) );
	} else {
		// TODO(sohom): Remove 'proofreadpage_source_href' after a bit,
		// currently supported so as to not break the functionality
		// while trasfering to 'prpSourceIndexPage'
		var anchorEl = $.parseHTML(
			mw.config.get( 'proofreadpage_source_href' )
		);
		linkUrl = anchorEl.attr( 'href' );
	}

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
} );
