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
			tabs = {
				proofreadPagePrevLink: 'previous',
				proofreadPageNextLink: 'next',
				proofreadPageIndexLink: 'collapse'
			};

		mw.loader.using( 'oojs-ui.styles.icons' ).then( function () {

			// Move prev and next links (swapped for rtl languages)
			if ( $( 'html' ).attr( 'dir' ) === 'rtl' ) {
				$( getTabsContainerSelector() )
					.append( $( '#ca-proofreadPageNextLink' ) )
					.append( $( '#ca-proofreadPagePrevLink' ) );
			} else {
				$( getTabsContainerSelector() )
					.prepend( $( '#ca-proofreadPageNextLink' ) )
					.prepend( $( '#ca-proofreadPagePrevLink' ) );
			}

			// add title attribute to links move to icon
			for ( id in tabs ) {
				$link = $( '#ca-' + id + '.icon a' );
				$link.attr( 'title', $link.text() )
					.addClass( 'oo-ui-icon-' + tabs[ id ] );
			}
		} );
	}

	$( document ).ready( function () {
		initTabs();
	} );

}( mw, jQuery ) );
