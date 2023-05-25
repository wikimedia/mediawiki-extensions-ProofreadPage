function generateFilterForPageSelection( pageStatus ) {
	return function ( pagelistArray, pageSelectionWidget ) {
		var pageFilter = [];
		for ( var i = 0; i < pagelistArray.length; i++ ) {
			if ( pagelistArray[ i ].pageStatus === pageStatus ) {
				pageFilter.push( true );
			} else {
				pageFilter.push( false );
			}
		}

		pageSelectionWidget.setHighlightedButtons( pageFilter );
	};
}

module.exports = [
	{ label: mw.msg( 'proofreadpage_quality0_summary' ), name: 'level0', func: generateFilterForPageSelection( 0 ) },
	{ label: mw.msg( 'proofreadpage_quality1_summary' ), name: 'level1', func: generateFilterForPageSelection( 1 ) },
	{ label: mw.msg( 'proofreadpage_quality2_summary' ), name: 'level2', func: generateFilterForPageSelection( 2 ) },
	{ label: mw.msg( 'proofreadpage_quality3_summary' ), name: 'level3', func: generateFilterForPageSelection( 3 ) },
	{ label: mw.msg( 'proofreadpage_quality4_summary' ), name: 'level4', func: generateFilterForPageSelection( 4 ) },
	{ label: mw.msg( 'prp-editinsequence-page-filter-redlink' ), name: 'levelminus1', func: generateFilterForPageSelection( -1 ) }
];
