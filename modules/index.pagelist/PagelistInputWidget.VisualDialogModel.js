const DialogModel = require( './PagelistInputWidget.DialogModel.js' );
const Parameters = require( './PagelistInputWidget.Parameters.js' );

/**
 * A model used to coordinate various parts of the Dialog UI.
 * The model caches changes to the parameters while the dialog is open
 * and then syncs the changes to the mainModel once the dialog closes.
 *
 * This particular one does quite a lot of the heavy lifting in
 * translating the user's actions into the pagelist parameters.
 *
 * @param {Object} data
 * @param {mw.proofreadpage.PagelistInputWidget.Model} mainModel
 * @class
 */
function VisualDialogModel( data, mainModel ) {
	VisualDialogModel.super.call( this, data, mainModel );
	this.parameters = null;
	this.changed = false;
	this.mainModel.connect( this, {
		parametersUpdated: 'updateCachedDataFromMainModel',
		enumeratedListCreated: 'pagelistPreviewGenerationDone',
		parsingerror: 'handleError'
	} );
	this.lengthOfPagelist = 0;
}

OO.inheritClass( VisualDialogModel, DialogModel );

/**
 * Updates the parameters based on user inputs all which are contained
 * in the `data` parameters. The data object should contain four parameters,
 * `subPage`,`type`, `single` and `number`.
 *
 * subPage - Refers to the scan number the user is on
 * type    - Refers to the label used to specify for a particular range (i.e. 'Cover' in `<pagelist 2to7=Cover />`)
 * single  - Whether or not to change the label for only one scan number
 * number  - Any numbering change that needs to be done.
 *
 * @param {Object} data A associative array containing the users inputs.
 */
VisualDialogModel.prototype.updateCachedData = function ( data ) {
	const params = this.parameters, partialList = this.generatePartialList();

	this.setRanges( params, data, partialList );

	this.setNumbering( params, data );

	// localStorage based debug mode that can be accessed in the frontend
	// by using mw.storage.set( 'proofreadpage-pagelist-widget-visual-mode-debug', true );
	if ( mw.storage.get( 'proofreadpage-pagelist-widget-visual-mode-debug' ) ) {
		this.debugOutput( params );
	}

	this.parameters = params;
	this.changed = true;
	this.mainModel.generateEnumeratedList( params );
};

/**
 * Sets the ranges based on data it recieves
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Parameters} params Pagelist parameters
 * @param {Object} data        Refer to the description of updateCacheData()
 * @param {Array} partialList  Refer to the description of generatePartialList()
 *
 * @return {mw.proofreadpage.PagelistInputWidget.Parameters}
 */
VisualDialogModel.prototype.setRanges = function ( params, data, partialList ) {
	const subPage = this.data.subPage,
		nextChangePoint = this.findNextChangePoint( partialList, subPage, null, params );
	if ( data.single ) {
		// If the user wants to change ony one single page
		this.mergeContigousRanges( partialList, params, subPage, subPage, data.label, true );
	} else {
		if ( !partialList[ subPage ] && data.label !== 'Number' ) {
			// If there is no pre-existing ranges in the area, make one to go from current subPage to
			this.mergeContigousRanges( partialList, params, subPage, nextChangePoint, data.label, false );
		} else if ( partialList[ subPage ] && data.label !== partialList[ subPage ][ 0 ].type ) {
			// If they have pre-existing ranges
			this.setRangesForOverlappingRanges( params, data, partialList, subPage );
		}
	}

	return params;
};

/**
 * Sets ranges for overlapping ranges
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Parameters} params
 * @param {Object} data        Refer to the description of updateCacheData()
 * @param {Array} partialList  Refer to the description of generatePartialList()
 */
VisualDialogModel.prototype.setRangesForOverlappingRanges = function ( params, data, partialList ) {
	const subPage = this.data.subPage,
		oldRange = ( partialList[ subPage ] && partialList[ subPage ][ 1 ] && partialList[ subPage ][ 1 ].type ) || 'Number',
		start = partialList[ subPage ] && partialList[ subPage ][ 0 ].from,
		end = partialList[ subPage ] && partialList[ subPage ][ 0 ].to,
		type = partialList[ subPage ] && partialList[ subPage ][ 0 ].type,
		nextChangePoint = this.findNextChangePoint( partialList, subPage, type, params );

	// Check if the user is at the start of the old range, if so, they are probably trying
	// to change the overlapping range itself and creating new ranges will be counterproductive.
	if ( start !== subPage ) {
		if ( data.label !== oldRange && data.label !== 'Number' ) {
			if ( nextChangePoint === end ) {
				// Shorten the old range and add the new range after that
				params.delete( start + 'to' + end );
				params.set( start + 'to' + ( subPage - 1 ), type );
				params.set( subPage + 'to' + nextChangePoint, data.label );
			} else {
				// Create a new range over the old range.
				this.mergeContigousRanges( partialList, params, subPage, nextChangePoint, data.label, false );
			}
		} else {
			// We realize that the `type` the user has entered is actually the same as that
			// of the label of the range under the current range ( or is 'Number' which is being considered
			// as the master base range )
			params.delete( start + 'to' + end );
			params.set( start + 'to' + ( subPage - 1 ), type );
		}

		this.resurfaceHiddenEntries( params, start, end );
	} else if ( data.label !== oldRange && data.label !== 'Number' ) {
		// Replace the whole range
		params.delete( start + 'to' + end );
		params.delete( start );
		this.mergeContigousRanges( partialList, params, start, end, data.label, true );
	} else {
		// Delete the whole range
		params.delete( start + 'to' + end );
		params.delete( subPage );
	}
};

/**
 * Sets the numbering changes
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Parameters} params
 * @param {Object} data Refer to the description of updateCacheData()
 *
 * @return {mw.proofreadpage.PagelistInputWidget.Parameters}
 */
VisualDialogModel.prototype.setNumbering = function ( params, data ) {
	// If there is a number, and the range is in the list of builtin ranges and the range
	// changes the number format,  go ahead and set the numbering, else, delete whatever
	// is already there if there are any numberings it will always be in the data...
	//  so we don't need to worry about preserving old ones.
	if ( data.number && ( data.label === 'Number' || ( mw.config.get( 'prpPagelistBuiltinLabels' ) &&
		mw.config.get( 'prpPagelistBuiltinLabels' ).indexOf( data.label ) !== -1 && data.label !== 'empty' ) ) ) {
		params.set( this.data.subPage, data.number );
	} else {
		params.delete( this.data.subPage );
	}

	return params;
};

/**
 * Shows debug output
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} params
 */
VisualDialogModel.prototype.debugOutput = function ( params ) {
	let pagelistText = 'Wikisource Pagelist Widget visual mode debug output:\n';

	pagelistText += '-----------------------------------------';

	params.forEach( ( index, label ) => {
		pagelistText += '\n' + index + '="' + label + '"';
	} );

	pagelistText += '\n-----------------------------------------';

	// eslint-disable-next-line no-console
	console.log( pagelistText );
};

/**
 * Bring parameters that get buried by new changes up to the top.
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} params
 * @param  {number} start  Starting index of range that is suspected to have covered other parameters
 * @param  {number} end    Ending index of range that is suspected to have covered other parameters
 */
VisualDialogModel.prototype.resurfaceHiddenEntries = function ( params, start, end ) {

	params.forEach( ( index, label ) => {
		if ( index.split( /(to|To)/ )[ 0 ] > start && index.split( /(to|To)/ )[ 0 ] < end ) {
			params.delete( index );
			params.set( index, label );
		}
	} );

};

/**
 * Remove numbering changes that are inside ranges with label other than the 3 builtin
 * range types.
 *
 * @param  {mw.proofreadapage.PagelistInputWidget.Parameters} params
 * @param  {Array} partialList Refer to the description of generatePartialList()
 */
VisualDialogModel.prototype.removeUnecessaryNumbering = function ( params, partialList ) {

	params.forEach( ( index, label ) => {
		if ( index.split( 'to' )[ 0 ] === index ) {
			if ( partialList[ index ] ) {
				label = partialList[ index ][ 0 ].type;
				if ( mw.config.get( 'prpPagelistBuiltinLabels' ) &&
				mw.config.get( 'prpPagelistBuiltinLabels' ).indexOf( label ) === -1 ) {
					params.delete( index );
				}
			}
		}
	} );

};

/**
 * Optimises ranges such that
 *
 * @param  {Array} partialList Refer to the description of generatePartialList()
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} params
 * @param  {number} start       Starting index of current range
 * @param  {number} end         Ending index of current range
 * @param  {string} label       Label of current range
 * @param  {boolean} all        Whether or not to merge ranges that occur after the current one. This is especially needed
 * when we haven't really haven't figured out where this range ends and merging ranges could lead to a loss of the
 * work the user was doing
 *
 * @return {mw.proofreadpage.PagelistInputWidget.Parameters}
 */
VisualDialogModel.prototype.mergeContigousRanges = function ( partialList, params, start, end, label, all ) {
	if ( partialList[ start - 1 ] && partialList[ start - 1 ][ 0 ].type === label &&
		partialList[ end + 1 ] && partialList[ end + 1 ][ 0 ].type === label && all ) {
		// There are ranges both in front and behind the current range with the same label
		// Don't merge this if the all argument is not set
		const dupStart = partialList[ start - 1 ][ 0 ].from;
		const dupEnd = partialList[ end + 1 ][ 0 ].to;
		params.delete( partialList[ start - 1 ][ 0 ].from + 'to' + partialList[ start - 1 ][ 0 ].to );
		params.delete( partialList[ end + 1 ][ 0 ].from + 'to' + partialList[ end + 1 ][ 0 ].to );
		params.set( dupStart + 'to' + dupEnd, label );
	} else if ( partialList[ start - 1 ] && partialList[ start - 1 ][ 0 ].type === label ) {
		// Range in front of the current range have the same label
		const dupStart = partialList[ start - 1 ][ 0 ].from;
		const dupEnd = partialList[ start - 1 ][ 0 ].to;
		params.delete( dupStart + 'to' + dupEnd );
		params.set( dupStart + 'to' + end, label );
	} else if ( partialList[ end + 1 ] && partialList[ end + 1 ][ 0 ].type === label && all ) {
		// Range behind the current range have the same label
		// Don't merge this if the all argument is not set
		const dupStart = partialList[ end + 1 ][ 0 ].from;
		const dupEnd = partialList[ end + 1 ][ 0 ].to;
		params.delete( dupStart + 'to' + dupEnd );
		params.set( start + 'to' + dupEnd, label );
	} else {
		// Nothing happened, no ranges to merge
		params.set( start + 'to' + end, label );
	}
	return params;
};

/**
 * find the next change point (any point where the numbering goes into another range)
 *
 * @param  {Array} partialList
 * @param  {number} currentIndex
 * @param  {string} currentLabel
 * @return {number} index of next 'change point'
 */
VisualDialogModel.prototype.findNextChangePoint = function ( partialList, currentIndex, currentLabel ) {
	for ( let i = currentIndex + 1; i <= this.lengthOfPagelist; i++ ) {
		if ( ( currentLabel && ( typeof partialList[ i ] === 'undefined' || partialList[ i ][ 0 ].type !== currentLabel ) ) ) {
			return i - 1;
		} else if ( !currentLabel && partialList[ i ] || this.parameters.get( i ) ) {
			return i - 1;
		}
	}
	return this.lengthOfPagelist;
};

/**
 * Generate a partialList representation of the pagelist parameters.
 *
 * The partialList representation of the pagelist is basically an enumerated form of the short syntax used on wiki. It consists of
 * an array with index representing each scan. Each index is eitheir null/undefined or consists of an array containing all the
 * information about the ranges that pass over the index. This is done to allow for easy access of page numbering status while
 * performing descision on what the user want to do.
 *
 * @return {Array} The partialList
 */
VisualDialogModel.prototype.generatePartialList = function () {
	const params = this.parameters, partialList = [];
	params.forEach( ( index, label ) => {
		if ( index.split( 'to' )[ 0 ] !== index ) {
			for ( let i = parseInt( index.split( /(to|To)/ )[ 0 ] ); i <= parseInt( index.split( /(to|To)/ )[ 2 ] ); i++ ) {
				if ( !partialList[ i ] ) {
					partialList[ i ] = [];
				}

				// Add to top
				partialList[ i ].unshift( {
					from: parseInt( index.split( /(to|To)/ )[ 0 ] ),
					to: parseInt( index.split( /(to|To)/ )[ 2 ] ),
					type: label
				} );
			}
		}
	} );

	return partialList;
};

/**
 * Normalize parameters such that all Page Number Type changes will be represented as ranges.
 * This significantly simplifies trying to detect if a particular parameter is refering to
 * a numbering change
 * ex: 3=Cover --> 3to3=Cover
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} params
 * @return {mw.proofreadpage.PagelistInputWidget.Parameters} Processed parameters
 */
VisualDialogModel.prototype.expandParameters = function ( params ) {
	const newParams = new Parameters();
	params.forEach( ( index, label ) => {
		if ( index.split( /(to|To)/ )[ 0 ] === index &&
			isNaN( label ) ) {
			newParams.set( index + 'to' + index, label );
		} else {
			newParams.set( index, label );
		}
	} );

	return newParams;
};

/**
 * Update the parameter based on events from the main model
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} parameters
 */
VisualDialogModel.prototype.updateCachedDataFromMainModel = function ( parameters ) {
	this.parameters = this.expandParameters( parameters );
	this.changed = false;
	this.emit( 'updateParameters' );
};

/**
 * @param {Array} enumeratedList
 * @event pagelistGenerated
 */
VisualDialogModel.prototype.pagelistPreviewGenerationDone = function ( enumeratedList ) {
	this.lengthOfPagelist = enumeratedList.length;
	this.emit( 'pagelistPreviewGenerated' );
};

/**
 * Handle the error
 */
VisualDialogModel.prototype.handleError = function () {
	this.emit( 'previewError' );
};

/**
 * Syncs main model with this model
 */
VisualDialogModel.prototype.setCachedData = function () {
	const params = this.parameters,
		partialList = this.generatePartialList();

	this.removeUnecessaryNumbering( params, partialList );
	this.mergeContigousRangesBeforeUpdate( params, partialList );

	this.mainModel.updateParameters( params );
	this.changed = false;
};

/**
 * Optimise and merge contigous ranges before syncing with main model
 *
 * @param  {mw.proofreadapage.PagelistInputWidget.Parameters} params
 * @param  {Array} partialList
 */
VisualDialogModel.prototype.mergeContigousRangesBeforeUpdate = function ( params, partialList ) {
	// Merge parameters
	params.forEach( function ( index, label ) {
		if ( index.split( /(to|To)/ )[ 0 ] !== index ) {
			const start = parseInt( index.split( /(to|To)/ )[ 0 ] );
			const end = parseInt( index.split( /(to|To)/ )[ 2 ] );

			if ( ( start - 1 ) <= 0 || ( end + 1 ) >= ( this.lengthOfPagelist - 1 ) ) {
				return;
			}

			if ( partialList[ String( start - 1 ) ] && partialList[ String( start - 1 ) ][ 0 ].type === label ) {
				params.delete( index );
				params.delete( partialList[ start - 1 ][ 0 ].from + 'to' + partialList[ start - 1 ][ 0 ].to );
				const dupStart = partialList[ start - 1 ][ 0 ].from;
				label = partialList[ start - 1 ][ 0 ].type;

				params.set( dupStart + 'to' + end, label );
			} else if ( partialList[ String( end + 1 ) ] && partialList[ String( end + 1 ) ][ 0 ].type === params[ index ] ) {
				params.delete( index );
				params.delete( partialList[ end + 1 ][ 0 ].from + 'to' + partialList[ end + 1 ][ 0 ].to );
				const dupEnd = partialList[ end + 1 ][ 0 ].to;
				label = partialList[ end + 1 ][ 0 ].type;

				params.set( start + 'to' + dupEnd, label );
			}
		}
	} );
};

/**
 * Resets cache to state before opening the dialog
 *
 * @return {boolean} Whether there is unsaved data or not
 */
VisualDialogModel.prototype.unloadCachedData = function () {
	const test = !this.changed;
	if ( test ) {
		this.parameters = this.mainModel.getParameters();
		this.changed = false;
		this.mainModel.generateEnumeratedList( this.parameters );
		this.emit( 'updateParameters', this.parameters );
	}
	return test;
};

/**
 * Explictly sets the changed falgs
 *
 * @param {boolean} value
 */
VisualDialogModel.prototype.setChangedFlag = function ( value ) {
	if ( typeof value === 'boolean' ) {
		this.changed = value;
	}
};

/**
 * @event DialogOpened
 */
VisualDialogModel.prototype.dialogOpened = function () {
	this.emit( 'dialogOpened' );
};

module.exports = VisualDialogModel;
