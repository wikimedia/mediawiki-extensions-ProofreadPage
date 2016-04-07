/**
 * ContentEditable MediaWiki pages node.
 *
 * @class
 * @extends ve.ce.MWBlockExtensionNode
 *
 * @constructor
 */
ve.ce.MWPagesNode = function VeCeMWPagesNode() {
	ve.ce.MWPagesNode.super.apply( this, arguments );
};

/* Inheritance */
OO.inheritClass( ve.ce.MWPagesNode, ve.ce.MWBlockExtensionNode );

/* Static Properties */
ve.ce.MWPagesNode.static.name = 'mwPages';

ve.ce.MWPagesNode.static.tagName = 'div';

ve.ce.MWPagesNode.static.primaryCommandName = 'pages';

ve.ce.MWPagesNode.static.iconWhenInvisible = 'articles';

ve.ce.MWPagesNode.static.rendersEmpty = true;

/* Methods */
/**
 * @inheritdoc
 */
ve.ce.MWPagesNode.static.getDescription = function ( model ) {
	var indexTitle = new mw.Title( model.getIndexName() );
	return ve.msg( 'proofreadpage-visualeditor-node-pages-inspector-description', indexTitle.getNameText() );
};

/* Registration */
ve.ce.nodeFactory.register( ve.ce.MWPagesNode );
