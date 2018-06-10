/*!
 * VisualEditor MediaWiki Initialization ProofreadPagePageTarget class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * ProofreadPage page target
 *
 * @class
 * @extends ve.init.mw.ve.init.mw.DesktopArticleTarget
 *
 * @constructor
 * @param {Object} config Configuration options
 */
ve.init.mw.ProofreadPagePageTarget = function VeInitMwProofreadPagePageTarget() {
	var zoomIn, zoomReset, zoomOut, $contentText, $pageContainer;

	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.apply( this, arguments );

	this.$element.addClass( 've-init-mw-proofreadPagePageTarget' );

	if ( [ 'edit', 'submit' ].indexOf( mw.config.get( 'wgAction' ) ) !== -1 ) {
		$contentText = $( '#mw-content-text' );
		$pageContainer = $contentText.find( '.prp-page-container' );
		$contentText.empty().append( $pageContainer );
	}

	this.$zoomContainer = $( '<div>' ).addClass( 've-init-mw-proofreadPagePageTarget-zoomContainer' );

	this.$zoomImage = $( '.prp-page-image img' );

	zoomOut = new OO.ui.ButtonWidget( { icon: 'zoomOut', title: ve.msg( 'proofreadpage-button-zoom-out-label' ) } )
		.on( 'click', this.$zoomImage.prpZoom.bind( this.$zoomImage, 'zoomOut' ) );
	zoomReset = new OO.ui.ButtonWidget( { label: ve.msg( 'proofreadpage-button-reset-zoom-label' ) } )
		.on( 'click', this.$zoomImage.prpZoom.bind( this.$zoomImage, 'reset' ) );
	zoomIn = new OO.ui.ButtonWidget( { icon: 'zoomIn', title: ve.msg( 'proofreadpage-button-zoom-out-label' ) } )
		.on( 'click', this.$zoomImage.prpZoom.bind( this.$zoomImage, 'zoomIn' ) );

	this.zoomButtons = new OO.ui.ButtonGroupWidget( {
		classes: [ 've-init-mw-proofreadPagePageTarget-zoom' ],
		items: [ zoomOut, zoomReset, zoomIn ]
	} );

	this.$zoomContainer.append( this.zoomButtons.$element );
};

/* Inheritance */

OO.inheritClass( ve.init.mw.ProofreadPagePageTarget, ve.init.mw.DesktopArticleTarget );

/* Static Properties */

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.static.name = 'proofread-page';

/* Methods */

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.getEditableContent = function () {
	return $( '.prp-page-content' );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.documentReady = function () {
	this.constructor.static.cleanHtml( this.doc );
	this.constructor.static.splitSections( this.doc );

	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.prototype.documentReady.apply( this, arguments );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.afterActivate = function () {
	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.prototype.afterActivate.apply( this, arguments );

	$( '.prp-page-image' )
		.removeClass( 've-init-mw-desktopArticleTarget-uneditableContent' )
		.before( this.$zoomContainer );

	// Make image zoomable
	this.$zoomImage.prpZoom();
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.cancel = function () {
	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.prototype.cancel.apply( this, arguments );

	this.$zoomContainer.detach();

	this.$zoomImage.prpZoom( 'destroy' );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.surfaceReady = function () {
	var surface = this.getSurface();

	// Move the cursor to the body section
	surface.getView().once( 'focus', function () {
		var sectionNodeOffset, contentOffset,
			surfaceModel = surface.getModel(),
			documentModel = surfaceModel.getDocument(),
			articleNode = documentModel.getDocumentNode().children[ 0 ];

		if ( articleNode instanceof ve.dm.ArticleNode ) {
			sectionNodeOffset = articleNode.children[ 1 ].getRange().start;
			contentOffset = documentModel.data.getNearestContentOffset( sectionNodeOffset, 1 );

			if ( contentOffset !== -1 ) {
				// Found a content offset
				surfaceModel.setLinearSelection( new ve.Range( contentOffset ) );
			}
		}
	} );

	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.prototype.surfaceReady.apply( this, arguments );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.getHtml = function ( newDoc, oldDoc ) {
	var i,
		wrapperNodes = newDoc.body.querySelectorAll(
			'article[data-mw-proofreadPage-wrapper], ' +
			'header[data-mw-proofreadPage-wrapper], ' +
			'section[data-mw-proofreadPage-wrapper], ' +
			'footer[data-mw-proofreadPage-wrapper]'
		);

	// Unwrap nodes.
	// There should be only one of each type, but in case the user somehow managed to duplicate it,
	// unwrap every instance (otherwise they would end up as `<article>` etc. in Parsoid HTML and then
	// in wikitext).
	for ( i = 0; i < wrapperNodes.length; i++ ) {
		while ( wrapperNodes[ i ].firstChild ) {
			wrapperNodes[ i ].parentNode.insertBefore( wrapperNodes[ i ].firstChild, wrapperNodes[ i ] );
		}
		wrapperNodes[ i ].parentNode.removeChild( wrapperNodes[ i ] );
	}

	// Parent method
	return ve.init.mw.ProofreadPagePageTarget.super.prototype.getHtml.call( this, newDoc, oldDoc );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.submit = function ( wikitext, fields ) {
	var content;
	if ( this.submitting ) {
		return false;
	}

	content = this.parseWikitext( wikitext );
	ve.extendObject( fields, {
		model: 'proofread-page',
		wpHeaderTextbox: content.header,
		wpTextbox1: content.body,
		wpFooterTextbox: content.footer,
		wpQuality: content.level.level
	} );

	return ve.init.mw.ProofreadPagePageTarget.super.prototype.submit.call( this, wikitext, fields );
};

/**
 * Parse Wikitext into the JSON serialization
 *
 * @param {string} wikitext Wikitext to parse
 * @return {Object} Parsed data, containing header, body, footer & level
 */
ve.init.mw.ProofreadPagePageTarget.prototype.parseWikitext = function ( wikitext ) {
	var structureMatchResult, headerMatchResult,
		result = {
			header: '',
			body: '',
			footer: '',
			level: {
				level: 1,
				user: null
			}
		};

	structureMatchResult = wikitext.match( /^<noinclude>([\s\S]*)\n*<\/noinclude>([\s\S]*)<noinclude>([\s\S]*)<\/noinclude>$/ );
	if ( structureMatchResult === null ) {
		result.body = wikitext;
		return result;
	}
	result.body = structureMatchResult[ 2 ];
	result.footer = structureMatchResult[ 3 ];

	headerMatchResult = structureMatchResult[ 1 ].match( /^<pagequality level="([0-4])" user="(.*)" *(\/>|> *<\/pagequality>)([\s\S]*)$/ );
	if ( headerMatchResult === null ) {
		result.header = structureMatchResult[ 1 ];
		return result;
	}
	result.level.level = parseInt( headerMatchResult[ 1 ] );
	result.level.user = headerMatchResult[ 2 ];
	result.header = headerMatchResult[ 4 ];
	return result;
};

/**
 * Removes the <div class="pagetext"> node that is still in parser cache
 * TODO: remove this function when Parsoid cache will be purged
 *
 * @param {HTMLDocument} doc Document
 */
ve.init.mw.ProofreadPagePageTarget.static.cleanHtml = function ( doc ) {
	var pagetext = doc.querySelector( 'div.pagetext' );

	if ( pagetext ) {
		while ( pagetext.childNodes.length > 0 ) {
			pagetext.parentNode.insertBefore( pagetext.firstChild, pagetext );
		}
		pagetext.parentNode.removeChild( pagetext );
	}
};

/**
 * Split a document into balanced header, body and footer sections
 *
 * @param {HTMLDocument} doc Document
 */
ve.init.mw.ProofreadPagePageTarget.static.splitSections = function ( doc ) {
	var i, sectionIndex,
		endHeader = doc.querySelector( 'body meta[typeof="mw:Includes/NoInclude/End"]' ),
		noincludes = doc.querySelectorAll( 'body meta[typeof="mw:Includes/NoInclude"]' ),
		endBody = noincludes[ noincludes.length - 1 ],
		articleNode = doc.createElement( 'article' ),
		sectionNodes = [
			doc.createElement( 'header' ),
			doc.createElement( 'section' ),
			doc.createElement( 'footer' )
		];

	// Alas, if some formatting spans the header/section or section/footer boundary,
	// we can't wrap other stuff around it.
	if ( endHeader && endHeader.parentNode !== doc.body ) {
		return;
	}
	if ( endBody && endBody.parentNode !== doc.body ) {
		return;
	}

	articleNode.setAttribute( 'data-mw-proofreadPage-wrapper', '' );
	for ( i = 0; i < sectionNodes.length; i++ ) {
		sectionNodes[ i ].setAttribute( 'data-mw-proofreadPage-wrapper', '' );
		articleNode.appendChild( sectionNodes[ i ] );
	}

	sectionIndex = endHeader ? 0 : 1;
	while ( doc.body.firstChild ) {
		sectionNodes[ sectionIndex ].appendChild( doc.body.firstChild );
		if ( sectionNodes[ sectionIndex ].lastChild === endHeader ) {
			sectionIndex = 1;
		} else if ( sectionNodes[ sectionIndex ].lastChild === endBody ) {
			sectionIndex = 2;
		}
	}

	doc.body.appendChild( articleNode );
};

/* Registration */

ve.init.mw.targetFactory.register( ve.init.mw.ProofreadPagePageTarget );
