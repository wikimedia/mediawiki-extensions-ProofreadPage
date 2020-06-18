/**
 * A model which keeps track of the parameters, wikitext and a list of Objects
 * containing useful information for displaying a pagelist.
 * @param {string} templateParameter template parameter to be used for api call
 * @param {string} wikitext          wikitext
 * @class
 */
function PagelistInputWidgetModel( templateParameter, wikitext ) {
	OO.EventEmitter.call( this );

	this.wikitext = wikitext;
	this.api = new mw.Api();
	this.templateParameter = templateParameter;
	// initializing with default values
	this.moreThanOne = false;
	this.parameters = null;
	this.enumeratedList = null;
}

OO.mixinClass( PagelistInputWidgetModel, OO.EventEmitter );

/**
 * Updates the wikitext parameter. Also updates the parameters and enumeratedLists
 * @param  {string} wikitext Updated wikitext
 */
PagelistInputWidgetModel.prototype.updateWikitext = function ( wikitext ) {
	this.wikitext = wikitext;
	this.generateParametersFromWikitext();
};

/**
 * Generates parameters from raw wikitext
 * @event parsingerror Error in parsing pagelist
 */
PagelistInputWidgetModel.prototype.generateParametersFromWikitext = function () {
	// https://regex101.com/r/rWhPy6/10
	// Try a naive way of extracting one pagelist tag and then put whatever we got
	// into a XML parser which should sort the parameters stuff for us
	var pagelistRegex = /<pagelist[^<]*?\/>/gmi,
		pagelistText,
		tagEndMatches,
		wikitext = this.wikitext,
		parsedPagelist,
		attrs,
		parameters = {},
		i;

	if ( pagelistRegex.test( wikitext ) ) {
		if ( wikitext.match( pagelistRegex ).length > 1 ) {
			this.moreThanOne = true;
			this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-morethanone' );
			return;
		} else {
			pagelistText = pagelistRegex.exec( wikitext )[ 0 ];

			tagEndMatches = pagelistText.match( '/>' );

			// hack to deal with <pagelist 1=2/> type stuff which
			// makes the first element render as 2/ when using DOMParser.
			if ( tagEndMatches.length ) {
				pagelistText = pagelistText.substring( 0, tagEndMatches.index ) + ' ' + pagelistText.substring( tagEndMatches.index );
			}

			parsedPagelist = ( new DOMParser() )
				.parseFromString( pagelistText, 'text/html' );

			if ( !parsedPagelist.body.childNodes[ 0 ].hasAttributes() ) {
				this.parameters = {};
				this.generateEnumeratedList();
				return;
			}

			attrs = parsedPagelist.body.childNodes[ 0 ].attributes;
			for ( i = 0; i < attrs.length; i++ ) {
				parameters[ attrs[ i ].name ] = attrs[ i ].value;
			}

			this.moreThanOne = false;
			this.parameters = parameters;
			this.generateEnumeratedList();
		}
	} else {
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-pagelistnotdetected' );
	}
};

//
PagelistInputWidgetModel.prototype.generateParametersFromEnumeratedList = function () {
	// TODO: Make this do something later
};

//
PagelistInputWidgetModel.prototype.updateEnumeratedList = function () {
	// TODO: Make this do something later
};

//
PagelistInputWidgetModel.prototype.generateWikitext = function () {
	// TODO: Make this do something later
};

/**
 * Generates list of Objects containing information usable for rendering the pagelist from
 * the current parameters based on responses from API
 * @event parsingerror Error in parsing pagelist
 * @event enumeratedListCreated List of pages was created
 */
PagelistInputWidgetModel.prototype.generateEnumeratedList = function () {
	// Create a template (per T252706) and then pass it to the parsing api
	// parse the resulting output and create a array of associative arrays each containing
	// info about a particular page number
	var apiWikitext = '{{MediaWiki:Proofreadpage index template|' + this.templateParameter + '=$2}}',
		parameters = this.parameters,
		pagelistText = '<pagelist ',
		index;

	if ( this.moreThanOne ) {
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-morethanone' );
		return;
	}

	for ( index in parameters ) {
		pagelistText += index + '="' + parameters[ index ] + '" ';
	}
	pagelistText += ' />';

	apiWikitext = apiWikitext.replace( '$2', pagelistText );

	this.api.get( {
		action: 'parse',
		title: mw.config.get( 'wgPageName' ),
		text: apiWikitext
	} ).done( this.parseAPItoEnumeratedList.bind( this ) ).catch( function ( err ) {
		mw.log.error( err );
	} );
};
/**
 * Parses Api response to enumerated list
 * @param  {Object} response API response
 */
PagelistInputWidgetModel.prototype.parseAPItoEnumeratedList = function ( response ) {
	var parsedText = document.createElement( 'body' ),
		parameters = this.parameters,
		parsedPagelist,
		enumeratedList = [],
		i, ranges = [],
		classes,
		notCreated,
		quality,
		index;
	parsedText.innerHTML = response.parse.text[ '*' ];

	// extract all the ranges being set by the pagelist
	for ( index in parameters ) {
		if ( index.split( 'to' )[ 0 ] !== index ) {
			for ( i = parseInt( index.split( 'to' )[ 0 ] ); i <= parseInt( index.split( 'to' )[ 1 ] ); i++ ) {
				ranges[ i ] = parameters[ index ];
			}
		}
	}

	try {
		parsedPagelist = parsedText.querySelector( '.prp-index-pagelist' ).children;
	} catch ( e ) {
		// output response to console for debug purposes
		mw.log.error( 'unknown parsing error', response );
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-unknown' );
		return;
	}

	if ( parsedPagelist[ 0 ].attributes.class.value === 'error' ) {
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-php',
			parsedPagelist[ 0 ].innerText );
		return;
	}

	for ( i = 0; i < parsedPagelist.length; i++ ) {
		classes = parsedPagelist[ i ].attributes.class.value.split( ' ' );

		notCreated = classes.filter( function ( data ) {
			return data === 'new';
		} );

		quality = classes.filter( function ( data ) {
			return /quality/.test( data );
		} );

		enumeratedList.push( {
			subPage: ( i + 1 ),
			notCreated: !!notCreated.length,
			// really bad hack to get page quality ( ES5 compatibility reasons )
			quality: quality.length && quality[ 0 ].split( 'prp-pagequality-' )[ 1 ] || null,
			text: parsedPagelist[ i ].innerHTML,
			type: ranges[ ( i + 1 ) ] || 'Number',
			assignedPageNumber: parameters[ ( i + 1 ) ] || null
		} );
	}

	this.enumeratedList = enumeratedList;
	this.emit( 'enumeratedListCreated', enumeratedList );
};

module.exports = PagelistInputWidgetModel;
