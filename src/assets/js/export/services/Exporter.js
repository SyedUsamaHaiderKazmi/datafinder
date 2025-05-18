/**
 * Exporter class for chunked export with support for XLSX, CSV, and XLS.
 */
/**
 * Exporter Class for frontend file generation using SheetJS CE.
 * Supports XLSX, CSV, XLS. Uses chunking for memory-efficient export.
 */
import * as XLSX from "https://cdn.sheetjs.com/xlsx-latest/package/xlsx.mjs";

export default class Exporter {
    constructor({
        filename = 'data-export.xlsx',
        sheetName = 'Sheet1',
        extension = 'xlsx', // 'xlsx', 'csv', 'xls'
        multiSheet = false,
        metadata = null
    } = {}) {
        this.filename = filename;
        this.sheetName = sheetName;
        this.extension = extension.toLowerCase();
        this.multiSheet = multiSheet;
        this.metadata = metadata;

        this.workbook = XLSX.utils.book_new();
        this.sheetCount = 1;

        // Active sheet tracking
        this.worksheet = null;
        this.sheetNameUsed = null;
        this.started = false;
    }

    /**
     * Append a chunk of data directly to the sheet.
     * Commits to a new sheet if multiSheet is true.
     * @param {Array} data - Array of JSON objects (rows).
     * @param {Object} options - isFinalChunk: boolean, newSheet: boolean.
     */
    appendData(data = [], { isFinalChunk = false } = {}) {
        if (!Array.isArray(data) || data.length === 0) return;

        if (this.multiSheet) {
            const name = `${this.sheetName}_${this.sheetCount}`;
            const worksheet = XLSX.utils.json_to_sheet(data);
            XLSX.utils.book_append_sheet(this.workbook, worksheet, name);
            this.sheetCount++;
        } else {
            // Single sheet mode: create if not started
            if (!this.started) {
                this.worksheet = XLSX.utils.json_to_sheet([]);
                this.sheetNameUsed = this.sheetName;
                XLSX.utils.book_append_sheet(this.workbook, this.worksheet, this.sheetNameUsed);
                this.started = true;
            }

            // Append new data to existing worksheet
            XLSX.utils.sheet_add_json(this.worksheet, data, { origin: -1 });
        }

        if (isFinalChunk) {
            this.downloadFile();
        }
    }

    /**
     * Finalize and download the file.
     */
    downloadFile() {
        if (this.metadata) {
            this.workbook.Props = {
                ...this.metadata,
                CreatedDate: new Date()
            };
        }

        let exportFileName = this.filename;
        if (!exportFileName.endsWith(`.${this.extension}`)) {
            exportFileName = `${this.filename.split('.')[0]}.${this.extension}`;
        }
        const writeOptions = {
            bookType: this.extension,
            type: "binary"
        }

        if (this.extension === 'csv' ) {
            writeOptions.FS = ','; // field separator
            writeOptions.RS = '\n'; // row separator
            writeOptions.compression = false;
        }

        XLSX.writeFile(this.workbook, exportFileName, writeOptions);
    }
}

