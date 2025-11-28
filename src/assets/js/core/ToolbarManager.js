/**
 * DataFinder Toolbar Manager
 * 
 * Responsible for:
 * - Creating DataTable toolbar buttons (Export, Reload)
 * - Managing button states and actions
 * - Integrating with ExportManager
 * 
 * Single Responsibility: Only handles toolbar/button-related operations
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

import DFConstants from './Constants.js';
import DOMUtils from '../utils/DOMUtils.js';

export default class ToolbarManager {

    /**
     * Create a ToolbarManager instance
     * @param {HTMLElement} container - The scoped container element
     * @param {Object} options - Toolbar configuration
     */
    constructor(container, options = {}) {
        // Store reference to container
        this._container = container;
        
        // Configuration
        this._options = {
            exportable: options.exportable || false,
            allowCustomRoute: options.allowCustomRoute || false,
            exportFormats: options.exportFormats || DFConstants.EXPORT.FORMATS,
            showReloadButton: options.showReloadButton !== false, // Default true
            showExportButton: options.showExportButton !== false, // Default true
            customButtons: options.customButtons || [],
            ...options
        };

        // References
        this._datatable = null;
        this._exportManager = null;
        this._errorHandler = null;
        
        // Instance-specific IDs for elements
        this._instanceId = DOMUtils.generateId('df-toolbar');
    }

    // ==========================================================================
    // PUBLIC METHODS
    // ==========================================================================

    /**
     * Set DataTable reference
     * @param {DataTable} datatable 
     * @returns {ToolbarManager} - For chaining
     */
    setDataTable(datatable) {
        this._datatable = datatable;
        return this;
    }

    /**
     * Set ExportManager reference
     * @param {ExportManager} exportManager 
     * @returns {ToolbarManager} - For chaining
     */
    setExportManager(exportManager) {
        this._exportManager = exportManager;
        return this;
    }

    /**
     * Set ErrorHandler reference
     * @param {ErrorHandler} errorHandler 
     * @returns {ToolbarManager} - For chaining
     */
    setErrorHandler(errorHandler) {
        this._errorHandler = errorHandler;
        return this;
    }

    /**
     * Get DataTable button configuration
     * @returns {Array} - Array of button configs for DataTables
     */
    getButtons() {
        const buttons = [];

        // Add export button if enabled
        if (this._shouldShowExportButton()) {
            buttons.push(this._createExportButton());
        }

        // Add reload button if enabled
        if (this._options.showReloadButton) {
            buttons.push(this._createReloadButton());
        }

        // Add custom buttons
        if (this._options.customButtons.length > 0) {
            buttons.push(...this._options.customButtons);
        }

        return buttons;
    }

    /**
     * Manually trigger reload
     * @param {Function} callback - Optional callback after reload
     */
    triggerReload(callback = null) {
        if (!this._datatable) {
            console.warn('DataFinder: DataTable not set for ToolbarManager');
            return;
        }

        // Show spinner
        this._showReloadSpinner();

        this._datatable.ajax.reload(() => {
            this._hideReloadSpinner();
            if (callback) callback();
        });
    }

    /**
     * Manually trigger export
     * @param {string} format - Export format
     * @param {HTMLElement} buttonElement - Button element for UI updates
     */
    triggerExport(format, buttonElement = null) {
        if (!this._datatable || !this._exportManager) {
            console.warn('DataFinder: DataTable or ExportManager not set');
            return;
        }

        const params = this._datatable.ajax.params();
        
        // Clear previous errors
        if (this._errorHandler) {
            this._errorHandler.clear();
        }

        this._exportManager.export(format, params, buttonElement);
    }

    /**
     * Destroy toolbar manager
     */
    destroy() {
        this._datatable = null;
        this._exportManager = null;
        this._errorHandler = null;
        this._container = null;
    }

    // ==========================================================================
    // PRIVATE METHODS
    // ==========================================================================

    /**
     * Check if export button should be shown
     * @returns {boolean}
     * @private
     */
    _shouldShowExportButton() {
        return this._options.exportable && 
               this._options.showExportButton && 
               !this._options.allowCustomRoute;
    }

    /**
     * Create reload button configuration
     * @returns {Object}
     * @private
     */
    _createReloadButton() {
        const self = this;
        const spinnerId = `${this._instanceId}-reload-spin`;

        return {
            text: `<img id="${spinnerId}" 
                        class="df-reload-spinner" 
                        style="mix-blend-mode: difference; display: none;" 
                        src="${DFConstants.ASSETS.SPINNER_LIGHT}" 
                        alt="Loading"> 
                   <span>Reload</span>`,
            className: 'btn btn-primary btn-sm df-btn-reload',
            action: function(e, dt, node, config) {
                self._handleReloadClick(dt, spinnerId);
            }
        };
    }

    /**
     * Create export button configuration (dropdown)
     * @returns {Object}
     * @private
     */
    _createExportButton() {
        const self = this;
        
        // Create format buttons
        const formatButtons = this._options.exportFormats.map(format => ({
            text: format.toUpperCase(),
            className: 'btn btn-default btn-sm',
            action: function(e, dt, node, config) {
                // Get the actual button element
                const buttonEl = e.target.closest('button') || e.target;
                self._handleExportClick(dt, format, buttonEl);
            }
        }));

        return {
            extend: 'collection',
            text: 'Export',
            className: 'btn btn-dark btn-sm df-btn-export',
            style: 'background-color: #007bff; color: white; border-radius: 5px; margin-right: 1rem;',
            buttons: formatButtons
        };
    }

    /**
     * Handle reload button click
     * @param {DataTable} dt 
     * @param {string} spinnerId 
     * @private
     */
    _handleReloadClick(dt, spinnerId) {
        // Show spinner
        const spinner = document.getElementById(spinnerId);
        if (spinner) {
            spinner.style.display = 'inline';
        }

        // Clear errors
        if (this._errorHandler) {
            this._errorHandler.clear();
        }

        // Reload table
        dt.ajax.reload(() => {
            // Hide spinner
            if (spinner) {
                spinner.style.display = 'none';
            }
        });
    }

    /**
     * Handle export button click
     * @param {DataTable} dt 
     * @param {string} format 
     * @param {HTMLElement} buttonEl 
     * @private
     */
    _handleExportClick(dt, format, buttonEl) {
        if (!this._exportManager) {
            console.warn('DataFinder: ExportManager not set');
            return;
        }

        // Clear errors
        if (this._errorHandler) {
            this._errorHandler.clear();
        }

        // Get current DataTable params (includes filters)
        const params = dt.ajax.params();

        // Trigger export
        this._exportManager.export(format, params, buttonEl);
    }

    /**
     * Show reload spinner
     * @private
     */
    _showReloadSpinner() {
        const spinner = DOMUtils.find(this._container, '.df-reload-spinner');
        if (spinner) {
            DOMUtils.show(spinner);
        }
    }

    /**
     * Hide reload spinner
     * @private
     */
    _hideReloadSpinner() {
        const spinner = DOMUtils.find(this._container, '.df-reload-spinner');
        if (spinner) {
            DOMUtils.hide(spinner);
        }
    }

}

