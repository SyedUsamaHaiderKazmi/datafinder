<?php

/**
    * Config Parse class for the DataFinder package.
    *
    * This helper file is responsible for parsing and providing data
    * required for package from configuration file. This is a wrapper class
    * to prevent repeatable function within controller file
    *
    * @package SUHK\DataFinder
    *
*/


namespace SUHK\DataFinder\App\Helpers;

use SUHK\DataFinder\App\Helpers\ConfigGlobal;

class ConfigParser
{
    /**
     * Fetch configuration by a specified key and condition.
     *
     * @param string $configFileName
     * @param string $configKey The key in the config file (e.g., 'table_headers', 'filters').
     * @param string|null $valueKey The specific key to extract (null for full entry).
     * @param string|null $conditionKey The key to check for a condition (null for no filtering).
     * @param mixed|null $conditionValue The value to match for the condition.
     * @param callable|null $transform A transformation function for each matching entry.
     * @return array
     */
    private static function getConfigByCondition(
        string $configFileName,
        string $configKey,
        ?string $valueKey = null,
        ?string $conditionKey = null,
        ?bool $conditionValue = null,
        ?callable $transform = null
    ) {
        $configEntries = ConfigGlobal::getValueFromFile("{$configFileName}", $configKey);
        $result = [];
        if (!is_array($configEntries)) {
            return $configEntries;
        }
        foreach ($configEntries as $key => $entry) {
            if (is_null($conditionKey) || (isset($entry[$conditionKey]) && $entry[$conditionKey] === $conditionValue)) {
                $result[] = $transform ? $transform($entry) : ($valueKey ? $entry[$valueKey] : $entry);
            }
        }
        // dd($result);
        return $result;
    }

    /**
     * Get table name.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getTableName(string $configFileName): string
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_name'
        );
    }


    /**
     * Get model path.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getModelPath(string $configFileName): string
    {
        return self::getConfigByCondition(
            $configFileName,
            'model_path'
        );
    }

    /**
     * if parent table (module) has selective solumns.
     *
     * @param string $configFileName
     * @return array
     */
    public static function tableHasSelectiveColumns(string $configFileName): string
    {
        return self::getConfigByCondition(
            $configFileName,
            'selective_columns'
        );
    }

    /**
     * if parent table (module) has selective solumns.
     *
     * @param string $configFileName
     * @return array
     */
    public static function tableSelectiveColumns(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'columns'
        );
    }

    /**
     * Get boolean if a configuration has joins.
     *
     * @param string $configFileName
     * @return array
     */
    public static function hasJoins(string $configFileName): string
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_has_joins'
        );
    }

    /**
     * Get joins if a configuration has joins.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getJoins(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'joins.tables'
        );
    }

    /**
     * Get joins if a configuration has joins.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getValuesForSelectQuery(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'joins.select'
        );
    }

    /**
     * Get visible table columns configuration for rendering.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getTableColumnsConfiguation(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_headers',
            null,
            'visibility',
            true,
            fn($header) => ['title' => $header['title'], 'data' => $header['data']]
        );
    }

    /**
     * Get exportable table columns with table names.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getTableColumnsForExport(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_headers',
            'data',
            'exportable',
            true,
            fn($header) => "{$header['table_name']}.{$header['data']}"
        );
    }

    /**
     * Get exportable table columns without table names.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getTableColumnsForExportWithoutTableName(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_headers',
            'data',
            'exportable',
            true
        );
    }

    /**
     * Get formatted table column titles for export.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getFormattedTableColumnsForExport(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_headers',
            'title',
            'exportable',
            true
        );
    }

    /**
     * Get formatted column titles for specific keys.
     *
     * @param string $configFileName
     * @param array $keys
     * @return array
     */
    public static function getFormattedTableColumnsNamesForExport(string $configFileName, array $keys): array
    {
        $tableHeaders = ConfigGlobal::getValueFromFile("{$configFileName}.php", 'table_headers');
        $columns = [];

        foreach ($keys as $key) {
            if (isset($tableHeaders[$key]) && $tableHeaders[$key]['exportable']) {
                $columns[] = $tableHeaders[$key]['title'];
            }
        }

        return $columns;
    }

    /**
     * Get searchable table columns configuration.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getTableSearchableColumns(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_headers',
            null,
            'searchable',
            true,
            fn($header) => [
                'title' => $header['title'],
                'column_name' => $header['column_name'],
                'table_name' => $header['table_name'],
                'search_through_join' => $header['search_through_join']
            ]
        );
    }

    /**
     * Get filter configuration.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getFiltersConfiguation(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'filters',
            null,
            'filterable',
            true
        );
    }
    /**
     * Get table tow button status.
     *
     * @param string $configFileName
     * @return array
     */
    public static function tableHasRowButtons(string $configFileName): bool
    {
        return self::getConfigByCondition(
            $configFileName,
            'row_has_buttons'
        );
    }
    /**
     * Get table tow buttons aarray.
     *
     * @param string $configFileName
     * @return array
     */
    public static function tableRowButtons(string $configFileName): array
    {
        return self::getConfigByCondition(
            $configFileName,
            'table_row_buttons'
        );
    }
}
