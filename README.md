
<p align="center">
<img src="https://suhk.me/assets/images/datafinder-full-logo-lg.png" width="400">
</p>
<p align="center">
An easy, configurable & modular laravel package for <a href="https://datatables.net/">Datatables</a>
</p>

## _Introduction_

**DataFinder** is an innovative and highly efficient Laravel package designed to implement advanced search, filtering, and data retrieval functionalities in your applications. It bridges the gap between complex backend queries and dynamic front-end tables, providing developers with a streamlined solution for handling large datasets seamlessly.

The package integrates effortlessly with **[DataTables](https://datatables.net/)** on the frontend while leveraging Laravel's robust **Eloquent Query Builder** on the backend. Its scalable architecture ensures high performance, making it suitable for systems with millions of records.

### **_Key Features_**

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


### **_Why Use DataFinder?_**
DataFinder simplifies the integration of **[DataTables](https://datatables.net/)** with Laravel by reducing the complexity of repetitive configurations. Its modular approach empowers developers to:
- Dynamically fetch, filter, and display data from the backend.
- Maintain scalability and performance while handling large datasets.
- Enhance user experience with responsive and interactive tables.
- Minimize development overhead with reusable and centralized configuration files.


## _Installation Guide_

Follow these steps to integrate the DataFinder package smoothly into your Laravel project.

>#### **Prerequisites**
For **DataFinder** to work properly, your project must include the following CDNs in your views:

>- **[Bootstrap](https://getbootstrap.com/)**
>- **[jQuery](https://jquery.com/)**
>- **[DataTables](https://datatables.net/)**
>- **[Select2](https://select2.org/)**
>
>These dependencies are **not included** in the package to:  
>1. **Avoid copyright claims** – We ensure compliance by not bundling third-party assets. Instead, we reference their official websites.   
2. **Maintain flexibility** – Including these libraries would require constant updates with each new release. By relying on your project’s existing dependencies, **DataFinder** remains compatible across multiple Laravel and PHP versions.  
>
>✅ **Tested Compatibility:**
>
> - **PHP:** 7.3 – 8.4
> - **Laravel:** 5.8 – 11
> 
>Make sure to include the required CDNs in your project to ensure **DataFinder** works seamlessly. 

### _Step 1: Install the Package_

Run the following command to install the package via Composer:

```bash
composer require suhk/TO_BE_DECIDED
```

### _Step 2: Add Required CDNs_

Add the following CDNs for Bootstrap, jQuery, and DataTables to the `<head>` section of your application:

```html
<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.0/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.0/js/jquery.dataTables.min.js"></script>

datatable button script
```

### _Step 3: Add Service Provider_

The package's service provider is auto-loaded upon installation. However, if it is not loaded, manually add the following entry to the `providers` array in your `config/app.php` file:

```php
SUHK\DataFinder\App\Providers\MainServiceProvider::class,
```

### _Step 4: Setup package:_
```bash
   php artisan suhk:setup-package

```

---


> # Documentation
>> #### Configuration File Breakdown

---

* ### _General Configuration_
 

Columns define the data fetched from the database and how it is processed.

#### _Structure_

```php

    'model_path' => 'YOUR_MODEL_PATH',
    'table_name' => 'YOUR_TABLE_NAME',
    'selective_columns' => false, // Boolean False means system will fetch the data with all columns of this table.
    'columns' => [], // If boolean is false then this array will be empty.

```

| Key               | Description                                                                                                         | Example Value                           |
|--------------------|---------------------------------------------------------------------------------------------------------------------|-----------------------------------------|
| `model_path`       | Namespace path to the Eloquent model used for the module.                                                          | `'App\Models\YOUR_MODEL_NAME'`  |
| `table_name`       | Name of the database table associated with the module.                                                             | `'table_name'`                    |
| `selective_columns`| Boolean. If `true`, only specified columns are fetched; if `false`, all columns in the table are retrieved.         | `true`                                  |
| `columns`| Please review column configuration section.         | N/A                                  |

---

* ### _Columns Configuration_

Columns define the data fetched from the database and how it is processed.

#### _Structure_
```php
'columns' => [
    [
        'column_name' => 'column_name_here',
        'aggregate' => true/false,
        'sum' => ['column1', 'column2'],
        'as' => 'alias_name',
    ],
],
```

#### _Keys_

| Key           | Description                                                                                       | Example Value    |
|---------------|---------------------------------------------------------------------------------------------------|------------------|
| `column_name` | Name of the column in the database table.                                                               | `'created_at'`   |
| `aggregate`   | Boolean. If `true`, aggregation is applied to the column (SUM, COUNT SUPPORTED ONLY).                        | `true/false`          |
| `sum`         | An array of columns to sum if aggregation is enabled.                                             | `['amount']`     |
| `as`          | Alias for the column in the results (used when `aggregate` or `sum` is applied).                  | `'total_amount'` |

#### _Example_

```php
'columns' => [
    [
        'column_name' => 'id',
        'aggregate' => false,
        'sum' => [],
        'as' => null,
    ],
    [
        'column_name' => 'created_at',
        'aggregate' => false,
        'sum' => [],
        'as' => 'creation_date',
    ],
],
```

---

* ### _Filters Configuration_
 

**Filters** allow you to easily manage query conditions through user input. By adding them to the configuration, you can automatically display them in the UI and integrate them into backend queries, without any code changes or manual updates

#### _Structure_

```php
'filters' => [
    [
            'id' => 'DOM_ELEMENT_ID', // .
            'name' => 'DOM_ELEMENT_NAME', // 
            'label' => 'DOM_ELEMENT_LABEL', // 
            'placeholder' => 'DOM_ELEMENT_PLACEHOLDER', // Placeholder To Display on User Interface.
            'type' => 'text | select | date | time | datetime-local', // .
            'value_type' => 'ROUTE_PARAM | PHP_VARIABLE | CUSTOM', // ROUTE_PARAM only supported with Text
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

#### _Keys_

| Key                   | Description                                                                                  | Example Value         |
|-----------------------|----------------------------------------------------------------------------------------------|-----------------------|
| `id`                  | ID value for to access from Javascript.                                                           | `'user_status'`       |
| `name`                | Name value for name attribute for forms or else.                                                        | `'user_status'`       |
| `label`               | Label To Display on User Interface.                                                                  | `'User Status'`       |
| `placeholder`               | Placeholder To Display on User Interface.                                                                  | `'Select Status'`       |
| `type`                | Input type value to render field as what. [Supports only: `text`, `select`, `date`, `time`, `datetime-local`].                                        | `'select'`            |
| `value_type`          | Field Value. use tag `'CUSTOM/ ROUTE_PARAM/ PHP_VARIABLE'` to set value to the input element.                                       | `'CUSTOM/ ROUTE_PARAM/ PHP_VARIABLE'` Note:`'ROUTE_PARAM'` is only supported by input type `text`           |
| `value`               | Incase of `CUSTOM` pass custom array of values. Incase of `ROUTE_PARAM` or `PHP_VARIABLE` view example for this section.                                                                | `[0 => 'Inactive']`   |
| `selected`          | for type `select` pass the matching key to set the default value. For `text`, pass string, for `date` pass dateString                                                  | `key` or `text` or `2024-01-21`                |
| `visibility`          | Whether the filter is displayed in the UI. Hiding a filter will not remove it from query.                                                 | `true`                |
| `filterable`          | Whether the filter is applied to the query. Hidden or not, this key determines whether the column value will be included within the query or not.                                                | `true`                |
| `filter_through_join` | If the value is true, the column will be filtered from a joined table.                                               | `true`                |
| `join_table`          | this will specify the table where the column should be looked up.                                                       | `'users'`             |
| `column_name`         | Column name in the joined table.                                                            | `'is_active'`         |
| `conditional_operator`| Conditional operator for filtering (`=`, `>=`, `<=`, `<>`).                                        | `'='`                 |

#### _Example_

```php
'filters' => [
    [
        'id' => 'user_activation_status',
        'name' => 'user_activation_status',
        'label' => 'Account Status',
        'type' => 'select',
        'value_type' => 'CUSTOM',
        'value' => [0 => 'Inactive', 1 => 'Active'],
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

* ### _Joins Configuration_


Joins define relationships between the primary table and other tables to fetch related data. To enable the join configuration for this package to retreive join table, make sure you add:

`'table_has_joins' => true, // Boolean for Joins.`

#### _Structure_
```php
'table_has_joins' => true/false, // Boolean for Joins.

'joins' => [
    'tables' => [
        [
            'join_with_table' => 'related_table',
            'reference_in_current' => 'parent_table.column',
            'conditional_sign' => '=',
            'reference_in_join' => 'related_table.column',
            'selective_columns' => true,
            'columns' => [
                [
                    'column_name' => 'column_name_here',
                    'aggregate' => true/false,
                    'sum' => [],
                    'as' => 'alias_name',
                ],
            ],
        ],
    ],
],
```

#### _Keys_

| Key                   | Description                                                                                  | Example Value            |
|-----------------------|----------------------------------------------------------------------------------------------|--------------------------|
| `table_has_joins`     | enable to retrieve data from joined tables .                                                        | `true`                |
| `join_with_table`     | Table to join with the current table.                                                        | `'users'`                |
| `reference_in_current`| Column in the current table used for the join condition.                                     | `'users.department_id'` |
| `conditional_sign`    | Operator for the join condition (`=`).                                             | `'='`                    |
| `reference_in_join`   | Column in the related table for the join condition.                                          | `'users.id'`             |
| `selective_columns`   | Whether to fetch specific columns from the joined table.                                     | `true`                   |
| `columns`   | Please review column configuration section.                                     | N/A                   |


#### _Example_

```php
'joins' => [
    'tables' => [
        [
            'join_with_table' => 'department',
            'reference_in_current' => 'users.department_id',
            'conditional_sign' => '=',
            'reference_in_join' => 'users.id',
            'selective_columns' => true,
            'columns' => [
                [
                    'column_name' => 'name',
                    'aggregate' => false,
                    'sum' => [],
                    'as' => 'user_name',
                ],
            ],
        ],
    ],
],
```

---

* ### _Table Headers_
 

Headers define how data is displayed in the frontend table.

#### _Structure_

```php
'table_headers' => [
    [
        'title' => 'Column Title',
        'data' => 'key_name',
        'column_name' => 'column_name',
        'visibility' => true,
        'searchable' => true/false,
        'search_through_join' => true/false,
        'table_name' => 'related_table',
    ],
],
```

#### _Keys_

| Key                   | Description                                                                                  | Example Value            |
|-----------------------|----------------------------------------------------------------------------------------------|--------------------------|
| `title`     | Label To Display on Table (User Interface).                                                        | `'First Name'`                |
| `data`| `Key_name` to be used from array passed [Not a db collection but a processed array] (It could be a column name or an alias name).                                     | `'first_name'` |
| `column_name`    | table column name same as in database table.                                             | `'users'`                    |
| `visibility`   | Boolean to Either show on Table (User Interface) or not.                                          | `true`             |
| `searchable`   | Boolean to Either set column for searching or not.                                     | `true`                   |
| `search_through_join`   | boolean to either search the value in parent table or one of the joined tables.                                     | `true`                   |
| `table_name`   | if `search_through_join` is `true` then add the table name (join).                                     | `true`                   |


#### _Example_

```php
'table_headers' => [
    [
        'title' => 'User Name',
        'data' => 'user_name',
        'column_name' => 'name',
        'visibility' => true,
        'searchable' => true,
        'search_through_join' => true,
        'table_name' => 'users',
    ],
],
```

---

* ### _Row Buttons_

Define custom action buttons for table rows.

#### _Example_

```php

'row_has_buttons' => false,

'table_row_buttons' => [
    [
        'label' => 'View',
        'class' => 'btn btn-info btn-sm shadow rounded-0',
        'style' =>  null, // 'bgColor: #000; color: #fff;'
        'route' => [
            'name' => 'route.name',
            'params' => ['user_type', 'id', 'user_department'],
        ],
        'icon' => 'fa fa-eye',
        'tooltip' => 'View',
    ],
],
```
