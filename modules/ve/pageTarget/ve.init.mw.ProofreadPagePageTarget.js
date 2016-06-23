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
		var surfaceModel = surface.getModel(),
			documentModel = surfaceModel.getDocument(),
			sectionOffset = documentModel.getDocumentNode().children[ 0 ].children[ 1 ].getRange().start,
			contentOffset = documentModel.data.getNearestContentOffset( sectionOffset, 1 );

		if ( contentOffset !== -1 ) {
			// Found a content offset
			surfaceModel.setLinearSelection( new ve.Range( contentOffset ) );
		}
	} );

	// Parent method
	ve.init.mw.ProofreadPagePageTarget.super.prototype.surfaceReady.apply( this, arguments );
};

/**
 * @inheritdoc
 */
ve.init.mw.ProofreadPagePageTarget.prototype.getHtml = function ( newDoc, oldDoc ) {
	var sectionNode,
		articleNode = newDoc.body.children[ 0 ];

	// Unwrap section and article tags, and check it hasn't already been unwrapped.
	if ( articleNode && articleNode.tagName.toLowerCase() === 'article' ) {
		while ( ( sectionNode = articleNode.firstChild ) ) {
			while ( sectionNode.firstChild ) {
				newDoc.body.insertBefore( sectionNode.firstChild, articleNode );
			}
			articleNode.removeChild( sectionNode );
		}

		newDoc.body.removeChild( articleNode );
	}

	// Parent method
	return ve.init.mw.ProofreadPagePageTarget.super.prototype.getHtml.call( this, newDoc, oldDoc );
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

	function bubbleUp( node ) {
		var anchor;
		while ( node.parentNode !== doc.body ) {
			anchor = node.parentNode.nextSibling;
			// Reparent the siblings after node up one level
			while ( node.nextSibling ) {
				node.parentNode.parentNode.insertBefore( node.nextSibling, anchor );
			}
			// Reparent node itself
			node.parentNode.parentNode.insertBefore( node, node.parentNode.nextSibling );
		}
	}

	if ( endHeader ) {
		bubbleUp( endHeader );
	}
	if ( endBody ) {
		bubbleUp( endBody );
	}

	for ( i = 0; i < sectionNodes.length; i++ ) {
		articleNode.appendChild( sectionNodes[ i ] );
	}

	sectionIndex = 0;
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
