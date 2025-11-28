/**
 * DataFinder Constants
 * 
 * Central configuration and constant values for the DataFinder package.
 * Single source of truth for all configurable values.
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

const DFConstants = {
    
    // ==========================================================================
    // CSS SELECTORS - Used for DOM queries (scoped to container)
    // ==========================================================================
    SELECTORS: {
        FILTER_CLASS: 'df-filter',              // Filter input/select elements
        TABLE_CLASS: 'df-table',                // DataTable element
        ERROR_CONTAINER: 'df-errors',           // Error display container
        ERROR_MESSAGE: 'df-error-message',      // Error message wrapper
        FILTER_BUTTON: 'df-filter-btn',         // Filter submit button
        CLEAR_BUTTON: 'df-clear-btn',           // Clear filters button
        CONTAINER_CLASS: 'df-container',        // Main container class
    },

    // ==========================================================================
    // DATA ATTRIBUTES - Used on filter elements
    // ==========================================================================
    DATA_ATTRS: {
        COLUMN: 'data-column',                  // Database column name
        OPERATOR: 'data-operator',              // Conditional operator (=, >=, etc.)
        JOIN_TABLE: 'data-join-table',          // Table name for join filters
        FILTER_THROUGH_JOIN: 'data-join-filter', // Boolean: filter through join
        TYPE: 'data-filter-type',               // Filter type override
    },

    // ==========================================================================
    // DEFAULT VALUES
    // ==========================================================================
    DEFAULTS: {
        PER_PAGE: 10,
        PER_PAGE_OPTIONS: [10, 25, 50, 100],
        CONDITIONAL_OPERATOR: '=',
        DATA_ROUTE: '/df/data',
        EXPORT_ROUTE: '/df/export/init',
        AJAX_TYPE: 'POST',
        ORDER_DIRECTION: 'asc',
    },

    // ==========================================================================
    // ASSET PATHS
    // ==========================================================================
    ASSETS: {
        SPINNER: '/vendor/datafinder/assets/svgs/spinner.svg',
        SPINNER_LIGHT: '/vendor/datafinder/assets/svgs/spinner-light.svg',
    },

    // ==========================================================================
    // EXPORT CONFIGURATION
    // ==========================================================================
    EXPORT: {
        FORMATS: ['xlsx', 'xls', 'csv'],
        DEFAULT_FORMAT: 'xlsx',
        CHUNK_SIZE: 1000,
    },

    // ==========================================================================
    // CSS CLASSES FOR DYNAMIC ELEMENTS
    // ==========================================================================
    CLASSES: {
        HIDDEN: 'd-none',
        LOADING: 'df-loading',
        ERROR: 'df-error',
        SUCCESS: 'df-success',
    },

    // ==========================================================================
    // EVENT NAMES - Custom events fired by DataFinder
    // ==========================================================================
    EVENTS: {
        INIT: 'df:init',
        DESTROY: 'df:destroy',
        RELOAD: 'df:reload',
        FILTER_CHANGE: 'df:filter:change',
        FILTER_CLEAR: 'df:filter:clear',
        EXPORT_START: 'df:export:start',
        EXPORT_PROGRESS: 'df:export:progress',
        EXPORT_COMPLETE: 'df:export:complete',
        EXPORT_ERROR: 'df:export:error',
        ERROR: 'df:error',
        DATA_LOADED: 'df:data:loaded',
    },

    // ==========================================================================
    // ERROR MESSAGES
    // ==========================================================================
    MESSAGES: {
        CONTAINER_NOT_FOUND: 'DataFinder: Container element not found',
        TABLE_NOT_FOUND: 'DataFinder: Table element not found in container',
        CONFIG_REQUIRED: 'DataFinder: configFile option is required',
        COLUMNS_REQUIRED: 'DataFinder: columns option is required',
        ALREADY_INITIALIZED: 'DataFinder: Instance already exists, destroying old instance',
        EXPORT_NO_DATA: 'No data available for export',
    },

};

// Freeze to prevent modifications
Object.freeze(DFConstants);
Object.freeze(DFConstants.SELECTORS);
Object.freeze(DFConstants.DATA_ATTRS);
Object.freeze(DFConstants.DEFAULTS);
Object.freeze(DFConstants.ASSETS);
Object.freeze(DFConstants.EXPORT);
Object.freeze(DFConstants.CLASSES);
Object.freeze(DFConstants.EVENTS);
Object.freeze(DFConstants.MESSAGES);

export default DFConstants;

