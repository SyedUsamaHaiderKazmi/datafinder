/**
 * DataFinder DOM Utilities
 * 
 * Utility functions for DOM manipulation.
 * Follows DRY principle - common operations extracted here.
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

const DOMUtils = {

    /**
     * Get CSRF token from meta tag
     * @returns {string|null} CSRF token value
     */
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : null;
    },

    /**
     * Create an element with attributes and content
     * @param {string} tag - HTML tag name
     * @param {Object} attributes - Element attributes
     * @param {string|HTMLElement} content - Inner content
     * @returns {HTMLElement}
     */
    createElement(tag, attributes = {}, content = null) {
        const element = document.createElement(tag);
        
        Object.entries(attributes).forEach(([key, value]) => {
            if (key === 'className') {
                element.className = value;
            } else if (key === 'style' && typeof value === 'object') {
                Object.assign(element.style, value);
            } else if (key.startsWith('data-')) {
                element.setAttribute(key, value);
            } else {
                element[key] = value;
            }
        });

        if (content) {
            if (typeof content === 'string') {
                element.innerHTML = content;
            } else if (content instanceof HTMLElement) {
                element.appendChild(content);
            }
        }

        return element;
    },

    /**
     * Create a spinner element
     * @param {string} src - Spinner image source
     * @param {string} alt - Alt text
     * @param {Object} styles - Additional styles
     * @returns {HTMLImageElement}
     */
    createSpinner(src, alt = 'Loading...', styles = {}) {
        return this.createElement('img', {
            src,
            alt,
            className: 'df-spinner',
            style: {
                height: '16px',
                marginRight: '5px',
                ...styles
            }
        });
    },

    /**
     * Show an element
     * @param {HTMLElement} element 
     */
    show(element) {
        if (element) {
            element.style.display = '';
            element.hidden = false;
        }
    },

    /**
     * Hide an element
     * @param {HTMLElement} element 
     */
    hide(element) {
        if (element) {
            element.style.display = 'none';
            element.hidden = true;
        }
    },

    /**
     * Toggle element visibility
     * @param {HTMLElement} element 
     * @param {boolean} show 
     */
    toggle(element, show) {
        if (show) {
            this.show(element);
        } else {
            this.hide(element);
        }
    },

    /**
     * Remove element from DOM safely
     * @param {HTMLElement} element 
     */
    remove(element) {
        if (element && element.parentNode) {
            element.parentNode.removeChild(element);
        }
    },

    /**
     * Get data attribute value with fallback
     * @param {HTMLElement} element 
     * @param {string} attr - Attribute name (without 'data-' prefix)
     * @param {*} fallback - Fallback value
     * @returns {*}
     */
    getData(element, attr, fallback = null) {
        const value = element.dataset[attr] || element.getAttribute(`data-${attr}`);
        return value !== null && value !== undefined ? value : fallback;
    },

    /**
     * Check if element has a truthy attribute
     * @param {HTMLElement} element 
     * @param {string} attr 
     * @returns {boolean}
     */
    hasAttr(element, attr) {
        return element.hasAttribute(attr) && element.getAttribute(attr) !== 'false';
    },

    /**
     * Query selector within a container (scoped)
     * @param {HTMLElement} container 
     * @param {string} selector 
     * @returns {HTMLElement|null}
     */
    find(container, selector) {
        return container.querySelector(selector);
    },

    /**
     * Query selector all within a container (scoped)
     * @param {HTMLElement} container 
     * @param {string} selector 
     * @returns {NodeList}
     */
    findAll(container, selector) {
        return container.querySelectorAll(selector);
    },

    /**
     * Dispatch a custom event on an element
     * @param {HTMLElement} element 
     * @param {string} eventName 
     * @param {Object} detail - Event detail data
     */
    dispatchEvent(element, eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            bubbles: true,
            cancelable: true,
            detail
        });
        element.dispatchEvent(event);
    },

    /**
     * Generate a unique ID
     * @param {string} prefix 
     * @returns {string}
     */
    generateId(prefix = 'df') {
        return `${prefix}-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    }

};

export default DOMUtils;

