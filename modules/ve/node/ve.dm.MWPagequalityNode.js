/**
 * DataModel MediaWiki pagequality node.
 *
 * @class
 * @extends ve.dm.MWBlockExtensionNode
 *
 * @constructor
 */
ve.dm.MWPagequalityNode = function VeDmMWPagequalityNode() {
	ve.dm.MWPagequalityNode.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.dm.MWPagequalityNode, ve.dm.MWBlockExtensionNode );

/* Static Properties */
ve.dm.MWPagequalityNode.static.name = 'mwPagequality';

ve.dm.MWPagequalityNode.static.tagName = 'div';

ve.dm.MWPagequalityNode.static.extensionName = 'pagequality';

ve.dm.MWPagequalityNode.static.isDeletable = false;

/* Methods */
/**
 * @inheritdoc
 */
ve.dm.MWPagequalityNode.prototype.canHaveSlugBefore = function () {
	return false;
};

/**
 * @inheritdoc
 */
ve.dm.MWPagequalityNode.prototype.isEditable = function () {
	// TODO: check editing right with mw.user.getRights() ?
	return !mw.user.isAnon();
};

/* Registration */
ve.dm.modelRegistry.register( ve.dm.MWPagequalityNode );
