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

    'table_name' => [

        /*
        |--------------------------------------------------------------------------
        | Filters Array to render filters on User Interface.
        |--------------------------------------------------------------------------
        |
         */

        'filters' => [
            'field_name' => [
                'id' => 'end_date', // ID value for to access from Javascript.
                'name' => 'admission_date', // Name value for name attribute for forms or else.
                'label' => 'Field Label', // Label To Display on User Interface.
                'type' => 'date', // Input type value to render field as what.
                'value' => date('Y-m-d'), // Field Default Value.
                'visibility' => true, // Boolean to Either show in Filters (User Interface) or not.
                'filter_through_join' => false, // Boolean to Either set column for filtering through joined table or not.
                'join_table' => null, // Joined-Table name for Joined filters.
                'table_column_name' => 'admission_date', // Joined-Column name from Joined-Table for Joined filters.
                'conditional_operator' => '<=', // Conditional operator to use in where caluse for filters.
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Joins Boolean and Configuration to search and filter the data from relationship tables using foreign keys.
        |--------------------------------------------------------------------------
        |
         */

        'table_has_joins' => true, // Boolean for Joins.
        'joins' => [

            // Table Array which are going to be use for joins.
            'tables' => [
                'table_name' => [
                    'reference_in_current' => 'parent_table_name.column_name', // Syntax is, Parent Table Name (which is module's main table) then Its Column Name which is used for reference in child tables.
                    'conditional_sign' => '=', // conditional sign for where clause.
                    'reference_in_join' => 'reference_table_name.column_name', // Syntax is, Reference Table Name (from which u want the data as per the parent table row) then Its Column Name which is used for reference with parent tables.
                ],
            ],

            // Select query to give command to plugin to get all/ specific columns along with their sum and counts with different name using "as".
            'select' => [

                /*
                |--------------------------------------------------------------------------
                | NOTE: Please make sure to give same table name as you have given in joins[tables] above.
                |--------------------------------------------------------------------------
                |
                 */
                'table_name' => [
                    'selective_columns' => false, // Boolean False means system will fetch the data with all columns of this table.
                    'columns' => [], // If boolean is false then this array will be empty.
                ],
                'table_name' => [
                    'selective_columns' => true, // Boolean True means system will fetch the data with defined columns below for this table.
                    /*
                    |--------------------------------------------------------------------------
                    | Selective column to get for table.
                    |--------------------------------------------------------------------------
                    |
                     */
                    'columns' => [
                        'column_name' => [
                            'sum' => [], // If there is no SUM required the column, then this array will remains empty.
                            'as' => null, // If SUM array is empty, then this will remains as "null".
                        ],
                        'column_name' => [
                            /*
                            |--------------------------------------------------------------------------
                            | If a column requires SUM with different name to use using "as"
                            |--------------------------------------------------------------------------
                            |
                             */
                            'sum' => [
                                0 => [
                                    'as' => 'total_payed', // which key name it should give data against
                                    'where' => [
                                        'when_clause' => 'installments.status_id = 1', // dummy syntax is given. Same Table Name than Its Status Column
                                        'else_clause' => '0', // Else Clause, if condition is not met, then return this.
                                    ],
                                ],
                                1 => [
                                    'as' => 'out_standing',
                                    'where' => [
                                        'when_clause' => 'installments.status_id = 0',
                                        'else_clause' => '0',
                                    ],
                                ],
                                3 => [
                                    'as' => 'receivable_till_date',
                                    'where' => [
                                        'when_clause' => 'installments.status_id = 0 AND installments.due_date lessthan ' . date('Y-m-d'),
                                        'else_clause' => '0',
                                    ],
                                ],
                            ],
                            'as' => null,
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
            'column_name' => [
                'title' => "Demo", // Label To Display on Table (User Interface)
                'data' => "demo", // Column Name to access from collection/ array
                'visibility' => true, // Boolean to Either show on Table (User Interface) or not
                "searchable" => true, // Boolean to Either set column for searching or not
                "table_name" => 'table_name',
                'exportable' => true, // Boolean to Either get column in export or not
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | If a module is going to have any buttons, then its array is given below.
        |--------------------------------------------------------------------------
        |
         */

        'row_has_buttons' => true,

        'table_row_buttons' => [
            0 => [
                'label' => 'View',
                'route_key' => 'view',
                'bgColor' => '#000',
                'color' => '#fff',
                'icon' => 'fa fa-eye',
                'tooltip' => 'View',
            ],
        ],
    ],
];
