/**
 * DataFinder Package - Main Entry Point
 * 
 * This file serves as the main entry point for the DataFinder JavaScript package.
 * It exports all public modules for use in applications.
 * 
 * Usage (ES Modules):
 *   import DataFinder from '/vendor/datafinder/assets/js/index.js';
 *   import { DFConstants } from '/vendor/datafinder/assets/js/index.js';
 * 
 * Usage (Global/Window):
 *   After including the script, access via window.DataFinder
 * 
 * @package SUHK\DataFinder
 * @author Syed Usama Haider Kazmi
 * @since 2.0.0
 */

// ==========================================================================
// CORE EXPORTS
// ==========================================================================

// Main DataFinder factory (default export)
export { default } from './DataFinder.js';

// Named exports for granular access
export { default as DataFinder } from './DataFinder.js';
export { default as DFConstants } from './core/Constants.js';

// ==========================================================================
// CORE CLASSES (for advanced usage)
// ==========================================================================

export { default as DataFinderInstance } from './core/Instance.js';
export { default as FilterManager } from './core/FilterManager.js';
export { default as ErrorHandler } from './core/ErrorHandler.js';
export { default as ExportManager } from './core/ExportManager.js';
export { default as ToolbarManager } from './core/ToolbarManager.js';

// ==========================================================================
// UTILITIES
// ==========================================================================

export { default as DOMUtils } from './utils/DOMUtils.js';

// ==========================================================================
// SERVICES
// ==========================================================================

export { default as Exporter } from './export/services/Exporter.js';

