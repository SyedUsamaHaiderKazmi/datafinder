/**
 * DataFinder Legacy Support
 * 
 * This file provides backward compatibility for v1.x implementations.
 * It wraps the new v2.x architecture to support the old global variable approach.
 * 
 * DEPRECATION NOTICE:
 * This legacy mode is deprecated and will be removed in v3.0.
 * Please migrate to the new DataFinder.init() API.
 * 
 * @package SUHK\DataFinder
 * @deprecated Since 2.0.0 - Use DataFinder.init() instead
 * @since 2.0.0
 */

(function() {
    'use strict';

    console.warn(
        '[DataFinder] Legacy mode is deprecated. ' +
        'Please migrate to DataFinder.init() API. ' +
        'See documentation: https://datafinder.suhk.me/migration'
    );

    // ==========================================================================
    // LEGACY GLOBAL VARIABLES
    // ==========================================================================
    
    // These globals are provided for backward compatibility only
    window.datatable = null;
    window.filters = {};
    window.errorList = [];

    // ==========================================================================
    // WAIT FOR NEW ARCHITECTURE TO LOAD
    // ==========================================================================

    const initLegacyMode = () => {
        // Check if new DataFinder is available
        if (typeof window.DataFinder === 'undefined') {
            console.error('[DataFinder] New architecture not loaded. Legacy mode cannot initialize.');
            return;
        }

        // Check for required legacy globals
        if (typeof window.frontend_config === 'undefined' || 
            typeof window.config_file_name === 'undefined') {
            console.warn('[DataFinder] Legacy config variables not found. Skipping legacy init.');
            return;
        }

        // Build options from legacy globals
        const options = {
            configFile: window.config_file_name,
            columns: window.columns || [],
            perPage: window.default_per_page || 10,
            perPageOptions: window.per_page_options || [10, 25, 50, 100],
            allowPerPageChange: window.allow_per_page_options !== false,
            searching: window.full_text_search !== false,
            exportable: window.exportable || false,
            dataRoute: window.live_search_filter_route || '/df/data',
            exportRoute: window.export_route || '/df/export/init',
        };

        // Find the legacy container
        const containerId = 'df-' + window.datafinder_table_id;
        let container = document.getElementById(containerId);
        
        // If no container, try to find table and wrap it
        if (!container) {
            const table = document.getElementById(containerId);
            if (table) {
                // Create wrapper
                const wrapper = document.createElement('div');
                wrapper.id = containerId + '-container';
                wrapper.className = 'df-container';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
                container = wrapper;
            }
        }

        if (!container) {
            console.error('[DataFinder] Cannot find container for legacy mode');
            return;
        }

        // Ensure table has df-table class
        const table = container.querySelector('table') || container;
        if (table.tagName === 'TABLE' && !table.classList.contains('df-table')) {
            table.classList.add('df-table');
        }

        // Initialize using new architecture
        const instance = window.DataFinder.init(container.id, options);

        // Expose to legacy globals
        window.datatable = instance.getDataTable();
        
        // Override legacy functions
        window.setupFilterObject = () => {
            window.filters = instance.getFilters();
        };

        window.dataTableReload = () => {
            instance.reload();
        };

        window.updateErrorDisplay = () => {
            // Handled by new ErrorHandler
        };

        console.log('[DataFinder] Legacy mode initialized successfully');
    };

    // ==========================================================================
    // LEGACY HELPER FUNCTIONS
    // ==========================================================================

    // These are kept for compatibility but delegate to new architecture
    window.addEventToFilters = function() {
        console.warn('[DataFinder] addEventToFilters() is deprecated - events are auto-bound');
    };

    window.addEventsToDatatable = function(dt) {
        console.warn('[DataFinder] addEventsToDatatable() is deprecated - events are auto-bound');
    };

    window.setupToolbar = function() {
        console.warn('[DataFinder] setupToolbar() is deprecated - toolbar is auto-configured');
        return [];
    };

    window.fnExport = function(event, datatable, ext) {
        if (window.DataFinder) {
            const instances = window.DataFinder.getAll();
            const firstInstance = Object.values(instances)[0];
            if (firstInstance) {
                firstInstance.export(ext);
            }
        }
    };

    window.fnResetReloadButtonView = function() {
        // Handled by new ToolbarManager
    };

    window.fnResetExportButtonView = function() {
        // Handled by new ExportManager
    };

    // ==========================================================================
    // AUTO-INITIALIZE ON DOM READY
    // ==========================================================================

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLegacyMode);
    } else {
        // DOM already loaded
        setTimeout(initLegacyMode, 0);
    }

})();

