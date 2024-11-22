<?php
namespace SUHK\DataFinder\Helpers;

class Globals
{
    public static function getTableColumnsConfiguation($table_name)
    {
        $index_table_cols_configuration = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($useable_columns as $key => $value) {
            if ($value['visibility']) {
                array_push($index_table_cols_configuration, ['title' => $value['title'], 'data' => $value['data']]);
            }
        }
        return $index_table_cols_configuration;
    }

    public static function getTableColumnsForExport($table_name)
    {
        $index_table_cols_configuration = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($useable_columns as $key => $value) {
            if ($value['exportable']) {
                array_push($index_table_cols_configuration, $value['table_name'] . '.' . $value['data']);
            }
        }
        return $index_table_cols_configuration;
    }

    public static function getTableColumnsForExportWithoutTableName($table_name)
    {
        $index_table_cols_configuration = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($useable_columns as $key => $value) {
            if ($value['exportable']) {
                array_push($index_table_cols_configuration, $value['data']);
            }
        }
        return $index_table_cols_configuration;
    }

    public static function getFormattedTableColumnsForExport($table_name)
    {
        $index_table_cols_configuration = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($useable_columns as $key => $value) {
            if ($value['exportable']) {
                // $index_table_cols_configuration[$value['table_name'] . '.' . $value['data']] = $value['title'];
                array_push($index_table_cols_configuration, $value['title']);
            }
        }
        return $index_table_cols_configuration;
    }
    public static function getFormattedTableColumnsNamesForExport($table_name, $keys)
    {
        $index_table_cols_configuration = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($keys as $key => $value) {
            if (isset($useable_columns[$value])) {
                if ($useable_columns[$value]['exportable']) {
                    // $index_table_cols_configuration[$value['table_name'] . '.' . $value['data']] = $value['title'];
                    array_push($index_table_cols_configuration, $useable_columns[$value]['title']);
                }
            }
        }
        return $index_table_cols_configuration;
    }

    public static function getTableSearchableColumns($table_name)
    {
        $searchable_columns = [];
        $useable_columns = \config('filter_configurations.' . $table_name . '.table_headers');
        foreach ($useable_columns as $key => $value) {
            if ($value['searchable']) {
                $data = ['column_name' => $value['data'], 'table_name' => $value['table_name']];
                array_push($searchable_columns, $data);
            }
        }
        return $searchable_columns;
    }
}
