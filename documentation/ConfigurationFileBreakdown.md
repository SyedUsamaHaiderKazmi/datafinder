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
    'exportable' => false, // if true, then the export functionality will be activated.
    'exportable_by_chunk' => false, // if exportable is true, then this value can be set true, Purpose is to optimized the exportable for larage datasets.
    'exportable_chunk_size' => null, // if 'exportable_by_chunk' is false, then this value will be null
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
            'left_side' => 'parent_table.column',
            'right_side' => 'related_table.column',
            'alias' => null,
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
| `left_side`| Syntax is this value will be put to left side of = in join query.                                    | `'table.column'` |
| `right_side`   | Syntax is this value will be put to right side of = in join query                                          | `'table.column'`             |
| `alias`   | Column in the related table for the join condition.                                          | `'users.id'`             |
| `selective_columns`   | Whether to fetch specific columns from the joined table.                                     | `true`                   |
| `columns`   | Please review column configuration section.                                     | N/A                   |


##### _Example_

```php
'joins' => [
    'tables' => [
        [
            'join_with_table' => 'department',
            'left_sode' => 'users.department_id',
            'right_side' => 'departments.id',
            'alias' => null,
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
        'visibility' => true/false,
        'searchable' => true/false,
        "exportable" => true/false, // Boolean to Either set column for exporting or not. Set false and the column will not be exported.
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
| `exportable`   | Boolean to Either set column to be included within export or not.                                     | `true`                   |
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
        'exportable' => true,
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