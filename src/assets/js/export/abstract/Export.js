/**
 * Abstract Class Export.
 *
 * @class Export
 */
export default class Export {

	// #data, #options;

	/**
	 * Constructor.
	 *
	 * Options: {file_name, data_by_sheet}
	 * 
	 * @return object
	 */

	constructor(data, options = {}) {
		if (this.constructor === Export) {
			throw new Error("Abstract class 'Export' cannot be instantiated directly.");
		}
		this.data = data;
		this.options = options;
	}

	prepareData() {
		throw new Error("Method 'prepareData()' must be implemented.");
	}

	generateFile() {
		throw new Error("Method 'download()' must be implemented.");
	}

	export() {
		const prepared_data = this.prepareData();
		this.generateFile(this.options.file_name, prepared_data);
	}
}