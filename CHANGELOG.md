# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

---

## [Unreleased]

### Added
- None

### Changed
- None

### Fixed
- None

### Documentation
- None

### Contributor(s)
- None

---

## [v1.1.0] - 2025-05-25

#### Added

* **Export Support**: XLSX, XLS, and CSV export functionality with real-time download progress tracking.
* **Exporter JS Module**: Handles frontend export operations with validation and user feedback.
* **Toolbar Enhancements**: Export and Reload buttons added next to the DataTable search input.
* **Loader SVGs**: Visual feedback for toolbar actions.
* **DataLayerService**: New backend abstraction layer for query logic, supporting DataFinder and Validator traits.
* **Export Routes & Config**: Dedicated routes and configurable options published for Laravel integration.

#### Changed

* Updated config parser to support exportable event functions.
* Integrated frontend validation for export errors (consistent with DataTable error handling).

#### Fixed

* General bug fixes and stability improvements related to DataTable rendering and interactions.

#### Documentation

* Added documentation for:

  * Custom DataTable Integration
  * Export System (frontend/backend/config)

#### Contributors


Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

---

## [1.0.0] - 2025-04-01

#### Added

- Official stable release of DataFinder.
- **Dynamic Multi-Table Search**: Perform efficient searches across multiple tables using intelligent JOIN operations.
- **Comprehensive Filtering**: Support for multi-value filters across related tables and text-based querying.
- **Flexible Table Configurations**: Define table sources and JOINs via modular configs.
- **Easy Module Setup**: Single-file configuration per module to manage columns, filters, and relationships.
- **Custom Row Actions**: Add interactive buttons tied to row-specific logic.
- **Custom Table Definitions**: Support for injecting custom buttons using package-defined methods without modifying core.
- **Scalability**: Optimized for handling millions of records with fast response times.
- **Initial Setup Command**: `php artisan datafinder:setup` to scaffold config and prepare usage.
- Composer support: `composer require suhk/datafinder-laravel`.

#### Changed
- None

#### Fixed
- None

#### Documentation
- Initial release documentation including setup, configuration, and usage guides published on GitHub.

#### Contributor(s)

Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)
