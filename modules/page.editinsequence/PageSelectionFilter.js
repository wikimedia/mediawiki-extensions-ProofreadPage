function generateFilterForPageSelection( pageStatus ) {
	return function ( pagelistArray, pageSelectionWidget ) {
		const pageFilter = [];
		for ( let i = 0; i < pagelistArray.length; i++ ) {
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
	{ icon: 'pagequality-level0', label: mw.msg( 'proofreadpage_quality0_summary' ), name: 'level0', func: generateFilterForPageSelection( 0 ) },
	{ icon: 'pagequality-level1', label: mw.msg( 'proofreadpage_quality1_summary' ), name: 'level1', func: generateFilterForPageSelection( 1 ) },
	{ icon: 'pagequality-level2', label: mw.msg( 'proofreadpage_quality2_summary' ), name: 'level2', func: generateFilterForPageSelection( 2 ) },
	{ icon: 'pagequality-level3', label: mw.msg( 'proofreadpage_quality3_summary' ), name: 'level3', func: generateFilterForPageSelection( 3 ) },
	{ icon: 'pagequality-level4', label: mw.msg( 'proofreadpage_quality4_summary' ), name: 'level4', func: generateFilterForPageSelection( 4 ) },
	{ icon: 'link', label: mw.msg( 'prp-editinsequence-page-filter-redlink' ), name: 'levelminus1', func: generateFilterForPageSelection( -1 ) }
];
