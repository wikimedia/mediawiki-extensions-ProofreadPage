function SaveOptionsModel() {
	OO.EventEmitter.apply( this, null );
	this.editSummary = '';
	this.isMinorEdit = false;
	this.shouldWatchlist = false;
	this.afterSaveAction = 'do-nothing';
	this.showDialogBeforeEverySave = true;
	this.isInitialized = true;
}

OO.mixinClass( SaveOptionsModel, OO.EventEmitter );

SaveOptionsModel.prototype.setEditSummary = function ( editSummary ) {
	this.editSummary = editSummary;
};

SaveOptionsModel.prototype.setMinorEdit = function ( isMinorEdit ) {
	this.isMinorEdit = isMinorEdit;
};

SaveOptionsModel.prototype.setShouldWatchlist = function ( shouldWatchlist ) {
	this.shouldWatchlist = shouldWatchlist;
};

SaveOptionsModel.prototype.setAfterSaveAction = function ( afterSaveAction ) {
	this.afterSaveAction = afterSaveAction;
};

SaveOptionsModel.prototype.setDialogBeforeSave = function ( dialogBeforeSave ) {
	this.emit( 'dialogBeforeSaveChange', this.showDialogBeforeEverySave );
	this.showDialogBeforeEverySave = dialogBeforeSave;
};

SaveOptionsModel.prototype.setIntialized = function () {
	this.isInitialized = true;
};

SaveOptionsModel.prototype.getSaveData = function () {
	return {
		editSummary: this.editSummary,
		isMinorEdit: this.isMinorEdit,
		shouldWatchlist: this.shouldWatchlist
	};
};

SaveOptionsModel.prototype.getAfterSaveAction = function () {
	return this.afterSaveAction;
};

SaveOptionsModel.prototype.getDialogBeforeSave = function () {
	return this.showDialogBeforeEverySave;
};

SaveOptionsModel.prototype.getIsInitialized = function () {
	return this.isInitialized;
};

SaveOptionsModel.prototype.clone = function () {
	var cloneObject = new SaveOptionsModel();
	cloneObject.isMinorEdit = this.isMinorEdit;
	cloneObject.isInitialized = this.isInitialized;
	cloneObject.editSummary = this.editSummary;
	cloneObject.shouldWatchlist = this.shouldWatchlist;
	cloneObject.afterSaveAction = this.afterSaveAction;
	cloneObject.showDialogBeforeEverySave = this.showDialogBeforeEverySave;
	return cloneObject;
};

SaveOptionsModel.prototype.merge = function ( obj ) {
	this.isMinorEdit = obj.isMinorEdit;
	this.isInitialized = obj.isInitialized;
	this.editSummary = obj.editSummary;
	this.shouldWatchlist = obj.shouldWatchlist;
	this.afterSaveAction = obj.afterSaveAction;
	if ( obj.showDialogBeforeEverySave !== this.showDialogBeforeEverySave ) {
		this.emit( 'dialogBeforeSaveChange', obj.showDialogBeforeEverySave );
	}
	this.showDialogBeforeEverySave = obj.showDialogBeforeEverySave;
};

module.exports = SaveOptionsModel;
