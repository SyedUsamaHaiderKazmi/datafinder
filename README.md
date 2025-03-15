
<p align="center">
<img src="https://suhk.me/assets/images/datafinder-full-logo-lg.png" width="400">
</p>
<p align="center">
An easy, configurable & modular laravel package for <a href="https://datatables.net/">Datatables</a>
</p>

## _Introduction_

**DataFinder** is an innovative and highly efficient Laravel package designed to implement advanced search, filtering, and data retrieval functionalities in your applications. It bridges the gap between complex backend queries and dynamic front-end tables, providing developers with a streamlined solution for handling large datasets seamlessly.

The package integrates effortlessly with **[DataTables](https://datatables.net/)** on the frontend while leveraging Laravel's robust **Eloquent Query Builder** on the backend. Its scalable architecture ensures high performance, making it suitable for systems with millions of records.

#### **_Key Features_**

🔍 **Dynamic Multi-Table Search**

- Search seamlessly across multiple database tables using **dynamic JOINs**, retrieving relevant data efficiently.  

🔎 **Advanced Multi-Filter Search**

- Apply multiple filters at once, each supporting multiple values, enabling highly refined search results.  
- Combine **filter-based search** with **text-based search**, working across single or multiple tables dynamically.  

⚡ **Flexible Table Configurations**

- Fetch data from any table and define relationships with **efficient JOIN operations**.  
- Supports dynamic configurations for **searchable and filterable columns** in a single file per module.  

🚀 **Easy Module Setup**

- Configure columns, filters, table configuration, database table and their joins, data to display and custom row actions with a single configuration file per module, making setup intuitive and reusable.  

🎯 **Custom Row Actions**

- Add interactive buttons for specific actions within the table, ensuring seamless user interaction.  

📈 **Optimized for Performance & Scalability**

- Designed to handle **millions of records** while maintaining fast and efficient search performance.


#### **_Why Use DataFinder?_**
DataFinder simplifies the integration of **[DataTables](https://datatables.net/)** with Laravel by reducing the complexity of repetitive configurations. Its modular approach empowers developers to:

- Dynamically fetch, filter, and display data from the backend.
- Maintain scalability and performance while handling large datasets.
- Enhance user experience with responsive and interactive tables.
- Minimize development overhead with reusable and centralized configuration files.


<hr>
<center>
    <h1>Documentation</h1>
    <a href="#installation-guide">Installation Guide</a>
    |
    <a href="#usage-guide">Usage</a>
    |
    <a href="#configuration-file-breakdown">Configuration File Breakdown</a>
</center>

## _Installation Guide_

Follow these steps to integrate the DataFinder package smoothly into your Laravel project.


#### _Step 1: Install the Package_

Run the following command to install the package via Composer:

```bash
composer require suhk/datafinder-laravel
```

#### _Step 2: Add Required CDNs_


For **DataFinder** to work properly, your project must include the following CDNs in your views:

>- **[Bootstrap](https://getbootstrap.com/)**
>- **[jQuery](https://jquery.com/)**
>- **[DataTables](https://datatables.net/)**
>- **[Select2](https://select2.org/)**

These dependencies are **not included** in the package to:  

>1. **Avoid copyright claims** – We ensure compliance by not bundling third-party assets. Instead, we reference their official websites.   
2. **Maintain flexibility** – Including these libraries would require constant updates with each new release. By relying on your project’s existing dependencies, **DataFinder** remains compatible across multiple Laravel and PHP versions.  

###### ✅ **Tested Compatibility:**

> - **PHP:** 7.3 – 8.4
> - **Laravel:** 5.8 – 11
 
Make sure to include the required CDNs in your project to ensure **DataFinder** works seamlessly. 

#### _Step 3: Add Service Provider_

The package's service provider is auto-loaded upon installation. However, if it is not loaded, manually add the following entry to the `providers` array in your `config/app.php` file:

```php
SUHK\DataFinder\App\Providers\MainServiceProvider::class,
```

#### _Step 4: Setup package:_

```bash
   php artisan suhk:setup-package
```

- Load route & views to autoload
- Publish required assets for package to public directory
- Publish the [_Sample Configuration_](src/config/filter_configurations.php) file to following directory structure:
    - **`app/Helpers/DataFinder/sample_configuration.php`** 
        - This is the [_Sample Configuration_](src/config/filter_configurations.php) file used to configure the `datafinder` package. It contains default settings and structure that can be customized as needed for your application.
        - [_See file breakdown below_](#configuration-file-breakdown)

---

## _Usage Guide_

The following instructions will guide you through the necessary steps to implement and customize the data table and filter functionality within your Laravel project.

* #### _Basic Setup_

To get started, you need to include the necessary blade components in the relevant view where you wish to display the data table and filters (typically your index or a specific view for the module).

###### **_Include the Data Table and Filters:_**

In your blade view, add the following includes:

```php
// for filters
@include('datafinder::filters', ['config_file_name' => 'YOUR_CONFIG_FILE_NAME'])
// for datatables
@include('datafinder::datatable', ['config_file_name' => 'YOUR_CONFIG_FILE_NAME'])
```
###### **_Explanation:_**

| Key          | Description                                                                                                                                                                          |
| :----------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `config_file_name` | key should correspond to the configuration file name you've set up for the data table in the module you're working with. |


> ###### **Note:** These includes will render the user interface (UI) for the package, displaying both the filters and the data table. The look and feel of the UI are customizable and can be adjusted by the end user as needed.


* #### _Advance Usage_


In some cases, you might need to pass dynamic data such as values from the database into the filters instead of using hardcoded values in the configuration file. Since you cannot directly utilize Eloquent models within the configuration file, the package provides a way to parse custom PHP variables.

###### **_Passing Custom PHP Variables:_**

To handle complex filter cases, you can pass dynamic data (e.g., values from the database) to the filters using the phpVariables key. Here’s an example of how to pass custom variables to the view:

```php
@include('datafinder::filters', [
    'config_file_name' => 'YOUR_CONFIG_FILE_NAME', 
    'phpVariables' => [
        'languages' => $languages, 
        'countries' => $countries,
        'hasValue' => true,
        'textValue' => 'Your Text Value',
    ]
])

```

| Key          | Description                                                                                                                                                                          | Value |
| :----------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | :---- |
| `phpVariables` | This key is used to pass custom PHP variables (e.g., an array of languages or countries) to the package. The package will access these variables to populate the filters dynamically | N/A   |

In your config file, reference the dynamic PHP variables passed from the view. For example:

```php
[
    ...
    [
        ...
        "name": "Languages",
        "value_type": "PHP_VARIABLE",
        "value": "languages",
        ...
    ],
    [
        ...
        "name": "Countries",
        "value_type": "PHP_VARIABLE",
        "value": "countries",
        ...
    ]
    ...
]

```

###### **_Explanation:_**

| Key        | Description                                                                                                        | Value |
| :--------- | :----------------------------------------------------------------------------------------------------------------- | :---- |
| `value_type` | Set this to `PHP_VARIABLE` to indicate that the value for this filter is being passed dynamically from PHP variables | N/A   |
| `value`      | The key that corresponds to the PHP variable passed in the view (e.g., languages, countries).                      | N/A   |

---

## _Configuration File Breakdown_

The configuration file is structured into 6 distinct sections, each serving a specific purpose to configure the table and its associated features. Below, you'll find a detailed explanation of each section, along with the purpose and how to use them effectively.

* ##### [_Section A: Frontend Configuration_](#section-a-frontend-configuration-1)
* ##### [_Section B: Database Configuration_](#section-b-database-configuration-1)
    * ##### [_Section B-1: Columns Configuration_](#section-b-1-columns-configuration-1)
* ##### [_Section C: Filters Configuration_](#section-c-filters-configuration-1)
* ##### [_Section D: Joins Configuration_](#section-d-joins-configuration-1)
* ##### [_Section E: Table Headers_](#section-e-table-headers-1)
* ##### [_Section F: Row Buttons_](#section-f-row-buttons-1)

---

* #### _Section A: Frontend Configuration_


In this section, users define settings that control how the table will be displayed on the frontend. This is crucial when a page contains multiple tables to avoid conflicts between them.

##### _Structure_

```php
    'dom_table_id' => 'YOUR_TABLE_ID',
    'responsive' => false, // to make datatable responsive for better view for increased columns and small sized screens
    'default_per_page' => null, // if remains empty, by default will be set to 10
    'allow_per_page_options' => false,
    'per_page_options' => [],

```

---


* #### _Section B: Database Configuration_


This section defines the base model and the base table used by the data table. It allows the user to configure which columns to retrieve from the database and whether to fetch all columns or only specific ones.

##### _Structure_

```php
    'model_path' => 'YOUR_MODEL_PATH',
    'table_name' => 'YOUR_TABLE_NAME',
    'selective_columns' => false, // Boolean False means system will fetch the data with all columns of this table.
    'columns' => [], // If boolean is false then this array will be empty.

```
##### _Keys_

| Key               | Description                                                                                                         | Example Value                           |
|--------------------|---------------------------------------------------------------------------------------------------------------------|-----------------------------------------|
| `model_path`       | Namespace path to the Eloquent model used for the module.                                                          | `'App\Models\YOUR_MODEL_NAME'`  |
| `table_name`       | Name of the database table associated with the module.                                                             | `'table_name'`                    |
| `selective_columns`| Boolean. If `true`, only specified columns are fetched; if `false`, all columns in the table are retrieved.         | `true`                                  |
| `columns`| Please review column configuration section.         | N/A                                  |

---

* #### _Section B-1: Columns Configuration_

Columns define the data fetched from the database and how it is processed.

##### _Structure_
```php
'columns' => [
    [
        'type' => 'DEFAULT', // RAW | DEFAULT 
        'column_name' => '*',
        'alias' => null, 
    ],
],
```

##### _Keys_

| Key           | Description                                                                                       | Example Value    |
|---------------|---------------------------------------------------------------------------------------------------|------------------|
| `type` | Required field for users to set either `RAW` if custom values required or `DEFAULT` if just the exact column is required.                                                               | `'created_at'`   |
| `column_name`   | If `DEFAULT`add column name. If `RAW` then add raw query                         | `DEFAULT`: `*/ column_name`<br> `RAW`: `DATE_FORMAT(tablename.column_name, "%W %d, %M %Y") AS formatted_date`          |
| `alias`         | If `RAW` then alias should be null. For `DEFAULT` type, alias is mendatory if column name is not `*`.                                             | `['amount']`     |

##### _Example_

```php
'columns' => [
    [
        'type' => 'DEFAULT', // RAW | DEFAULT 
        'column' => '*',
        'alias' => null,
    ],
    [
        'type' => 'RAW', // RAW | DEFAULT 
        'column' => 'DATE_FORMAT(tablename.column_name, "%W %d, %M %Y") AS formatted_date',
        'alias' => null,
    ],
],
```

---

* #### _Section C: Filters Configuration_
 

**Filters** allow you to easily manage query conditions through user input. In this section, users can define multiple filters for their module, including how they interact with the AJAX data retrieval process.

##### _Structure_

```php
'filters' => [
    [
            'id' => 'DOM_ELEMENT_ID', // .
            'name' => 'DOM_ELEMENT_NAME', // 
            'label' => 'DOM_ELEMENT_LABEL', // 
            'placeholder' => 'DOM_ELEMENT_PLACEHOLDER', // Placeholder To Display on User Interface.
            'type' => 'text | select | date | time | datetime-local', // .
            'value_type' => 'ROUTE_PARAM | QUERY_PARAM | PHP_VARIABLE | CUSTOM', // ROUTE_PARAM only supported with Text
            'value' => 'DOM_ELEMENT_VALUE', // Field Default Value. Either provide custom value or use tag "ROUTE_PARAM
            'selected' => 'DOM_ELEMENT_DEFAULT_VALUE', // Field Default Value. Either provide custom value or use tag "ROUTE_PARAM
            'visibility' => true, // Boolean to Either show in Filters (User Interface) or not.
            'filterable' => true, // Boolean to Either show in Filters (User Interface) or not.
            'filter_through_join' => false, // Boolean to Either set column for filtering through joined table or not.
            'join_table' => null, // Joined-Table name for Joined filters.
            'column_name' => 'YOUR_DATABASE_TABLE_COLUMN', // Joined-Column name from Joined-Table for Joined filters.
            'conditional_operator' => '= | >= | <= | <>', // Conditional operator to use in where caluse for filters.
        ],
],
```

##### _Keys_

| Key                   | Description                                                                                  | Example Value         |
|-----------------------|----------------------------------------------------------------------------------------------|-----------------------|
| `id`                  | ID value for to access from Javascript.                                                           | `'user_status'`       |
| `name`                | Name value for name attribute for forms or else.                                                        | `'user_status'`       |
| `label`               | Label To Display on User Interface.                                                                  | `'User Status'`       |
| `placeholder`               | Placeholder To Display on User Interface.                                                                  | `'Select Status'`       |
| `type`                | Input type value to render field as what. [Supports only: `text`, `select`, `date`, `time`, `datetime-local`].                                        | `'select'`            |
| `value_type`          | Field Value. use tag `'CUSTOM/ ROUTE_PARAM/ QUERY_PARAM/ PHP_VARIABLE'` to set value to the input element.                                       | `'CUSTOM/ ROUTE_PARAM/ QUERY_PARAM/ PHP_VARIABLE'` Note:`'ROUTE_PARAM'` is only supported by input type `text`           |
| `value`               | Incase of `CUSTOM` pass custom array of values. Incase of `ROUTE_PARAM` or `PHP_VARIABLE` view example for this section.                                                                | `[0 => 'Inactive']`   |
| `selected`          | for type `select` pass the matching key to set the default value. For `text`, pass string, for `date` pass dateString                                                  | `key` or `text` or `2024-01-21`                |
| `visibility`          | Whether the filter is displayed in the UI. Hiding a filter will not remove it from query.                                                 | `true`                |
| `filterable`          | Whether the filter is applied to the query. Hidden or not, this key determines whether the column value will be included within the query or not.                                                | `true`                |
| `filter_through_join` | If the value is true, the column will be filtered from a joined table.                                               | `true`                |
| `join_table`          | this will specify the table where the column should be looked up.                                                       | `'users'`             |
| `column_name`         | Column name in the joined table.                                                            | `'is_active'`         |
| `conditional_operator`| Conditional operator for filtering (`=`, `>=`, `<=`, `<>`).                                        | `'='`                 |

##### _Example_

```php
'filters' => [
    [
        'id' => 'user_activation_status',
        'name' => 'user_activation_status',
        'label' => 'Account Status',
        'placeholder' => 'Select Status',
        'type' => 'select',
        'value_type' => 'CUSTOM',
        'value' => [0 => 'Inactive', 1 => 'Active'],
        'selected' => 1,
        'visibility' => true,
        'filterable' => true,
        'filter_through_join' => true,
        'join_table' => 'users',
        'column_name' => 'is_active',
        'conditional_operator' => '=',
    ],
],
```

---

* #### _Section D: Joins Configuration_


Joins define relationships between the primary table and other tables to fetch related data. To enable the join configuration for this package to retreive join table, make sure you add:

`'table_has_joins' => true, // Boolean for Joins.`

##### _Structure_

```php
'table_has_joins' => true/false, // Boolean for Joins.

'joins' => [
    'tables' => [
        [
            'join_with_table' => 'related_table',
            'reference_in_current' => 'parent_table.column',
            'reference_in_join' => 'related_table.column',
            'selective_columns' => true,
            'columns' => [
                [
                    'type' => 'DEFAULT', // RAW | DEFAULT 
                    'column' => '*',
                    'alias' => null,
                ],
                [
                    'type' => 'RAW', // RAW | DEFAULT 
                    'column' => 'DATE_FORMAT(tablename.column_name, "%W %d, %M %Y") AS formatted_date',
                    'alias' => null,
                ],
            ],
        ],
    ],
],
```

##### _Keys_

| Key                   | Description                                                                                  | Example Value            |
|-----------------------|----------------------------------------------------------------------------------------------|--------------------------|
| `table_has_joins`     | enable to retrieve data from joined tables .                                                        | `true`                |
| `join_with_table`     | Table to join with the current table.                                                        | `'users'`                |
| `reference_in_current`| Column in the current table used for the join condition.                                     | `'users.department_id'` |
| `reference_in_join`   | Column in the related table for the join condition.                                          | `'users.id'`             |
| `selective_columns`   | Whether to fetch specific columns from the joined table.                                     | `true`                   |
| `columns`   | Please review column configuration section.                                     | N/A                   |


##### _Example_

```php
'joins' => [
    'tables' => [
        [
            'join_with_table' => 'department',
            'reference_in_current' => 'users.department_id',
            'reference_in_join' => 'users.id',
            'selective_columns' => true,
            'columns' => [
                [
                    'type' => 'DEFAULT', // RAW | DEFAULT 
                    'column' => '*',
                    'alias' => null,
                ],
                [
                    'type' => 'RAW', // RAW | DEFAULT 
                    'column' => 'DATE_FORMAT(department.created_at, "%W %d, %M %Y") AS formatted_date',
                    'alias' => null,
                ],
            ],
        ],
    ],
],
```

---

* #### _Section E: Table Headers_
 

This section is where users define the columns they want to display in the data table. It’s especially useful when dealing with joins, as it allows the user to specify which table each column belongs to, ensuring the correct columns are shown and properly searchable.

##### _Structure_

```php
'table_headers' => [
    [
        'title' => 'Column Title',
        'data' => 'key_name',
        'visibility' => true,
        'searchable' => true/false,
        'column_name' => 'column_name',
        'search_through_join' => true/false,
        'table_name' => 'related_table',
    ],
],
```

##### _Keys_

| Key                   | Description                                                                                  | Example Value            |
|-----------------------|----------------------------------------------------------------------------------------------|--------------------------|
| `title`     | Label To Display on Table (User Interface).                                                        | `'First Name'`                |
| `data`| `Key_name` to be used from array passed [Not a db collection but a processed array] (It could be a column name or an alias name).                                     | `'first_name'` |
| `visibility`   | Boolean to Either show on Table (User Interface) or not.                                          | `true`             |
| `searchable`   | Boolean to Either set column for searching or not.                                     | `true`                   |
| `column_name`    | table column name same as in database table.                                             | `'users'`                    |
| `search_through_join`   | boolean to either search the value in parent table or one of the joined tables.                                     | `true`                   |
| `table_name`   | if `search_through_join` is `true` then add the table name (join).                                     | `true`                   |


##### _Example_

```php
'table_headers' => [
    [
        'title' => 'User Name',
        'data' => 'user_name',
        'visibility' => true,
        'searchable' => true,
        'column_name' => 'name',
        'search_through_join' => true,
        'table_name' => 'users',u
    ],
],
```

---

* #### _Section F: Row Buttons_

This section allows users to define dynamic action buttons for each row in the data table. These buttons can be customized for any action the user needs, such as viewing details, editing, or deleting records.To enable the button configuration for this package to route buttons, make sure you add:

`'row_has_buttons' => true,`

##### _Structure_

```php
'table_row_buttons' => [
    [
        'label' => 'BTN_LABEL',
        'class' => 'BTN_CLASS',
        'style' =>  null, // 'bgColor: #000; color: #fff;'
        'icon' => 'YOUR_BTN_ICON_CLASS',
        'tooltip' => 'YOUR_BTN_TOOLTIP',
        'route' => [
            'name' => 'route.name',
            'params' => [ // only params from end results (data after query) are supported
                [
                    'param_key' => 'YOUR_PARA<_KEY',
                    'data_key' => 'YOU_DATA_KEY_FROM_DATA',
                ],
            ], // params defined for url in url registry
            'additional_params' => [
                [
                    'param_key' => 'YOUR_PARA<_KEY',
                    'data_key' => 'YOU_DATA_KEY_FROM_DATA',
                ],
            ], // additional params to pass through with required params such as query params
        ],
    ],
],
```

##### _Keys_

| Key                                   | Description                                                | Value                                |
| :------------------------------------ | :--------------------------------------------------------- | :----------------------------------- |
| label                                 | This key is used to pass label for the button              | View/ Edit                           |
| class                                 | This key is used to pass the classes for the button        | btn btn-info btn-sm shadow rounded-0 |
| style                                 | This key is used to pass the custom styling for the button | bgColor: #000; color: #fff;          |
| icon                                  | This key is used to pass the classes for the button        | btn btn-info btn-sm shadow rounded-0 |
| tooltip                               | This key is used to pass the custom styling for the button | Action Button: View                  |
| route                                 |                                                            | N/A                                  |
| route[].name                          |                                                            | route.show                           |
| route[].params                        |                                                            | []                                   |
| route[].params[].param_key            |                                                            | id                                   |
| route[].params[].data_key             |                                                            | id                                   |
| route[].additional_params             |                                                            | []                                   |
| route[].additional_params[].param_key |                                                            | uuid                                 |
| route[].additional_params[].data_key  |                                                            | user_id                              |


##### _Example_

```php

'row_has_buttons' => true,

'table_row_buttons' => [
    [
            'label' => 'View',
            'class' => 'btn btn-info btn-sm shadow rounded-0',
            'style' =>  null, // 'bgColor: #000; color: #fff;'
            'icon' => 'fa fa-eye',
            'tooltip' => 'View',
            'route' => [
                'name' => 'route.show', // route_name in url registry
                'params' => [
                    [
                        'param_key' => 'id',
                        'data_key' => 'id',
                    ],
                ], // params defined for url in url registry
                'additional_params' => [
                    [
                        'param_key' => 'uuid',
                        'data_key' => 'user_id',
                    ],
                ], // additional params to pass through with required params such as query params
            ],
        ],
        [
            'label' => 'Edit',
            'class' => 'btn btn-warning btn-sm shadow rounded-0',
            'style' =>  null, // 'bgColor: #000; color: #fff;'
            'icon' => 'fa fa-pencil',
            'tooltip' => 'View',
            'route' => [
                'name' => 'route.edit', // route_name in url registry
                'params' => [
                    [
                        'param_key' => 'id',
                        'data_key' => 'id',
                    ],
                ], // params defined for url in url registry
                'additional_params' => [
                    [
                        'param_key' => 'uuid',
                        'data_key' => 'user_id',
                    ],
                ], // additional params to pass through with required params such as query params
            ],
        ],
],
```
