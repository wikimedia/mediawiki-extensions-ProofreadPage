/**
 * Pages tag inspector.
 *
 * @class
 * @extends ve.ui.MWExtensionInspector
 *
 * @constructor
 */
ve.ui.MWPagesInspector = function VeUiMWPagesInspector() {
	ve.ui.MWPagesInspector.super.apply( this, arguments );
};

/* Inheritance */

OO.inheritClass( ve.ui.MWPagesInspector, ve.ui.MWExtensionInspector );

/* Static properties */

ve.ui.MWPagesInspector.static.name = 'pages';

ve.ui.MWPagesInspector.static.title = OO.ui.deferMsg( 'proofreadpage-visualeditor-node-pages-inspector-title' );

ve.ui.MWPagesInspector.static.modelClasses = [ ve.dm.MWPagesNode ];

ve.ui.MWPagesInspector.static.allowedEmpty = true;

ve.ui.MWPagesInspector.static.delayForIndexInput = 2000;

/* Methods */

/**
 * @inheritdoc
 */
ve.ui.MWPagesInspector.prototype.initialize = function () {
	// Parent method
	ve.ui.MWPagesInspector.super.prototype.initialize.apply( this, arguments );

	this.attributeInputs = {};
	this.$attributes = $( '<div>' );
	this.form.$element.append( this.$attributes );
};

/**
 * @inheritdoc
 */
ve.ui.MWPagesInspector.prototype.getSetupProcess = function ( data ) {
	// Parent method
	return ve.ui.MWPagesInspector.super.prototype.getSetupProcess.call( this, data )
		.next( function () {
			this.mwData = ( this.selectedNode !== null && this.selectedNode.getAttribute( 'mw' ) ) || {
				attrs: {},
				body: { extsrc: '' }
			};

			this.setupForm();

			// Hide the tag content if possible
			if ( this.input.getValue().trim() === '' ) {
				this.input.toggle( false );
			}
		}, this );
};

/**
 * Creates the inspector form using the current data stored in this.mwData
 */
ve.ui.MWPagesInspector.prototype.setupForm = function () {
	var key,
		attributes = this.mwData.attrs,
		inspector = this;

	this.pushPending();
	this.getFileInfo( attributes.index ).done( function ( imageInfo ) {
		inspector.addAttributeWidgetToForm( inspector.createIndexWidget(), 'index' );

		inspector.addAttributeWidgetToForm( new OO.ui.DropdownInputWidget( {
			options: inspector.buildHeaderFieldSelectorOptions( attributes.header )
		} ), 'header' );

		if ( imageInfo.pagecount !== undefined ) {
			inspector.addAttributeWidgetToForm( new OO.ui.NumberInputWidget( {
				isInteger: true,
				min: 1,
				max: imageInfo.pagecount
			} ), 'from' );
			inspector.addAttributeWidgetToForm( new OO.ui.NumberInputWidget( {
				isInteger: true,
				min: 1,
				max: imageInfo.pagecount
			} ), 'to' );
		} else {
			inspector.addAttributeWidgetToForm( new mw.widgets.TitleInputWidget( {
				namespace: inspector.getIdForNamespace( 'page' )
			} ), 'from' );
			inspector.addAttributeWidgetToForm( new mw.widgets.TitleInputWidget( {
				namespace: inspector.getIdForNamespace( 'page' )
			} ), 'to' );
		}

		inspector.addAttributeWidgetToForm( new OO.ui.TextInputWidget(), 'fromsection' );
		inspector.addAttributeWidgetToForm( new OO.ui.TextInputWidget(), 'tosection' );

		for ( key in attributes ) {
			if ( key in inspector.attributeInputs ) {
				inspector.attributeInputs[ key ].setValue( attributes[ key ] );
			} else {
				inspector.addAttributeWidgetToForm( new OO.ui.TextInputWidget( {
					value: attributes[ key ]
				} ), key );
			}
		}

		inspector.updateSize();
		inspector.popPending();
	} );
};

/**
 * @return {mw.widgets.TitleInputWidget}
 */
ve.ui.MWPagesInspector.prototype.createIndexWidget = function () {
	return new mw.widgets.TitleInputWidget( {
		namespace: this.getIdForNamespace( 'index' ),
		required: true
	} ).connect( this, {
		change: OO.ui.debounce( this.onIndexChange.bind( this ), this.constructor.static.delayForIndexInput )
	} );
};

/**
 * @param {string} namespaceName the name of the namespace like "Index" or "Page"
 * @return {int} the namespace id like 252
 */
ve.ui.MWPagesInspector.prototype.getIdForNamespace = function ( namespaceName ) {
	var namespaceIds = mw.config.get( 'wgNamespaceIds' );

	if ( namespaceName in namespaceIds ) {
		return namespaceIds[ namespaceName ];
	} else {
		throw new Error( 'Unknown id for the ' + namespaceName + ': namespace' );
	}
};

/**
 * Builds the options for the header field dropdown
 *
 * @param {string|undefined} headerValue the current value of the header field
 * @return {Object[]}
 */
ve.ui.MWPagesInspector.prototype.buildHeaderFieldSelectorOptions = function ( headerValue ) {
	var headerInputOptions = [
		{ data: '', label: OO.ui.msg( 'proofreadpage-visualeditor-node-pages-inspector-indexselector-no' ) },
		{ data: '1', label: OO.ui.msg( 'proofreadpage-visualeditor-node-pages-inspector-indexselector-yes' ) }
	];
	if ( [ '', '1' ].indexOf( headerValue ) === -1 ) {
		headerInputOptions.push( { data: headerValue, label: headerValue } );
	}
	return headerInputOptions;
};

/**
 * Get info about the file
 *
 * @param {string} fileName like "test.djvu"
 * @return {jQuery.Promise} information about the file like {size: 222, pagecount: 1}. Always resolves
 */
ve.ui.MWPagesInspector.prototype.getFileInfo = function ( fileName ) {
	if ( fileName !== '' ) {
		return ( new mw.Api() ).get( {
			formatversion: 2,
			action: 'query',
			prop: 'imageinfo',
			titles: 'File:' + fileName,
			iiprop: 'size'
		} ).then( function ( data ) {
			var file = data.query.pages[ 0 ];
			return file.imageinfo && file.imageinfo[ 0 ] || {};
		}, function () {
			return $.Deferred().resolve( {} );
		} );
	} else {
		return $.Deferred().resolve( {} );
	}
};

/**
 * Adds to the inspector an input for a <pages> tag parameter
 *
 * @param {OO.ui.Widget} attributeInput
 * @param {string} attributeKey the key of the attribute like "from"
 */
ve.ui.MWPagesInspector.prototype.addAttributeWidgetToForm = function ( attributeInput, attributeKey ) {
	var field = new OO.ui.FieldLayout(
		attributeInput,
		{
			align: 'left',
			label: attributeKey
		}
	);
	this.$attributes.append( field.$element );
	this.attributeInputs[ attributeKey ] = attributeInput;
	attributeInput.connect( this, { change: 'onChangeHandler' } );
};

/**
 * Event callback when the index field changes
 */
ve.ui.MWPagesInspector.prototype.onIndexChange = function () {
	if ( this.attributeInputs.index && this.attributeInputs.index.getValue() !== this.mwData.attrs.index ) {
		this.updateMwData( this.mwData );
		this.teardownForm();
		this.setupForm();
		this.onChangeHandler();
	}
};

/**
 * Removes the inspector form
 */
ve.ui.MWPagesInspector.prototype.teardownForm = function () {
	this.$attributes.empty();
	this.attributeInputs = {};
};

/**
 * @inheritdoc
 */
ve.ui.MWPagesInspector.prototype.getTeardownProcess = function ( data ) {
	// Parent method
	return ve.ui.MWPagesInspector.super.prototype.getTeardownProcess.call( this, data )
		.next( function () {
			this.teardownForm();
		}, this );
};

/**
 * @inheritdoc
 */
ve.ui.MWPagesInspector.prototype.updateMwData = function ( mwData ) {
	var key;

	// Parent method
	ve.ui.MWPagesInspector.super.prototype.updateMwData.call( this, mwData );

	mwData.attrs = mwData.attrs || {};
	for ( key in this.attributeInputs ) {
		mwData.attrs[ key ] = this.attributeInputs[ key ].getValue();
	}
};

/* Registration */

ve.ui.windowFactory.register( ve.ui.MWPagesInspector );
