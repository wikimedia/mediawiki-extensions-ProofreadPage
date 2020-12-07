var Parameters = require( './PagelistInputWidget.Parameters.js' );
/**
 * A model which keeps track of the parameters, wikitext and a list of Objects
 * containing useful information for displaying a pagelist.
 *
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
 *
 * @param {string} wikitext Updated wikitext
 */
PagelistInputWidgetModel.prototype.updateWikitext = function ( wikitext ) {
	this.wikitext = wikitext;
	this.emit( 'wikitextUpdated', wikitext );
	this.generateParametersFromWikitext();
};

/**
 * Generates parameters from raw wikitext
 *
 * @param {string} wikitext
 * @event parsingerror Error in parsing pagelist
 */
PagelistInputWidgetModel.prototype.generateParametersFromWikitext = function ( wikitext ) {
	// https://regex101.com/r/rWhPy6/10
	// Try a naive way of extracting one pagelist tag and then put whatever we got
	// into a XML parser which should sort out the parameters stuff for us
	var pagelistRegex = /<pagelist[^<]*?\/>/gmi,
		pagelistText,
		tagEndMatches,
		parsedPagelist,
		attrs,
		parameters = new Parameters(),
		i;

	wikitext = wikitext || this.wikitext;

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
				pagelistText = pagelistText.substring( 0, tagEndMatches.index ) +
					' ' + pagelistText.substring( tagEndMatches.index );
			}

			parsedPagelist = ( new DOMParser() )
				.parseFromString( pagelistText, 'text/html' );

			if ( !parsedPagelist.body.childNodes[ 0 ].hasAttributes() ) {
				this.parameters = new Parameters();
				this.emit( 'parametersUpdated', parameters );
				this.generateEnumeratedList();
				return;
			}

			attrs = parsedPagelist.body.childNodes[ 0 ].attributes;
			for ( i = 0; i < attrs.length; i++ ) {
				parameters.set( attrs[ i ].name, attrs[ i ].value );
			}

			this.moreThanOne = false;
			if ( !arguments.length ) {
				this.parameters = parameters;
				this.emit( 'parametersUpdated', parameters );
				this.generateEnumeratedList();
			} else {
				this.generateEnumeratedList( parameters );
			}
		}
	} else {
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-pagelistnotdetected' );
	}
};

/**
 * Gets wikitext
 *
 * @return {string} wikitext
 */
PagelistInputWidgetModel.prototype.getWikitext = function () {
	return this.wikitext;
};

/**
 * Gets parameters
 *
 * @return {Object} parameters
 */
PagelistInputWidgetModel.prototype.getParameters = function () {
	return this.parameters;
};

/**
 * Updates the parameters
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} parameters
 */
PagelistInputWidgetModel.prototype.updateParameters = function ( parameters ) {
	this.parameters = parameters;
	this.generateEnumeratedList( parameters );
	this.generateWikitext( parameters );
};

/**
 * Generates the wikitext from parameters
 *
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} parameters
 */
PagelistInputWidgetModel.prototype.generateWikitext = function ( parameters ) {
	// refer to generateParametersFromWikitext() function
	var pagelistRegex = /<pagelist[^<]*?\/>/gmi,
		linesRegex = /([\n\r][^=<|>]*=)/g,
		seperator = ' ',
		pagelistText = '<pagelist';

	if ( ( this.wikitext.match( linesRegex ) || [] ).length > 0 ) {
		seperator = '\n';
	}

	parameters.forEach( function ( index, label ) {
		if ( index.split( /(to|To)/ )[ 0 ] !== index &&
			index.split( /(to|To)/ )[ 0 ] === index.split( /(to|To)/ )[ 2 ] ) {
			pagelistText += seperator + index.split( /(to|To)/ )[ 0 ] + '="' + label + '"';
		} else {
			pagelistText += seperator + index + '="' + label + '"';
		}
	} );

	pagelistText += ' />';
	this.wikitext = this.wikitext.replace( pagelistRegex, pagelistText );
	this.emit( 'wikitextUpdated', this.wikitext );
};

/**
 * Generates list of Objects containing information usable for rendering the pagelist from
 * the current parameters based on responses from API
 *
 * @param {mw.proofreadpage.PagelistInputWidget.Parameters} parameters
 * @event parsingerror Error in parsing pagelist
 * @event enumeratedListCreated List of pages was created
 */
PagelistInputWidgetModel.prototype.generateEnumeratedList = function ( parameters ) {
	// Create a template (per T252706) and then pass it to the parsing api
	// parse the resulting output and create a array of associative arrays each containing
	// info about a particular page number
	var apiWikitext = '{{MediaWiki:Proofreadpage index template|' + this.templateParameter + '=$2}}',
		pagelistText = '<pagelist ';

	this.emit( 'enumeratedListGenerationStarted' );

	parameters = parameters || this.parameters;

	if ( this.moreThanOne ) {
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-morethanone' );
		return;
	}

	parameters.forEach( function ( index, label ) {
		pagelistText += index + '="' + label + '" ';
	} );

	pagelistText += ' />';

	apiWikitext = apiWikitext.replace( '$2', pagelistText );

	this.api.post( {
		action: 'parse',
		title: mw.config.get( 'wgPageName' ),
		text: apiWikitext
	} ).done( function ( response ) {
		this.parseAPItoEnumeratedList( response, parameters );
	}.bind( this ) ).catch( function ( err ) {
		mw.log.error( err );
		this.emit( 'parsingerror', 'proofreadpage-pagelist-parsing-error-unknown' );
	}.bind( this ) );
};

/**
 * Parses Api response to enumerated list
 *
 * @param  {Object} response API response
 * @param  {mw.proofreadpage.PagelistInputWidget.Parameters} parameters
 */
PagelistInputWidgetModel.prototype.parseAPItoEnumeratedList = function ( response, parameters ) {
	var parsedText = document.createElement( 'body' ),
		parsedPagelist,
		enumeratedList = [],
		i, ranges = [];

	parameters = parameters || this.parameters;
	parsedText.innerHTML = response.parse.text[ '*' ];

	// extract all the ranges being set by the pagelist
	parameters.forEach( function ( index, label ) {
		if ( index.split( /(to|To)/ )[ 0 ] !== index ) {
			for ( i = parseInt( index.split( 'to' )[ 0 ] ); i <= parseInt( index.split( 'to' )[ 1 ] ); i++ ) {
				ranges[ i ] = label;
			}
		} else if ( isNaN( parseInt( label ) ) ) {
			ranges[ index ] = label;
		}
	} );

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

		enumeratedList.push( {
			subPage: ( i + 1 ),
			text: parsedPagelist[ i ].innerHTML,
			type: ranges[ ( i + 1 ) ] || 'Number',
			assignedPageNumber: parseInt( parameters.get( i + 1 ) ) || null
		} );
	}

	this.enumeratedList = enumeratedList;
	this.emit( 'enumeratedListCreated', enumeratedList );
};

module.exports = PagelistInputWidgetModel;
