/**
 * DataFinder Export Manager
 * 
 * Responsible for:
 * - Managing export process (chunked and full)
 * - Coordinating with Exporter service
 * - Handling export progress and errors
 * 
 * Single Responsibility: Only handles export-related operations
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

import DFConstants from './Constants.js';
import DOMUtils from '../utils/DOMUtils.js';

export default class ExportManager {

    /**
     * Create an ExportManager instance
     * @param {HTMLElement} container - The scoped container element
     * @param {Object} options - Export configuration options
     */
    constructor(container, options = {}) {
        // Store reference to container
        this._container = container;
        
        // Configuration
        this._options = {
            exportRoute: options.exportRoute || DFConstants.DEFAULTS.EXPORT_ROUTE,
            configFile: options.configFile || null,
            exportable: options.exportable || false,
            exportableByChunk: options.exportableByChunk || false,
            chunkSize: options.chunkSize || DFConstants.EXPORT.CHUNK_SIZE,
            ...options
        };

        // Export state
        this._isExporting = false;
        this._currentExporter = null;
        
        // Callbacks
        this._onProgress = null;
        this._onComplete = null;
        this._onError = null;
    }

    // ==========================================================================
    // PUBLIC METHODS
    // ==========================================================================

    /**
     * Check if export is enabled
     * @returns {boolean}
     */
    isEnabled() {
        return this._options.exportable === true;
    }

    /**
     * Check if currently exporting
     * @returns {boolean}
     */
    isExporting() {
        return this._isExporting;
    }

    /**
     * Set progress callback
     * @param {Function} callback 
     * @returns {ExportManager} - For chaining
     */
    onProgress(callback) {
        this._onProgress = callback;
        return this;
    }

    /**
     * Set complete callback
     * @param {Function} callback 
     * @returns {ExportManager} - For chaining
     */
    onComplete(callback) {
        this._onComplete = callback;
        return this;
    }

    /**
     * Set error callback
     * @param {Function} callback 
     * @returns {ExportManager} - For chaining
     */
    onError(callback) {
        this._onError = callback;
        return this;
    }

    /**
     * Start export process
     * @param {string} format - Export format (xlsx, xls, csv)
     * @param {Object} params - DataTable ajax params
     * @param {HTMLElement} buttonElement - The clicked button element (for UI updates)
     * @returns {Promise}
     */
    async export(format, params, buttonElement = null) {
        if (!this.isEnabled()) {
            console.warn('DataFinder: Export is not enabled for this instance');
            return;
        }

        if (this._isExporting) {
            console.warn('DataFinder: Export already in progress');
            return;
        }

        // Validate format
        format = format.toLowerCase();
        if (!DFConstants.EXPORT.FORMATS.includes(format)) {
            const error = `Invalid export format: ${format}`;
            this._handleError(error);
            return;
        }

        this._isExporting = true;

        // Dispatch export start event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.EXPORT_START, { format });

        // UI state management
        const uiElements = buttonElement ? this._setupExportUI(buttonElement) : null;

        try {
            // Create exporter instance
            this._currentExporter = await this._createExporter(format);

            // Prepare export params
            const exportParams = {
                ...params,
                exportable: true,
                export_extension: format,
                config_file_name: this._options.configFile,
                offset: 0
            };

            // Start fetching data
            await this._fetchExportData(exportParams, uiElements);

            // Success
            this._handleComplete(format);

        } catch (error) {
            this._handleError(error);
        } finally {
            this._isExporting = false;
            this._currentExporter = null;
            
            // Reset UI
            if (uiElements) {
                this._resetExportUI(uiElements);
            }
        }
    }

    /**
     * Cancel ongoing export
     */
    cancel() {
        if (this._isExporting) {
            this._isExporting = false;
            this._currentExporter = null;
            console.log('DataFinder: Export cancelled');
        }
    }

    /**
     * Update export options
     * @param {Object} options 
     * @returns {ExportManager} - For chaining
     */
    updateOptions(options) {
        this._options = { ...this._options, ...options };
        return this;
    }

    /**
     * Get available export formats
     * @returns {Array}
     */
    getAvailableFormats() {
        return [...DFConstants.EXPORT.FORMATS];
    }

    /**
     * Destroy export manager
     */
    destroy() {
        this.cancel();
        this._container = null;
        this._onProgress = null;
        this._onComplete = null;
        this._onError = null;
    }

    // ==========================================================================
    // PRIVATE METHODS
    // ==========================================================================

    /**
     * Create Exporter instance
     * @param {string} format 
     * @returns {Promise<Exporter>}
     * @private
     */
    async _createExporter(format) {
        // Dynamic import for Exporter
        const { default: Exporter } = await import('../export/services/Exporter.js');
        
        const filename = `${this._options.configFile.replace('/', '_')}_${new Date().toISOString()}.${format}`;
        
        return new Exporter({
            filename,
            sheetName: 'Data',
            extension: format,
            multiSheet: false,
            metadata: {
                Author: 'DataFinder',
                Manager: 'SUHK',
                Company: 'SUHK',
                CreatedDate: new Date()
            }
        });
    }

    /**
     * Fetch export data (handles chunking)
     * @param {Object} params 
     * @param {Object} uiElements 
     * @returns {Promise}
     * @private
     */
    _fetchExportData(params, uiElements) {
        return new Promise((resolve, reject) => {
            this._fetchChunk(params, uiElements, resolve, reject);
        });
    }

    /**
     * Fetch a single chunk of data
     * @param {Object} params 
     * @param {Object} uiElements 
     * @param {Function} resolve 
     * @param {Function} reject 
     * @private
     */
    _fetchChunk(params, uiElements, resolve, reject) {
        // Check if cancelled
        if (!this._isExporting) {
            reject(new Error('Export cancelled'));
            return;
        }

        $.ajax({
            url: this._options.exportRoute,
            type: 'POST',
            data: params,
            headers: {
                'X-CSRF-TOKEN': DOMUtils.getCSRFToken()
            },
            success: (response) => {
                const { data, next_offset, completed_percentage } = response;

                try {
                    // Append data to exporter
                    const isFinalChunk = next_offset === null;
                    this._currentExporter.appendData(data, { isFinalChunk });

                    // Update progress
                    this._updateProgress(completed_percentage, uiElements);

                    // Dispatch progress event
                    DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.EXPORT_PROGRESS, {
                        percentage: completed_percentage,
                        hasMore: !isFinalChunk
                    });

                    // Callback
                    if (this._onProgress) {
                        this._onProgress(completed_percentage, isFinalChunk);
                    }

                    if (!isFinalChunk) {
                        // Fetch next chunk
                        params.offset = next_offset;
                        this._fetchChunk(params, uiElements, resolve, reject);
                    } else {
                        resolve();
                    }

                } catch (error) {
                    reject(error);
                }
            },
            error: (xhr) => {
                let errorMessage = 'Export failed';
                if (xhr.responseJSON) {
                    errorMessage = xhr.responseJSON.message || 
                                   (xhr.responseJSON.exception ? `${xhr.responseJSON.exception}: ${xhr.responseJSON.message}` : errorMessage);
                }
                reject(new Error(errorMessage));
            }
        });
    }

    /**
     * Setup export button UI (spinner, progress)
     * @param {HTMLElement} buttonElement 
     * @returns {Object}
     * @private
     */
    _setupExportUI(buttonElement) {
        // Create spinner
        const spinner = DOMUtils.createSpinner(DFConstants.ASSETS.SPINNER, 'Exporting...');
        
        // Create progress text
        const progressSpan = DOMUtils.createElement('span', {
            className: 'df-export-progress',
            style: { marginLeft: '5px', fontSize: '12px' }
        }, '0%');

        // Add to button
        buttonElement.prepend(spinner);
        buttonElement.appendChild(progressSpan);

        // Disable button
        buttonElement.disabled = true;

        return { spinner, progressSpan, button: buttonElement };
    }

    /**
     * Reset export button UI
     * @param {Object} uiElements 
     * @private
     */
    _resetExportUI(uiElements) {
        if (!uiElements) return;

        const { spinner, progressSpan, button } = uiElements;
        
        DOMUtils.remove(spinner);
        DOMUtils.remove(progressSpan);
        
        if (button) {
            button.disabled = false;
        }
    }

    /**
     * Update progress display
     * @param {string} percentage 
     * @param {Object} uiElements 
     * @private
     */
    _updateProgress(percentage, uiElements) {
        if (uiElements && uiElements.progressSpan) {
            uiElements.progressSpan.textContent = percentage;
        }
    }

    /**
     * Handle export completion
     * @param {string} format 
     * @private
     */
    _handleComplete(format) {
        // Dispatch complete event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.EXPORT_COMPLETE, { format });

        // Callback
        if (this._onComplete) {
            this._onComplete(format);
        }
    }

    /**
     * Handle export error
     * @param {Error|string} error 
     * @private
     */
    _handleError(error) {
        const message = error instanceof Error ? error.message : String(error);
        
        // Dispatch error event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.EXPORT_ERROR, { error: message });

        // Callback
        if (this._onError) {
            this._onError(message);
        }

        console.error('DataFinder Export Error:', message);
    }

}

