/**
 * ContentEditable MediaWiki pagequality node.
 *
 * @class
 * @extends ve.ce.MWBlockExtensionNode
 *
 * @constructor
 */
ve.ce.MWPagequalityNode = function VeCeMWPagequalityNode() {
	ve.ce.MWPagequalityNode.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.ce.MWPagequalityNode, ve.ce.MWBlockExtensionNode );

/* Static Properties */
ve.ce.MWPagequalityNode.static.name = 'mwPagequality';

ve.ce.MWPagequalityNode.static.tagName = 'div';

ve.ce.MWPagequalityNode.static.primaryCommandName = 'pagequality';

ve.ce.MWPagequalityNode.static.rendersEmpty = true;

/* Registration */
ve.ce.nodeFactory.register( ve.ce.MWPagequalityNode );
