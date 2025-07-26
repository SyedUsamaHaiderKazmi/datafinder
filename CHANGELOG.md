# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

---

## [v1.2.5] - 2025-07-26

### Added
- None

### Changed
- None

### Fixed
- System were submitting the values from filters even if they are empty, making datafinder search for empty values, making it difficult for the data to fetched in case of input elements, fixed the bugs in JS where we initialize the object.

### Documentation
- None

### Contributor(s)
Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

---

## [v1.2.4] - 2025-07-25

### Added
- None

### Changed
- None

### Fixed
- Input type elements faced bugs of not rendering properly unless the `data` key is not set as `''` or to some value instead of `null`. Whereas input element is for to get the input from user. So it created the bug which is now resolved in this release.
- fixed the `Page Length` menu in datatables as they were not working before and also fixed if the value for page length is empty, do not crash the integration but set the default value as `''`.

### Documentation
- README.md Changes:
  - Update the wrong `yield` name in guide. It was mistakenly wrote as `yield('datafinder-scriots')`. Correct value was `yield('df-scripts')`.

### Contributor(s)
Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

---

## [v1.2.3] - 2025-07-15

### Added
- None

### Changed
- `@section(scripts)` to `@section(datafinder-scripts)`

### Fixed
- file include paths in js assets.
- Spinner color was having problem, in some cases if the `bg-color` was light, and the spinner color is also light then it was giving a bad feeling, so i updated the css to inverse the colors if the bg is light, then color is dark and vise versa.

### Documentation
- README.md Changes:
  - Added a line that datatable and its button extension CDNs are required for the package to operate properly.
  - Added a step to guide users on how to add datafinder required scripts in their laravel views using `yield` directive.
  - Refractored the steps in documentation (README.md) file and added extra steps.

### Contributor(s)
Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

---

## [v1.2.2] - 2025-07-10

### Added
- None

### Changed
- None

### Fixed
- called `url()` function instead of `asset()` to declase js files in frontend.

### Documentation
- None

### Contributor(s)
Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

---

## [v1.2.1] - 2025-07-10

### Added
- None

### Changed
- None

### Fixed
- A `...` saperator was added within the joins configuration, creating a crash in the package flow.
- A wrong variable name [used to show message from constants] was used in else condition for frontend validation in filters blade file for validating if the file (config file) exists or not.

### Documentation
- None

### Contributor(s)
Following are the contributor(s) to this release:

* @SyedUsamaHaiderKazmi (Owner)

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
