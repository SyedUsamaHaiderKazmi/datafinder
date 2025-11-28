/**
 * DataFinder Filter Manager
 * 
 * Responsible for:
 * - Collecting filter values from DOM elements (SCOPED to container)
 * - Binding filter change events
 * - Formatting filter data for server requests
 * 
 * Single Responsibility: Only handles filter-related operations
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

import DFConstants from './Constants.js';
import DOMUtils from '../utils/DOMUtils.js';

export default class FilterManager {

    /**
     * Create a FilterManager instance
     * @param {HTMLElement} container - The scoped container element
     * @param {Function} onFilterChange - Callback when filters change
     */
    constructor(container, onFilterChange = null) {
        // Store reference to container (for scoped queries)
        this._container = container;
        
        // Callback for filter changes
        this._onFilterChange = onFilterChange;
        
        // Current filter values
        this._filters = {};
        
        // Bound event handler (for proper removal)
        this._boundHandleChange = this._handleChange.bind(this);
    }

    // ==========================================================================
    // PUBLIC METHODS
    // ==========================================================================

    /**
     * Initialize filter event bindings
     * @returns {FilterManager} - For chaining
     */
    init() {
        this._bindEvents();
        return this;
    }

    /**
     * Collect all filter values from DOM
     * Scoped to this instance's container only
     * @returns {Object} - Filter data object
     */
    collect() {
        this._filters = {};
        
        // Get all filter elements WITHIN this container only
        const filterElements = DOMUtils.findAll(
            this._container, 
            `.${DFConstants.SELECTORS.FILTER_CLASS}`
        );

        filterElements.forEach((element) => {
            this._processFilterElement(element);
        });

        return this._filters;
    }

    /**
     * Get current filter values
     * @returns {Object}
     */
    getFilters() {
        return { ...this._filters };
    }

    /**
     * Set a filter value programmatically
     * @param {string} name - Filter name
     * @param {*} value - Filter value
     * @returns {FilterManager} - For chaining
     */
    setFilter(name, value) {
        const element = DOMUtils.find(
            this._container, 
            `.${DFConstants.SELECTORS.FILTER_CLASS}[name="${name}"]`
        );
        
        if (element) {
            element.value = value;
            // Trigger change for Select2 compatibility
            $(element).trigger('change');
        }
        
        return this;
    }

    /**
     * Clear all filters
     * @returns {FilterManager} - For chaining
     */
    clear() {
        const filterElements = DOMUtils.findAll(
            this._container, 
            `.${DFConstants.SELECTORS.FILTER_CLASS}`
        );

        filterElements.forEach((element) => {
            if (element.tagName === 'SELECT') {
                element.selectedIndex = -1;
                // Clear Select2 if present
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).val(null).trigger('change');
                }
            } else {
                element.value = '';
            }
        });

        this._filters = {};
        
        // Dispatch clear event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.FILTER_CLEAR);
        
        return this;
    }

    /**
     * Destroy filter manager and clean up events
     */
    destroy() {
        this._unbindEvents();
        this._filters = {};
        this._container = null;
        this._onFilterChange = null;
    }

    // ==========================================================================
    // PRIVATE METHODS
    // ==========================================================================

    /**
     * Process a single filter element and extract its value
     * @param {HTMLElement} element 
     * @private
     */
    _processFilterElement(element) {
        // Skip empty values
        if (element.value === '' || element.value === null) {
            return;
        }

        const name = element.name;
        if (!name) {
            console.warn('DataFinder: Filter element missing name attribute', element);
            return;
        }

        // Initialize filter entry if needed
        if (!this._filters[name]) {
            this._filters[name] = {};
        }

        // Extract filter metadata from data attributes
        const filterMeta = this._extractFilterMeta(element);

        // Handle select elements with multiple options
        if (element.selectedOptions && element.selectedOptions.length > 0) {
            Array.from(element.selectedOptions).forEach((option, index) => {
                this._filters[name][index] = {
                    value: option.value,
                    ...filterMeta
                };
            });
        } else {
            // Handle input elements
            const length = Object.keys(this._filters[name]).length;
            this._filters[name][length] = {
                value: element.value,
                ...filterMeta
            };
        }
    }

    /**
     * Extract filter metadata from element attributes
     * @param {HTMLElement} element 
     * @returns {Object}
     * @private
     */
    _extractFilterMeta(element) {
        return {
            type: DOMUtils.getData(element, 'filter-type') || element.type || 'text',
            filter_through_join: DOMUtils.hasAttr(element, 'data-join-filter') || 
                                 DOMUtils.hasAttr(element, 'filter_through_join'),
            join_table: DOMUtils.getData(element, 'join-table') || 
                        element.getAttribute('join_table'),
            column_name: DOMUtils.getData(element, 'column') || 
                         element.getAttribute('column_name') || 
                         element.name,
            conditional_operator: DOMUtils.getData(element, 'operator') || 
                                  element.getAttribute('conditional_operator') || 
                                  DFConstants.DEFAULTS.CONDITIONAL_OPERATOR
        };
    }

    /**
     * Bind filter change events
     * @private
     */
    _bindEvents() {
        // Use event delegation on container for better performance
        // and to handle dynamically added filters
        $(this._container).on(
            'change', 
            `.${DFConstants.SELECTORS.FILTER_CLASS}`, 
            this._boundHandleChange
        );

        // Bind filter button click
        $(this._container).on(
            'click', 
            `.${DFConstants.SELECTORS.FILTER_BUTTON}`, 
            this._boundHandleChange
        );

        // Bind clear button click
        $(this._container).on(
            'click', 
            `.${DFConstants.SELECTORS.CLEAR_BUTTON}`, 
            () => {
                this.clear();
                if (this._onFilterChange) {
                    this._onFilterChange();
                }
            }
        );
    }

    /**
     * Unbind filter events
     * @private
     */
    _unbindEvents() {
        $(this._container).off('change', `.${DFConstants.SELECTORS.FILTER_CLASS}`);
        $(this._container).off('click', `.${DFConstants.SELECTORS.FILTER_BUTTON}`);
        $(this._container).off('click', `.${DFConstants.SELECTORS.CLEAR_BUTTON}`);
    }

    /**
     * Handle filter change event
     * @param {Event} event 
     * @private
     */
    _handleChange(event) {
        // Dispatch custom event
        DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.FILTER_CHANGE, {
            filter: event.target.name,
            value: event.target.value
        });

        // Call change callback if provided
        if (this._onFilterChange) {
            this._onFilterChange(event);
        }
    }

}

