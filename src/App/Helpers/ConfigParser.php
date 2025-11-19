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
            if (is_null($conditionKey) || !isset($entry['conditionKey']) || (isset($entry[$conditionKey]) && $entry[$conditionKey] === $conditionValue)) {
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
     * Get all primary query options .
     *
     * @param string $configFileName
     * @return array
     */
    public static function getPrimaryQueryOptions(string $configFileName): array
    {
        $model_name = self::getConfigByCondition(
            $configFileName,
            'model_path'
        );
        $table_name = self::getConfigByCondition(
            $configFileName,
            'table_name'
        );
        $has_select = self::getConfigByCondition(
            $configFileName,
            'has_select'
        );
        $select = self::getConfigByCondition(
            $configFileName,
            'select'
        );
        $has_joins = self::getConfigByCondition(
            $configFileName,
            'table_has_joins'
        );
        $joins = self::getConfigByCondition(
            $configFileName,
            'joins'
        );
        $where = self::getConfigByCondition(
            $configFileName,
            'where'
        );
        $groupBy = self::getConfigByCondition(
            $configFileName,
            'groupBy'
        );
        $having = self::getConfigByCondition(
            $configFileName,
            'having'
        );

        return [
            'model_path' => $model_name ?? '',
            'table_name' => $table_name ?? '',
            'has_select' => $has_select ?? false,
            'select' => $select ?? [],
            'has_joins' => $has_joins ?? false,
            'joins' => $joins ?? [],
            'where' => $where ?? [],
            'groupBy' => $groupBy ?? [],
            'having' => $having ?? [],
        ];
    }

    /**
     * Get all primary frontend table options .
     *
     * @param string $configFileName
     * @return array
     */
    public static function getPrimaryFrontendOptions(string $configFileName): array
    {
        $table_id = self::getConfigByCondition(
            $configFileName,
            'dom_table_id'
        );
        $class = self::getConfigByCondition(
            $configFileName,
            'class'
        );
        $table_responsiveness = self::getConfigByCondition(
            $configFileName,
            'responsive'
        );
        $full_text_search = self::getConfigByCondition(
            $configFileName,
            'full_text_search'
        );
        $default_per_page = self::getConfigByCondition(
            $configFileName,
            'default_per_page'
        );
        $allow_per_page_options = self::getConfigByCondition(
            $configFileName,
            'allow_per_page_options'
        );
        $per_page_options = self::getConfigByCondition(
            $configFileName,
            'per_page_options'
        );
        $exportable = self::getConfigByCondition(
            $configFileName,
            'exportable'
        );
        $exportable_by_chunk = self::getConfigByCondition(
            $configFileName,
            'exportable_by_chunk'
        );
        $exportable_chunk_size = self::getConfigByCondition(
            $configFileName,
            'exportable_chunk_size'
        );
        $allow_custom_route = self::getConfigByCondition(
            $configFileName,
            'allow_custom_route'
        );
        $custom_data_route = self::getConfigByCondition(
            $configFileName,
            'custom_data_route'
        );
        $custom_export_route = self::getConfigByCondition(
            $configFileName,
            'custom_export_route'
        );

        return [
            'dom_table_id' => $table_id ?? '',
            'class' => $class ?? '',
            'responsive' => $table_responsiveness ?? '',
            'full_text_search' => $full_text_search ?? true,
            'default_per_page' => !empty($default_per_page) ? $default_per_page : 50,
            'allow_per_page_options' => $allow_per_page_options ?? true,
            'per_page_options' => $per_page_options ?? [10, 25, 50, 100],
            'exportable' => $exportable ?? false,
            'exportable_by_chunk' => $allow_custom_route ? false : $exportable_by_chunk,
            'exportable_chunk_size' => $allow_custom_route ? null : $exportable_chunk_size,
            'allow_custom_route' => $allow_custom_route ?? false,
            'custom_data_route' => $custom_data_route ?? null,
            'custom_export_route' => $custom_export_route ?? null,
        ];
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
            'has_select'
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
            'select'
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
            'joins'
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
            fn($header) => ['title' => $header['title'], 'data' => $header['data'], 'orderable' => $header['orderable'] ?? true]
        );
    }

    /**
     * Get model path.
     *
     * @param string $configFileName
     * @return array
     */
    public static function isExportable(string $configFileName): string
    {
        return self::getConfigByCondition(
            $configFileName,
            'exportable'
        );
    }

    /**
     * Get model path.
     *
     * @param string $configFileName
     * @return array
     */
    public static function isExportableByChunk(string $configFileName): ?string
    {
        return self::getConfigByCondition(
            $configFileName,
            'exportable_by_chunk'
        );
    }
    /**
     * Get model path.
     *
     * @param string $configFileName
     * @return array
     */
    public static function getExportableChunkSize(string $configFileName)
    {
        return self::getConfigByCondition(
            $configFileName,
            'exportable_chunk_size'
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
            fn($header) => ['title' => $header['title'], 'data' => $header['data']]
        );
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
                'table_name' => $header['table_name'] ?? '',
                'search_through_join' => $header['search_through_join'] ?? false
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
