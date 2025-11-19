# Changelog

>All notable changes to this project will be documented in this file.
>
>The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/).

---

### [v2.1.0] - 2025-11-19

#### Added
- Introducing grouped paramters in where clause.
- `column_name` value added back to support value generating through laravel eloquent model's append or mutators to support proper search and filters over them.
  - For example: a column in database is `first_name` or `last_name` but on frontend using appends you create custom key-pair `full_name`. To support DB search over such values or filter over such value, column_name is added to define the actual column from DB to pass from frontend in filter search like `Filter by Last Name` or `Filter by First Name` etc.
- Custom datatable logic is refractored as well where the custom config is not required not, simply define if the custom routes are allowed or not, if yes, then add custom routes to data handler (controller) and the export handler (controller). Export handler is still in progress. Rightnow if custom route is enabled, then export is disabled.
  - `allow_custom_route`, `custom_data_route`, `custom_export_route`

#### Changed
- Refractored backend code to support package main functionality more effectively with less callable methods.
- Refractored frontend code to help user integrating package more effectively by making the `include` from 2 to 1
  - Previously:
    - `@include('datafinder::filters', ['config_file_name' => 'PATH_TO_YOUR_CONFIG_FILE_NAME'])`
    - `@include('datafinder::datatable', ['config_file_name' => 'PATH_TO_YOUR_CONFIG_FILE_NAME'])`
  - New:
    - `@include('datafinder::container', ['config_file_name' => 'PATH_TO_YOUR_CONFIG_FILE_NAME'])` and this will include both the datatable and the filters container.
- Refractored javascript helper classes to support the frontend integration more effectively by reducing the repitive data declare and the callable funtions to get the data.

#### Fixed
- Bugs and fixes for handling values coming through laravel eloquent model's append or mutators.
- Added more validation for required fields such as `name` in filters. If not provided, package will not render the field and will notify user to add the name attribute first.

#### Documentation
- None.

#### Contributor(s)

Following are the contributor(s) to this release:
- @SyedUsamaHaiderKazmi (Owner)

---

### [v2.0.3] - 2025-10-14

#### Added
- None.

#### Changed
- None.

#### Fixed
- Bugs and fixes in data count through queries and table styles.

#### Documentation
- None.

#### Contributor(s)

Following are the contributor(s) to this release:
- @SyedUsamaHaiderKazmi (Owner)

---

### [v2.0.2] - 2025-10-13

#### Added
- Introducing custom datatable styles using `yield('df-styles')`.
  - Custom styles on table.
- For defaults, `count()` works fine, but when there is raw query joins and raw subquery joins `count()` requires a `binding` strategy to the eloquent query builder to ensure you get the correct count.

#### Changed
- None.

#### Fixed
- Validation on table aggregate/ conditional values.
- Bugs and fixes in integration flow.

#### Documentation
- Discontinued in this package repository. Visit [https://datafinder.suhk.me/](Documentation)

#### Contributor(s)

Following are the contributor(s) to this release:
- @SyedUsamaHaiderKazmi (Owner)

---

### [v2.0.1] - 2025-09-14

#### Added
- None.

#### Changed
- None.

#### Fixed
- check if visibility key exists or not before checking its value in `select.blade.php`

#### Documentation
- None

#### Contributor(s)

Following are the contributor(s) to this release:
- @SyedUsamaHaiderKazmi (Owner)

---

### [v2.0.0] - 2025-09-01

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
- Now supports conditional functions like where, groupBy, having including aggregate support for primary table.
- Added an option to enable and disable the text-based search in datatable in frontend configuration file.

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
- `SubQueryTrait` has now convert to `DFQueryBuilder` class to cover not just the creation of query for subqueries-joins but also for primary query now using one single file following the code reusability approach.
  - Implemented new builder to `JoinTrait` and `DataFinderService` 
- Enhanced `DataFinderTrait` to cover only the functions that are functionality for Datafinder but not the core features
- Enhanced `ConfigParser` by adding a new function which will return all the nessecary items required by the package configuration in single call, saving memory utilizations and recalling of the config file repitition.

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

#### Added
- None

#### Changed
- None

### Fixed
- called `url()` function instead of `asset()` to declase js files in frontend.

#### Documentation
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
