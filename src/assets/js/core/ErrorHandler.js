/**
 * DataFinder Error Handler
 * 
 * Responsible for:
 * - Managing error collection
 * - Rendering error messages to DOM (SCOPED to container)
 * - Providing error notification hooks
 * 
 * Single Responsibility: Only handles error-related operations
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

import DFConstants from './Constants.js';
import DOMUtils from '../utils/DOMUtils.js';

export default class ErrorHandler {

    /**
     * Create an ErrorHandler instance
     * @param {HTMLElement} container - The scoped container element
     */
    constructor(container) {
        // Store reference to container
        this._container = container;
        
        // Error list for this instance
        this._errors = [];
        
        // Find or create error container
        this._errorContainer = this._getOrCreateErrorContainer();
    }

    // ==========================================================================
    // PUBLIC METHODS
    // ==========================================================================

    /**
     * Add an error message
     * @param {string|Error} error - Error message or Error object
     * @returns {ErrorHandler} - For chaining
     */
    add(error) {
        const message = error instanceof Error ? error.message : String(error);
        
        if (message && !this._errors.includes(message)) {
            this._errors.push(message);
            this._render();
            
            // Dispatch error event
            DOMUtils.dispatchEvent(this._container, DFConstants.EVENTS.ERROR, {
                error: message,
                errors: [...this._errors]
            });
        }
        
        return this;
    }

    /**
     * Add multiple errors
     * @param {Array} errors - Array of error messages
     * @returns {ErrorHandler} - For chaining
     */
    addMultiple(errors) {
        if (Array.isArray(errors)) {
            errors.forEach(error => this.add(error));
        }
        return this;
    }

    /**
     * Clear all errors
     * @returns {ErrorHandler} - For chaining
     */
    clear() {
        this._errors = [];
        this._render();
        return this;
    }

    /**
     * Get all current errors
     * @returns {Array}
     */
    getErrors() {
        return [...this._errors];
    }

    /**
     * Check if there are any errors
     * @returns {boolean}
     */
    hasErrors() {
        return this._errors.length > 0;
    }

    /**
     * Get error count
     * @returns {number}
     */
    count() {
        return this._errors.length;
    }

    /**
     * Handle AJAX error response
     * @param {Object} xhr - jQuery AJAX xhr object
     * @returns {ErrorHandler} - For chaining
     */
    handleAjaxError(xhr) {
        let message = 'An unknown error occurred';
        
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                // Laravel validation errors
                if (Array.isArray(xhr.responseJSON.errors)) {
                    return this.addMultiple(xhr.responseJSON.errors);
                } else if (typeof xhr.responseJSON.errors === 'object') {
                    // Object format: { field: ['error1', 'error2'] }
                    const messages = Object.values(xhr.responseJSON.errors).flat();
                    return this.addMultiple(messages);
                }
            }
            message = xhr.responseJSON.message || message;
        } else if (xhr.statusText) {
            message = `${xhr.status}: ${xhr.statusText}`;
        }
        
        return this.add(message);
    }

    /**
     * Handle DataTable error
     * @param {string} message - Error message from DataTables
     * @returns {ErrorHandler} - For chaining
     */
    handleDataTableError(message) {
        return this.add(message);
    }

    /**
     * Destroy error handler and clean up
     */
    destroy() {
        this._errors = [];
        if (this._errorContainer) {
            DOMUtils.hide(this._errorContainer);
        }
        this._container = null;
        this._errorContainer = null;
    }

    // ==========================================================================
    // PRIVATE METHODS
    // ==========================================================================

    /**
     * Get existing or create new error container
     * @returns {HTMLElement}
     * @private
     */
    _getOrCreateErrorContainer() {
        // Try to find existing error container
        let container = DOMUtils.find(
            this._container, 
            `.${DFConstants.SELECTORS.ERROR_CONTAINER}`
        );
        
        if (!container) {
            // Create error container if not exists
            container = DOMUtils.createElement('div', {
                className: `${DFConstants.SELECTORS.ERROR_CONTAINER} ${DFConstants.CLASSES.HIDDEN}`
            });
            
            // Insert at the beginning of the main container
            this._container.insertBefore(container, this._container.firstChild);
        }
        
        return container;
    }

    /**
     * Render errors to DOM
     * @private
     */
    _render() {
        if (!this._errorContainer) return;

        if (this._errors.length === 0) {
            // Hide and clear error container
            this._errorContainer.innerHTML = '';
            this._errorContainer.classList.add(DFConstants.CLASSES.HIDDEN);
            DOMUtils.hide(this._errorContainer);
            return;
        }

        // Build error HTML
        const errorHTML = this._buildErrorHTML();
        
        // Update container
        this._errorContainer.innerHTML = errorHTML;
        this._errorContainer.classList.remove(DFConstants.CLASSES.HIDDEN);
        DOMUtils.show(this._errorContainer);
    }

    /**
     * Build error list HTML
     * @returns {string}
     * @private
     */
    _buildErrorHTML() {
        const errorItems = this._errors
            .map(error => `<li>${this._escapeHTML(error)}</li>`)
            .join('');

        return `
            <div class="${DFConstants.SELECTORS.ERROR_MESSAGE}" 
                 style="color: #721c24; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 10px;">
                <p style="margin: 0 0 10px 0; font-weight: bold;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px; vertical-align: text-bottom;">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    Following issue(s) found:
                </p>
                <ol style="margin: 0; padding-left: 20px;">
                    ${errorItems}
                </ol>
            </div>
        `;
    }

    /**
     * Escape HTML to prevent XSS
     * @param {string} text 
     * @returns {string}
     * @private
     */
    _escapeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

}

