( function ( mw, $ ) {
	'use strict';

	/**
	 * Get the selector for the main tabs container
	 *
	 * @return {string}
	 */
	function getTabsContainerSelector() {
		switch ( mw.config.get( 'skin' ) ) {
			case 'vector':
				return '#p-namespaces ul';
			case 'monobook':
			case 'modern':
				return '#p-cactions ul';
			default:
				return '';
		}
	}

	/*
	 * Improve the tabs presentation
	 */
	function initTabs() {
		var id, $link,
			tabIcons = {
				proofreadPagePrevLink: 'previous',
				proofreadPageNextLink: 'next',
				proofreadPageIndexLink: 'collapse'
			},
			$tabs = $( getTabsContainerSelector() );

		// Move prev and next links (swapped for rtl languages)
		if ( $( 'html' ).attr( 'dir' ) === 'rtl' ) {
			$tabs
				.append( $( '#ca-proofreadPageNextLink' ) )
				.append( $( '#ca-proofreadPagePrevLink' ) );
		} else {
			$tabs
				.prepend( $( '#ca-proofreadPageNextLink' ) )
				.prepend( $( '#ca-proofreadPagePrevLink' ) );
		}

		// add title attribute to links move to icon
		for ( id in tabIcons ) {
			$link = $( '#ca-' + id + '.icon a' );
			$link.attr( 'title', $link.text() )
				.addClass( 'oo-ui-icon-' + tabIcons[ id ] );
		}

		$tabs.addClass( 'prp-tabs' );
	}

	$( function () {
		initTabs();
	} );

}( mediaWiki, jQuery ) );
