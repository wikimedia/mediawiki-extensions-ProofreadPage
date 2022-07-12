/**
 * PrevTool implements the button used to go to the previous page in edit-in-sequence
 *
 * @class
 */
function PrevTool() {
	PrevTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-prev' );
	this.toolbar.pagelistModel.on( 'firstReached', this.onFirstReached.bind( this ) );
	this.toolbar.pagelistModel.on( 'pageUpdated', this.onPageUpdated.bind( this ) );
	this.toolbar.pagelistModel.on( 'pageListModelReady', this.onPageListModelReady.bind( this ) );
	this.setDisabled( true );
	this.isAtFirst = false;
}

OO.inheritClass( PrevTool, OO.ui.Tool );

PrevTool.static.name = 'prev';
PrevTool.static.icon = 'previous';

/**
 * @inheritdoc
 */
PrevTool.prototype.onSelect = function () {
	this.setActive( false );
	this.setDisabled( true );
	this.toolbar.pagelistModel.prev();
};

/**
 * Event handler for pageListModelReady
 *
 * @see PagelistModel.js
 */
PrevTool.prototype.onPageListModelReady = function () {
	if ( !this.isAtFirst ) {
		this.setDisabled( false );
	}
};

/**
 * Event handler for onFirstReached
 *
 * @see PagelistModel.js
 */
PrevTool.prototype.onFirstReached = function () {
	this.isAtFirst = true;
	this.setDisabled( true );
};

/**
 * Event handler for onPageUpdated
 *
 * @see PagelistModel.js
 */
PrevTool.prototype.onPageUpdated = function () {
	this.isAtFirst = false;
	this.setDisabled( false );
};

/**
 * @inheritdoc
 */
PrevTool.prototype.onUpdateState = function () {};

/**
 * NextTool implements the button used to go to the next page in edit-in-sequence
 *
 * @class
 */
function NextTool() {
	NextTool.super.apply( this, arguments );
	this.$element.addClass( 'prp-editinsequence-next' );
	this.toolbar.pagelistModel.on( 'lastReached', this.onLastReached.bind( this ) );
	this.toolbar.pagelistModel.on( 'pageUpdated', this.onPageUpdated.bind( this ) );
	this.toolbar.pagelistModel.on( 'pageListModelReady', this.onPageListModeReady.bind( this ) );
	this.setDisabled( true );
	this.isAtLast = false;
}

OO.inheritClass( NextTool, OO.ui.Tool );

NextTool.static.name = 'next';
NextTool.static.icon = 'next';

/**
 * @inheritdoc
 */
NextTool.prototype.onSelect = function () {
	this.setActive( false );
	this.setDisabled( true );
	this.toolbar.pagelistModel.next();
};

/**
 * Event handler for pageListModelReady
 *
 * @see PagelistModel.js
 */
NextTool.prototype.onPageListModeReady = function () {
	if ( !this.isAtLast ) {
		this.setDisabled( false );
	}
};

/**
 * Event handler for onLastReached
 *
 * @see PagelistModel.js
 */
NextTool.prototype.onLastReached = function () {
	this.isAtLast = true;
	this.setDisabled( true );
};

/**
 * Event handler for onLastReached
 *
 * @see PagelistModel.js
 */
NextTool.prototype.onPageUpdated = function () {
	this.isAtLast = false;
	this.setDisabled( false );
};

/**
 * @inheritdoc
 */
NextTool.prototype.onUpdateState = function () {};

module.exports = {
	PrevTool: PrevTool,
	NextTool: NextTool
};
