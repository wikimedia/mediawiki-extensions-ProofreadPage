/*!
 * VisualEditor ContentEditable MWPagesSectionNode class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see http://ve.mit-license.org
 */

/**
 * ContentEditable section node.
 *
 * @class
 * @extends ve.ce.SectionNode
 * @constructor
 * @param {ve.dm.MWPagesSectionNode} model Model to observe
 * @param {Object} [config] Configuration options
 */
ve.ce.MWPagesSectionNode = function VeCeMwPagesSectionNode() {
	var messages = {
		header: 'proofreadpage_header',
		footer: 'proofreadpage_footer',
		section: 'proofreadpage_body'
	};
	// Parent constructor
	ve.ce.MWPagesSectionNode.super.apply( this, arguments );

	this.$element.attr( 'data-title',
		ve.msg( messages[ this.model.getAttribute( 'style' ) ] )
	);
};

/* Inheritance */

OO.inheritClass( ve.ce.MWPagesSectionNode, ve.ce.SectionNode );

/* Registration */

ve.ce.nodeFactory.register( ve.ce.MWPagesSectionNode );
