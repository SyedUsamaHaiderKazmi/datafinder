/**
 * DataFinder Instance
 * 
 * Main instance class that orchestrates all components:
 * - FilterManager for filter handling
 * - ErrorHandler for error management
 * - ExportManager for data export
 * - ToolbarManager for button management
 * - DataTable integration
 * 
 * This class follows the Facade pattern - providing a unified interface
 * to all the subsystems.
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

import DFConstants from './Constants.js';
import DOMUtils from '../utils/DOMUtils.js';
import FilterManager from './FilterManager.js';
import ErrorHandler from './ErrorHandler.js';
import ExportManager from './ExportManager.js';
import ToolbarManager from './ToolbarManager.js';

export default class DataFinderInstance {

    /**
     * Create a DataFinder instance
     * @param {string} containerId - Container element ID (without #)
     * @param {Object} options - Configuration options
     */
    constructor(containerId, options = {}) {
        // =======================================================================
        // INSTANCE PROPERTIES
        // =======================================================================
        
        // Container reference
        this._containerId = containerId.replace('#', '');
        this._container = document.getElementById(this._containerId);
        
        // Configuration (merged with defaults)
        this._options = this._mergeOptions(options);
        
        // Component references (initialized in init())
        this._datatable = null;
        this._filterManager = null;
        this._errorHandler = null;
        this._exportManager = null;
        this._toolbarManager = null;
        
        // State
        this._initialized = false;
        this._destroyed = false;
    }

    // ==========================================================================
    // PUBLIC API - LIFECYCLE
    // ==========================================================================

    /**
     * Initialize the DataFinder instance
     * @returns {DataFinderInstance} - For chaining
     */
    init() {
        // Validate container exists
        if (!this._container) {
            console.error(DFConstants.MESSAGES.CONTAINER_NOT_FOUND, this._containerId);
            return this;
        }

        // Validate required options
        if (!this._options.configFile) {
            console.error(DFConstants.MESSAGES.CONFIG_REQUIRED);
            return this;
        }

        if (!this._options.columns || this._options.columns.length === 0) {
            console.error(DFConstants.MESSAGES.COLUMNS_REQUIRED);
            return this;
        }

        // Initialize components
        this._initComponents();
        
        // Initialize DataTable
        this._initDataTable();
        
        // Mark as initialized
        this._initialized = true;
        
        // Dispatch init event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.INIT, {
            instance: this,
            containerId: this._containerId
        });

        return this;
    }

    /**
     * Destroy the instance and clean up
     * @returns {DataFinderInstance}
     */
    destroy() {
        if (this._destroyed) return this;

        // Destroy DataTable
        if (this._datatable) {
            this._datatable.destroy();
            this._datatable = null;
        }

        // Destroy components
        if (this._filterManager) {
            this._filterManager.destroy();
            this._filterManager = null;
        }

        if (this._errorHandler) {
            this._errorHandler.destroy();
            this._errorHandler = null;
        }

        if (this._exportManager) {
            this._exportManager.destroy();
            this._exportManager = null;
        }

        if (this._toolbarManager) {
            this._toolbarManager.destroy();
            this._toolbarManager = null;
        }

        // Unbind all events from container
        $(this._container).off();

        // Dispatch destroy event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.DESTROY, {
            containerId: this._containerId
        });

        this._destroyed = true;
        this._initialized = false;

        return this;
    }

    // ==========================================================================
    // PUBLIC API - DATA OPERATIONS
    // ==========================================================================

    /**
     * Reload the DataTable data
     * @param {Function} callback - Optional callback after reload
     * @returns {DataFinderInstance} - For chaining
     */
    reload(callback = null) {
        if (!this._datatable) return this;

        // Clear errors before reload
        this._errorHandler.clear();

        this._datatable.ajax.reload(() => {
            // Dispatch reload event
            DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.RELOAD);
            
            if (callback) callback();
        });

        return this;
    }

    /**
     * Get the underlying DataTable instance
     * @returns {DataTable|null}
     */
    getDataTable() {
        return this._datatable;
    }

    /**
     * Get current ajax parameters
     * @returns {Object}
     */
    getParams() {
        return this._datatable ? this._datatable.ajax.params() : {};
    }

    /**
     * Get current data from DataTable
     * @returns {Array}
     */
    getData() {
        return this._datatable ? this._datatable.rows().data().toArray() : [];
    }

    // ==========================================================================
    // PUBLIC API - FILTER OPERATIONS
    // ==========================================================================

    /**
     * Get current filter values
     * @returns {Object}
     */
    getFilters() {
        return this._filterManager ? this._filterManager.collect() : {};
    }

    /**
     * Set a filter value programmatically
     * @param {string} name - Filter name
     * @param {*} value - Filter value
     * @returns {DataFinderInstance} - For chaining
     */
    setFilter(name, value) {
        if (this._filterManager) {
            this._filterManager.setFilter(name, value);
        }
        return this;
    }

    /**
     * Clear all filters and reload
     * @returns {DataFinderInstance} - For chaining
     */
    clearFilters() {
        if (this._filterManager) {
            this._filterManager.clear();
            this.reload();
        }
        return this;
    }

    // ==========================================================================
    // PUBLIC API - EXPORT OPERATIONS
    // ==========================================================================

    /**
     * Trigger data export
     * @param {string} format - Export format (xlsx, xls, csv)
     * @returns {DataFinderInstance} - For chaining
     */
    export(format = 'xlsx') {
        if (this._exportManager && this._datatable) {
            const params = this._datatable.ajax.params();
            this._exportManager.export(format, params);
        }
        return this;
    }

    /**
     * Check if export is in progress
     * @returns {boolean}
     */
    isExporting() {
        return this._exportManager ? this._exportManager.isExporting() : false;
    }

    // ==========================================================================
    // PUBLIC API - ERROR HANDLING
    // ==========================================================================

    /**
     * Get current errors
     * @returns {Array}
     */
    getErrors() {
        return this._errorHandler ? this._errorHandler.getErrors() : [];
    }

    /**
     * Clear all errors
     * @returns {DataFinderInstance} - For chaining
     */
    clearErrors() {
        if (this._errorHandler) {
            this._errorHandler.clear();
        }
        return this;
    }

    /**
     * Add an error message
     * @param {string} error 
     * @returns {DataFinderInstance} - For chaining
     */
    addError(error) {
        if (this._errorHandler) {
            this._errorHandler.add(error);
        }
        return this;
    }

    // ==========================================================================
    // PUBLIC API - STATE & CONFIGURATION
    // ==========================================================================

    /**
     * Check if instance is initialized
     * @returns {boolean}
     */
    isInitialized() {
        return this._initialized;
    }

    /**
     * Check if instance is destroyed
     * @returns {boolean}
     */
    isDestroyed() {
        return this._destroyed;
    }

    /**
     * Get container element
     * @returns {HTMLElement}
     */
    getContainer() {
        return this._container;
    }

    /**
     * Get container ID
     * @returns {string}
     */
    getContainerId() {
        return this._containerId;
    }

    /**
     * Get configuration options
     * @returns {Object}
     */
    getOptions() {
        return { ...this._options };
    }

    /**
     * Update options (triggers re-initialization if needed)
     * @param {Object} options 
     * @returns {DataFinderInstance} - For chaining
     */
    updateOptions(options) {
        this._options = { ...this._options, ...options };
        
        // Update export manager options
        if (this._exportManager) {
            this._exportManager.updateOptions(options);
        }
        
        return this;
    }

    // ==========================================================================
    // PUBLIC API - EVENT HANDLING
    // ==========================================================================

    /**
     * Listen to DataFinder events
     * @param {string} eventName - Event name (use DFConstants.EVENTS)
     * @param {Function} handler - Event handler
     * @returns {DataFinderInstance} - For chaining
     */
    on(eventName, handler) {
        this._container.addEventListener(eventName, handler);
        return this;
    }

    /**
     * Remove event listener
     * @param {string} eventName 
     * @param {Function} handler 
     * @returns {DataFinderInstance} - For chaining
     */
    off(eventName, handler) {
        this._container.removeEventListener(eventName, handler);
        return this;
    }

    // ==========================================================================
    // PRIVATE METHODS - INITIALIZATION
    // ==========================================================================

    /**
     * Merge user options with defaults
     * @param {Object} options 
     * @returns {Object}
     * @private
     */
    _mergeOptions(options) {
        return {
            // Required
            configFile: options.configFile || null,
            columns: options.columns || [],
            
            // DataTable options
            perPage: options.perPage || DFConstants.DEFAULTS.PER_PAGE,
            perPageOptions: options.perPageOptions || DFConstants.DEFAULTS.PER_PAGE_OPTIONS,
            allowPerPageChange: options.allowPerPageChange !== false,
            searching: options.searching !== false,
            ordering: options.ordering !== false,
            responsive: options.responsive || false,
            
            // Routes
            dataRoute: options.dataRoute || DFConstants.DEFAULTS.DATA_ROUTE,
            exportRoute: options.exportRoute || DFConstants.DEFAULTS.EXPORT_ROUTE,
            allowCustomRoute: options.allowCustomRoute || false,
            
            // Export options
            exportable: options.exportable || false,
            exportableByChunk: options.exportableByChunk || false,
            chunkSize: options.chunkSize || DFConstants.EXPORT.CHUNK_SIZE,
            
            // UI options
            showReloadButton: options.showReloadButton !== false,
            showExportButton: options.showExportButton !== false,
            
            // Callbacks
            onDataLoaded: options.onDataLoaded || null,
            onError: options.onError || null,
            onFilterChange: options.onFilterChange || null,
            
            // Custom buttons
            customButtons: options.customButtons || [],
            
            // Row buttons (from config)
            hasRowButtons: options.hasRowButtons || false,
        };
    }

    /**
     * Initialize all component managers
     * @private
     */
    _initComponents() {
        // Initialize ErrorHandler first (other components may use it)
        this._errorHandler = new ErrorHandler(this._container);

        // Initialize FilterManager with reload callback
        this._filterManager = new FilterManager(
            this._container, 
            () => this.reload()
        );
        this._filterManager.init();

        // Initialize ExportManager
        this._exportManager = new ExportManager(this._container, {
            exportRoute: this._options.exportRoute,
            configFile: this._options.configFile,
            exportable: this._options.exportable,
            exportableByChunk: this._options.exportableByChunk,
            chunkSize: this._options.chunkSize
        });

        // Set up export error handling
        this._exportManager.onError((error) => {
            this._errorHandler.add(error);
        });

        // Initialize ToolbarManager
        this._toolbarManager = new ToolbarManager(this._container, {
            exportable: this._options.exportable,
            allowCustomRoute: this._options.allowCustomRoute,
            showReloadButton: this._options.showReloadButton,
            showExportButton: this._options.showExportButton,
            customButtons: this._options.customButtons
        });

        // Wire up component dependencies
        this._toolbarManager
            .setExportManager(this._exportManager)
            .setErrorHandler(this._errorHandler);
    }

    /**
     * Initialize DataTables
     * @private
     */
    _initDataTable() {
        // Find table element within container
        const tableEl = DOMUtils.find(
            this._container, 
            `.${DFConstants.SELECTORS.TABLE_CLASS}`
        );

        if (!tableEl) {
            this._errorHandler.add(DFConstants.MESSAGES.TABLE_NOT_FOUND);
            return;
        }

        // Get DataTable configuration
        const config = this._getDataTableConfig();

        // Disable DataTable default error mode (we handle errors ourselves)
        $.fn.dataTable.ext.errMode = 'none';

        // Initialize DataTable
        this._datatable = $(tableEl).DataTable(config);

        // Set DataTable reference in ToolbarManager
        this._toolbarManager.setDataTable(this._datatable);

        // Bind DataTable events
        this._bindDataTableEvents();
    }

    /**
     * Get DataTable configuration object
     * @returns {Object}
     * @private
     */
    _getDataTableConfig() {
        const self = this;
        
        // Find first orderable column
        const firstOrderableColumn = this._options.columns.findIndex(
            col => col.orderable !== false
        );

        return {
            // Basic settings
            info: true,
            paging: true,
            processing: true,
            serverSide: true,
            
            // Ordering
            order: firstOrderableColumn >= 0 
                ? [[firstOrderableColumn, DFConstants.DEFAULTS.ORDER_DIRECTION]] 
                : [],
            ordering: this._options.ordering,
            
            // Search
            searching: this._options.searching,
            search: {
                return: true // Search on enter key
            },
            
            // Pagination
            pageLength: this._options.perPage,
            lengthChange: this._options.allowPerPageChange,
            lengthMenu: this._options.perPageOptions,
            
            // Responsive
            responsive: this._options.responsive,
            
            // Layout (DataTables 2.x)
            layout: {
                top2End: 'buttons',
                topEnd: 'search',
            },
            
            // Columns
            columns: this._options.columns,
            
            // Buttons (from ToolbarManager)
            buttons: this._toolbarManager.getButtons(),
            
            // AJAX configuration
            ajax: {
                url: this._options.dataRoute,
                type: DFConstants.DEFAULTS.AJAX_TYPE,
                headers: {
                    'X-CSRF-TOKEN': DOMUtils.getCSRFToken()
                },
                data: function(data) {
                    // Collect current filter values
                    self._filterManager.collect();
                    
                    // Add DataFinder specific params
                    data.config_file_name = self._options.configFile;
                    data.filters = self._filterManager.getFilters();
                },
                error: function(xhr, status, error) {
                    self._handleAjaxError(xhr);
                }
            },
            
            // Callbacks
            initComplete: function(settings, json) {
                // Additional initialization if needed
            }
        };
    }

    /**
     * Bind DataTable events
     * @private
     */
    _bindDataTableEvents() {
        const self = this;

        // Handle DataTable errors
        this._datatable.on('error.dt', function(e, settings, techNote, message) {
            self._errorHandler.add(message);
        });

        // Handle XHR (before request)
        this._datatable.on('preXhr.dt', function(e, settings, data) {
            // Clear errors before new request
            self._errorHandler.clear();
        });

        // Handle XHR (after response)
        this._datatable.on('xhr.dt', function(e, settings, json, xhr) {
            if (json && json.errors && json.errors.length > 0) {
                self._errorHandler.addMultiple(json.errors);
            }

            // Dispatch data loaded event
            DOMUtils.dispatchEvent(self._container, DFConstants.EVENTS.DATA_LOADED, {
                data: json?.data || [],
                total: json?.recordsTotal || 0,
                filtered: json?.recordsFiltered || 0
            });

            // User callback
            if (self._options.onDataLoaded) {
                self._options.onDataLoaded(json);
            }
        });
    }

    /**
     * Handle AJAX errors
     * @param {Object} xhr 
     * @private
     */
    _handleAjaxError(xhr) {
        this._errorHandler.handleAjaxError(xhr);
        
        // User callback
        if (this._options.onError) {
            this._options.onError(xhr);
        }
    }

}

