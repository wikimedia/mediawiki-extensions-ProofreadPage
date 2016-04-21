/*!
 * VisualEditor DataModel MWPagesSectionNode class.
 *
 * @copyright 2011-2016 VisualEditor Team and others; see http://ve.mit-license.org
 */

/**
 * DataModel section node.
 *
 * @class
 * @extends ve.dm.SectionNode
 *
 * @constructor
 * @param {Object} [element] Reference to element in linear model
 * @param {ve.dm.Node[]} [children]
 */
ve.dm.MWPagesSectionNode = function VeDmMwPagesSectionNode() {
	// Parent constructor
	ve.dm.MWPagesSectionNode.super.apply( this, arguments );
};

/* Inheritance */

OO.inheritClass( ve.dm.MWPagesSectionNode, ve.dm.SectionNode );

/* Registration */

ve.dm.modelRegistry.register( ve.dm.MWPagesSectionNode );
