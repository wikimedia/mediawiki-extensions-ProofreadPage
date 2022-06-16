function Toolbar( config ) {
	config = config || {};
	Toolbar.super.call( this, config );
}

OO.inheritClass( Toolbar, OO.ui.Toolbar );

module.exports = Toolbar;
