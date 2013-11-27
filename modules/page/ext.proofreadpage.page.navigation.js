( function ( mw, $ ) {
	'use strict';

	/**
	 * Get the selector for the main tabs container
	 *
	 * @return string
	 */
	function getTabsContainerSelector() {
		switch( mw.config.get( 'skin' ) ) {
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
		//Move prev and next links
		$( getTabsContainerSelector() )
			.prepend( $( '#ca-proofreadPageNextLink') )
			.prepend( $( '#ca-proofreadPagePrevLink') );

		//add title attribute to links move to icon
		var tabsToIcon = [ 'proofreadPagePrevLink', 'proofreadPageNextLink', 'proofreadPageIndexLink' ];
		$.each( tabsToIcon, function( i, id ) {
			var $link = $( '#ca-' + id + '.icon a' );
			$link.attr( 'title', $link.text() );
		} );
	}

	$( document ).ready( function() {
		initTabs();
	} );

} ( mediaWiki, jQuery ) );