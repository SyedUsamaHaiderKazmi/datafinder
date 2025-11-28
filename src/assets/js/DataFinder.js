/**
 * DataFinder - Main Entry Point
 * 
 * Factory Pattern wrapped in IIFE for:
 * - Multiple instance support per page
 * - On-demand initialization (works with jQuery.html())
 * - Global instance registry
 * - Clean public API
 * 
 * Usage:
 *   DataFinder.init('container-id', { configFile: 'module/main', columns: [...] });
 *   DataFinder.get('container-id').reload();
 *   DataFinder.destroy('container-id');
 * 
 * @package SUHK\DataFinder
 * @author Syed Usama Haider Kazmi
 * @since 2.0.0
 */

import DFConstants from './core/Constants.js';
import DataFinderInstance from './core/Instance.js';

/**
 * DataFinder Factory (IIFE)
 * 
 * Creates a singleton factory that manages all DataFinder instances.
 * The IIFE ensures private scope while exposing a clean public API.
 */
const DataFinder = (function() {
    'use strict';

    // ==========================================================================
    // PRIVATE STORAGE
    // ==========================================================================
    
    /**
     * Registry of all active DataFinder instances
     * Key: containerId, Value: DataFinderInstance
     * @private
     */
    const _instances = new Map();

    /**
     * Global configuration defaults
     * @private
     */
    let _globalConfig = {};

    // ==========================================================================
    // PRIVATE METHODS
    // ==========================================================================

    /**
     * Validate container ID
     * @param {string} containerId 
     * @returns {string} - Cleaned container ID
     * @private
     */
    const _cleanContainerId = (containerId) => {
        if (!containerId || typeof containerId !== 'string') {
            throw new Error('DataFinder: containerId must be a non-empty string');
        }
        return containerId.replace('#', '').trim();
    };

    /**
     * Check if instance exists
     * @param {string} containerId 
     * @returns {boolean}
     * @private
     */
    const _hasInstance = (containerId) => {
        return _instances.has(_cleanContainerId(containerId));
    };

    /**
     * Log debug message (only in development)
     * @param  {...any} args 
     * @private
     */
    const _debug = (...args) => {
        if (_globalConfig.debug) {
            console.log('[DataFinder]', ...args);
        }
    };

    // ==========================================================================
    // PUBLIC API
    // ==========================================================================

    return {

        /**
         * Initialize a new DataFinder instance
         * 
         * @param {string} containerId - Container element ID (with or without #)
         * @param {Object} options - Configuration options
         * @param {string} options.configFile - Path to config file (required)
         * @param {Array} options.columns - DataTable column definitions (required)
         * @param {number} [options.perPage=10] - Default records per page
         * @param {Array} [options.perPageOptions] - Per page dropdown options
         * @param {boolean} [options.exportable=false] - Enable export functionality
         * @param {string} [options.dataRoute] - Custom data endpoint
         * @param {string} [options.exportRoute] - Custom export endpoint
         * @returns {DataFinderInstance} - The initialized instance
         * 
         * @example
         * DataFinder.init('users-table', {
         *     configFile: 'users/main',
         *     columns: [
         *         { title: 'Name', data: 'name' },
         *         { title: 'Email', data: 'email' }
         *     ],
         *     exportable: true
         * });
         */
        init: function(containerId, options = {}) {
            containerId = _cleanContainerId(containerId);

            // Destroy existing instance if re-initializing
            if (_hasInstance(containerId)) {
                _debug(`Destroying existing instance: ${containerId}`);
                this.destroy(containerId);
            }

            // Merge with global config
            const mergedOptions = { ..._globalConfig, ...options };

            _debug(`Initializing instance: ${containerId}`, mergedOptions);

            // Create and initialize new instance
            const instance = new DataFinderInstance(containerId, mergedOptions);
            instance.init();

            // Store in registry
            _instances.set(containerId, instance);

            return instance;
        },

        /**
         * Get an existing DataFinder instance
         * 
         * @param {string} containerId - Container element ID
         * @returns {DataFinderInstance|null} - The instance or null if not found
         * 
         * @example
         * const instance = DataFinder.get('users-table');
         * if (instance) {
         *     instance.reload();
         * }
         */
        get: function(containerId) {
            containerId = _cleanContainerId(containerId);
            return _instances.get(containerId) || null;
        },

        /**
         * Check if an instance exists
         * 
         * @param {string} containerId - Container element ID
         * @returns {boolean}
         * 
         * @example
         * if (DataFinder.has('users-table')) {
         *     DataFinder.get('users-table').reload();
         * }
         */
        has: function(containerId) {
            return _hasInstance(containerId);
        },

        /**
         * Destroy a DataFinder instance
         * 
         * @param {string} containerId - Container element ID
         * @returns {boolean} - True if destroyed, false if not found
         * 
         * @example
         * DataFinder.destroy('users-table');
         */
        destroy: function(containerId) {
            containerId = _cleanContainerId(containerId);
            
            const instance = _instances.get(containerId);
            if (instance) {
                instance.destroy();
                _instances.delete(containerId);
                _debug(`Destroyed instance: ${containerId}`);
                return true;
            }
            
            return false;
        },

        /**
         * Destroy all DataFinder instances
         * Useful for SPA navigation cleanup
         * 
         * @returns {number} - Number of instances destroyed
         * 
         * @example
         * // Before navigating away in SPA
         * DataFinder.destroyAll();
         */
        destroyAll: function() {
            let count = 0;
            _instances.forEach((instance, containerId) => {
                instance.destroy();
                count++;
            });
            _instances.clear();
            _debug(`Destroyed all instances (${count})`);
            return count;
        },

        /**
         * Get all active instances
         * 
         * @returns {Object} - Object with containerId as key, instance as value
         * 
         * @example
         * const instances = DataFinder.getAll();
         * Object.keys(instances).forEach(id => {
         *     instances[id].reload();
         * });
         */
        getAll: function() {
            const result = {};
            _instances.forEach((instance, containerId) => {
                result[containerId] = instance;
            });
            return result;
        },

        /**
         * Get instance count
         * 
         * @returns {number}
         */
        count: function() {
            return _instances.size;
        },

        /**
         * Reload a specific instance
         * Shorthand for DataFinder.get(id).reload()
         * 
         * @param {string} containerId - Container element ID
         * @param {Function} [callback] - Optional callback after reload
         * @returns {DataFinderInstance|null}
         * 
         * @example
         * DataFinder.reload('users-table');
         */
        reload: function(containerId, callback = null) {
            const instance = this.get(containerId);
            if (instance) {
                instance.reload(callback);
            }
            return instance;
        },

        /**
         * Reload all active instances
         * 
         * @returns {number} - Number of instances reloaded
         * 
         * @example
         * DataFinder.reloadAll();
         */
        reloadAll: function() {
            let count = 0;
            _instances.forEach(instance => {
                instance.reload();
                count++;
            });
            return count;
        },

        /**
         * Set global configuration defaults
         * These will be merged with options passed to init()
         * 
         * @param {Object} config - Global configuration
         * 
         * @example
         * DataFinder.configure({
         *     perPage: 25,
         *     debug: true,
         *     dataRoute: '/api/df/data'
         * });
         */
        configure: function(config) {
            _globalConfig = { ..._globalConfig, ...config };
            _debug('Global config updated:', _globalConfig);
        },

        /**
         * Get global configuration
         * 
         * @returns {Object}
         */
        getConfig: function() {
            return { ..._globalConfig };
        },

        /**
         * Get constants for external use
         * 
         * @returns {Object}
         * 
         * @example
         * const { EVENTS } = DataFinder.constants();
         * element.addEventListener(EVENTS.RELOAD, handler);
         */
        constants: function() {
            return DFConstants;
        },

        /**
         * Version information
         */
        version: '2.0.0',

        /**
         * Package information
         */
        info: {
            name: 'DataFinder',
            author: 'Syed Usama Haider Kazmi',
            license: 'MIT',
            repository: 'https://github.com/SyedUsamaHaiderKazmi/datafinder'
        }

    };

})();

// ==========================================================================
// EXPORT & GLOBAL REGISTRATION
// ==========================================================================

// ES Module export
export default DataFinder;

// Also expose constants
export { default as DFConstants } from './core/Constants.js';

// Register globally for non-module usage
if (typeof window !== 'undefined') {
    window.DataFinder = DataFinder;
}

