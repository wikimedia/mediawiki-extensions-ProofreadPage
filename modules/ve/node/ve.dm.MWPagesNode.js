/**
 * DataModel MediaWiki pages node.
 *
 * @class
 * @extends ve.dm.MWBlockExtensionNode
 *
 * @constructor
 */
ve.dm.MWPagesNode = function VeDmMWPagesNode() {
	ve.dm.MWPagesNode.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.dm.MWPagesNode, ve.dm.MWBlockExtensionNode );

/* Static Properties */
ve.dm.MWPagesNode.static.name = 'mwPages';

ve.dm.MWPagesNode.static.tagName = 'div';

ve.dm.MWPagesNode.static.extensionName = 'pages';

/* Methods */
/**
 * @return {string} Index name
 */
ve.dm.MWPagesNode.prototype.getIndexName = function () {
	return this.getAttribute( 'mw' ).attrs.index || '';
};

/* Registration */
ve.dm.modelRegistry.register( ve.dm.MWPagesNode );
