# Changelog

>All notable changes to this project will be documented in this file.
>
>The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

---

### _[Unreleased]_

#### Added
- None

#### Changed
- None

#### Fixed
- None

#### Documentation
- None

#### Contributor(s)
- None

---

### _[Unreleased]_

#### Added
- Command-based configuration generation using a stub-driven strategy to maintain a cleaner file hierarchy and prevent bloated single configuration files. A new `AddNewModule` command (located in the Commands folder) allows developers to generate module-specific configuration files by providing interactive input. Each execution of the command will create a dedicated configuration file using predefined stubs in appropriate folders.
  - Command: `php artisan datafinder:add-new-module`
- A general stub handler helper to have reuseable methods to generate and publish files through stubs.
- Added functions in `ConfigGlobal.php`
  - `getPath`
  - `createDirectoryIfNotExist`
- Added the buttons column in table on frontend as a default column but based on the option in `configuration file`.
- Improved validation for filters during data retrieval to exclude empty values and unselected fields from the filter object. This prevents ambiguity when searching or filtering against empty or null values.
- RefreshPackage command is added in the package to allow users to update package assets (publishable) upon new release using command.
  - Command: `php artisan datafinder:assets-refresh`

#### Changed
- File name:
  - `DataLayerService.php` to `DataFinderService.php`.
- Function in ConfigGlobal.php:
    - `validateConfigFile`: change the way, path was genertaed and used a new reuseable function called getPath.
- Function in `ConfigParser.php`:
  - `getTableColumnsConfiguration`: added the orderable key in the mapping callback to return orderable status for columns.
- Function name in `DataSearchController.php`:
  - `liveSearchTableRender` to `data`.
- Renamed key for buttons in records collection:
  - `options` to `actions`
- Renamed route name to get data in the datatable:
  - `liveSearchTableRender` to `df/data`
- Renamed route name to export data:
  - `export/init` to `df/export/init`
- Updated new routes in package's datatable support files in js for data fetch and export.
- Renamed Code of Conduct.md file.

#### Fixed
- If no default value is given for filter type input text, then ask the value from end user to input
- Validation message variable name fix from `file_not_exisit_message` to `file_not_exist_message`
- fixed bugs in stubs structure and their file includes.

#### Documentation
- None

#### Contributor(s)

Following are the contributor(s) to this release:
- @SyedUsamaHaiderKazmi (Owner)

---

## [v1.2.0] - 2025-06-03

### Added
- SubQuery Joins to handle joins through a sub query including aggregate support for sub query joins.
  - A new way to define the conditional clauses of where, groupBy or having ensuring of every single condition to be supported by the package which laravel offers in eloquent builder.
- A new way to add joins within the datafinder supporting not just one type of join (previously leftJoin) but all types of joins provided by laravel builder.
- A new way to define multiple On conditions for joins. Not just one but now a user can define "On" or "orOn" conditional references for joins.
- Added a Join trait handeling the new additions of joins feature more robust and effectively.
- Added a SubQuery trait to handle the subquery instance for not just subQueryJoins but also the subQuerySelects

### Changed
- Moved joins processing from DataFinder's parent trait to sub trait as a support trait to enhance the implementation of whole process while ensuring the process more readable.

### Fixed
- DataFinder's export functionality to be supported by the new implementation of joins ensuring while export, the joins are implemented as intended by the user through the package.

### Documentation
- Updated joins documentation with new joins structure.

### Contributor(s)
- @SyedUsamaHaiderKazmi (Owner)

---

## [v1.1.1] - 2025-05-30

#### Added

- None

#### Changed

* **JOINS** following configuration changes have been made:
  * it has been made more clear to what will be on left side of = and what will be on right side of = in Join query.
  * Added `alias` compatibility to the config to allow users to define alias for their joined tables.

#### Fixed

* Implementation of responsive class to datatable DOM element through configuration file.

#### Documentation

* Updated documentation for:

  * **Joins** changes made in this patch release
  * Added credits to README.md file for all 3rd party integrations.

#### Contributors


Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

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
