/**
 * Custom datastructure to store the pagelist parameters
 * without any loss in ordering of the pagelist. This is
 * required 'cause the generic Object '{}' is unable to
 * retain it's ordering for both string and number at
 * the same time. The solution seems to be to append
 * a random string of characters (defined in this.secret)
 * to each and every index to convert them all to strings.
 *
 * @class
 */
function Parameters() {
	this.data = {};
	this.size = 0;
	this.secret = '_d';
}

/**
 * Stores data at index
 *
 * @param {string} index index to store the data at
 * @param {string|number} data
 */
Parameters.prototype.set = function ( index, data ) {
	this.data[ this.secret + String( index ) ] = data;
	this.size++;
};

/**
 * Deletes data at index
 *
 * @param {string} index index to be deleted
 */
Parameters.prototype.delete = function ( index ) {
	delete this.data[ this.secret + String( index ) ];
	this.size--;
};

/**
 * Retrieves data at index
 *
 * @param  {string} index index from which data needs to be retrieved
 * @return {string|number} data
 */
Parameters.prototype.get = function ( index ) {
	return this.data[ this.secret + String( index ) ];
};

/**
 * Checks if data exists at that index
 *
 * @param  {string}  index
 * @return {boolean}
 */
Parameters.prototype.has = function ( index ) {
	return !!this.data[ this.secret + String( index ) ];
};

/**
 * Returns whether the parameters list is empty
 *
 * @return {boolean}
 */
Parameters.prototype.isEmpty = function () {
	return !!this.size;
};

/**
 * Iterates over the object, while excuting callback
 *
 * @param  {Function} callback callback to be excuted
 */
Parameters.prototype.forEach = function ( callback ) {
	let index, label;
	const data = this.data;
	for ( index in data ) {
		index = index.replace( this.secret, '' );
		label = this.get( index );
		callback( index, label );
	}
};

module.exports = Parameters;
