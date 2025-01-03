<?php
return [

    /*
    ---------------------------------------------------------------------------
    |                                    SAMPLE FILE
    |--------------------------------------------------------------------------
    | DUMMY FORMAT FOR ALL OPTIONS. THIS IS A DUMMY FILE FOR A SINGLE MODULE.
    | PLEASE CREATE A NEW FILE FOR EVERY MODULE KEEPING THIS SAMPLE FILE AS IT
    | IS FOR FUTURE REFERENCE.
    |--------------------------------------------------------------------------
    |
     */
-------------------------------------------------------------------------------------------------------------------

    /*
    |--------------------------------------------------------------------------
    | General information for the module that requires this file to be configurated.
    |--------------------------------------------------------------------------
    |
     */
    
    'model_path' => 'YOUR_MODEL_PATH',
    'table_name' => 'YOUR_TABLE_NAME',
    'selective_columns' => false, // Boolean False means system will fetch the data with all columns of this table.
    'columns' => [], // If boolean is false then this array will be empty.


    /*
    |--------------------------------------------------------------------------
    | Filters Array to render filters on User Interface.
    |--------------------------------------------------------------------------
    |
     */

    'filters' => [
        [
            'id' => 'DOM_ELEMENT_ID', // ID value for to access from Javascript.
            'name' => 'DOM_ELEMENT_NAME', // Name value for name attribute for forms or else.
            'label' => 'DOM_ELEMENT_LABEL', // Label To Display on User Interface.
            'type' => 'text | select', // Input type value to render field as what.
            'value_type' => 'ROUTE_PARAM | PHP_VARIABLE | CUSTOM', // Field Default Value. Either provide custom value or use tag "ROUTE_PARAM | PHP_VARIABLE to get value from routes or variable passed to the blade.
            'value' => 'DOM_ELEMENT_VALUE', // Field Default Value. Either provide custom value or use tag "ROUTE_PARAM
            'selected' => 'DOM_ELEMENT_DEFAULT_VALUE', // Field Default Value. Either provide custom value or use tag "ROUTE_PARAM
            'visibility' => true, // Boolean to Either show in Filters (User Interface) or not.
            'filterable' => true, // Boolean to Either show in Filters (User Interface) or not.
            'filter_through_join' => false, // Boolean to Either set column for filtering through joined table or not.
            'join_table' => null, // Joined-Table name for Joined filters.
            'column_name' => 'YOUR_DATABASE_TABLE_COLUMN', // Joined-Column name from Joined-Table for Joined filters.
            'conditional_operator' => '= | >= | <= | <>', // Conditional operator to use in where caluse for filters.
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    |if you want to use joins within the module to show data with complex data 
    |Joins Boolean and Configuration to search and filter the data from relationship tables using foreign keys.
    |--------------------------------------------------------------------------
    |
     */

    'table_has_joins' => false, // Boolean for Joins.
    'joins' => [
        // Table Array which are going to be use for joins.
        'tables' => [
            [
                'join_with_table' => 'TABLE_NAME_TO_BE_JOIN_WITH',
                'reference_in_current' => 'REFERENCE_HERE', // Syntax is parent_table_name.id, Parent Table Name (which is module's main table) then Its Column Name which is to be used for reference in child tables.
                'conditional_sign' => '=', // conditional sign for where clause.
                'reference_in_join' => 'REFERENCE_HERE', // Syntax is reference_table_name.id, Reference Table Name (from which u want the data as per the parent table row) then Its Column Name which is used for reference with parent tables.
                'selective_columns' => false, // if you want to retrieve data but with limited columns instead of everything, then set value to "true"
                'columns' => [ // definition of columns you want to get from the table you are going to join with
                    [
                        'column_name' => 'COLUMN_NAME', // joined tabled column name
                        'aggregate' => false, // if you want a sum of a column or a count of column in different column name using "as"
                        'sum' => [], // If there is no SUM required for the column, then this array will remains empty.
                        'as' => null, // If "aggregate" is false, then this can be set as either "null" to get same column name or pass any custom string to get desired column name for specific column
                    ],
                    // NOTE: FOLLOWING EXAMPLE
                    [
                        'column_name' => '*', // with selective column true you can still get all columns and your custom named columns as well
                        'aggregate' => false, // if you want a sum of a column or a count of column in different column name using "as"
                        'sum' => [], // If there is no SUM required for the column, then this array will remains empty.
                        'as' => null, // If "aggregate" is false, then this can be set as either "null" to get same column name or pass any custom string to get desired column name for specific column
                    ],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Header array for DATATABLE on User Interface.
    |--------------------------------------------------------------------------
    |
     */

    'table_headers' => [
        [
            'title' => "First Name", // Label To Display on Table (User Interface)
            'data' => "first_name", // key_name to be used from array passed [Not a db collection but a processed array] (It could be a column name or custom name)
            'column_name' => 'COLUMN_NAME', // table column name same as in database table
            'visibility' => true, // Boolean to Either show on Table (User Interface) or not
            "searchable" => false, // Boolean to Either set column for searching or not
            'search_through_join' => false, // boolean to either search the value in parent table or one of the joined tables
            "table_name" => null, // if "search_through_join" is set to true then add the table name, this column is from (join)
            'exportable' => false, // Boolean to Either get column in export or not
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | If a module is going to have any buttons, then its array is given below.
    |--------------------------------------------------------------------------
    |
     */

    'row_has_buttons' => false,

    
    'table_row_buttons' => [
        [
            'label' => 'View',
            'class' => 'btn btn-info btn-sm shadow rounded-0',
            'style' =>  null, // 'bgColor: #000; color: #fff;'
            'route' => [
                'name' => 'route.name',
                'params' => ['study_id', 'id', 'site_id'],
            ],
            // '' => '#000',
            // 'color' => '#fff',
            'icon' => 'fa fa-eye',
            'tooltip' => 'View',
        ],
        [
            'label' => 'View 1',
            'class' => 'btn btn-warning btn-sm shadow rounded-0',
            'style' =>  null, // 'bgColor: #000; color: #fff;'
            'route' => [
                'name' => 'route.name',
                'params' => ['study_id', 'id', 'site_id'],
            ],
            // '' => '#000',
            // 'color' => '#fff',
            'icon' => 'fa fa-eye',
            'tooltip' => 'View',
        ],
    ],
];

