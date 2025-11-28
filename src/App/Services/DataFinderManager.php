<?php

/**
 * DataFinder Manager
 * 
 * Core service class that provides programmatic access to DataFinder functionality.
 * This is the class that backs the DataFinder facade.
 * 
 * Responsibilities:
 * - Building queries from configuration
 * - Applying filters and search
 * - Providing pagination for DataTables
 * - Exposing configuration parsers
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 */

namespace SUHK\DataFinder\App\Services;

use SUHK\DataFinder\App\Helpers\ConfigParser;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;
use SUHK\DataFinder\App\Helpers\DFQueryBuilder;
use SUHK\DataFinder\App\Traits\DataFinderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataFinderManager
{
    use DataFinderTrait;

    // =========================================================================
    // QUERY BUILDING
    // =========================================================================

    /**
     * Get a query builder instance for a configuration
     * 
     * This is the primary method for getting a query based on your config.
     * Returns an Eloquent or Query Builder that you can further customize.
     * 
     * @param string $configPath Path to config file (e.g., 'users/main')
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * 
     * @example
     * // Basic usage
     * $query = DataFinder::query('users/main');
     * $users = $query->get();
     * 
     * // With additional conditions
     * $query = DataFinder::query('users/main')
     *     ->where('status', 'active')
     *     ->orderBy('created_at', 'desc');
     * $users = $query->paginate(15);
     */
    public function query(string $configPath)
    {
        $this->validateConfigPath($configPath);
        
        $queryOptions = ConfigParser::getPrimaryQueryOptions($configPath);
        $queryBuilder = new DFQueryBuilder($queryOptions);
        
        return $queryBuilder->createQuery(ConfigGlobal::QUERY_BY_MODEL);
    }

    /**
     * Get a query builder with filters applied
     * 
     * @param string $configPath Path to config file
     * @param array $filters Filters in DataFinder format
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * 
     * @example
     * $filters = [
     *     'status' => [
     *         ['value' => 'active', 'type' => 'text', 'column_name' => 'status', 'conditional_operator' => '=']
     *     ]
     * ];
     * $query = DataFinder::queryWithFilters('users/main', $filters);
     */
    public function queryWithFilters(string $configPath, array $filters = [])
    {
        $query = $this->query($configPath);
        
        if (!empty($filters)) {
            $queryOptions = ConfigParser::getPrimaryQueryOptions($configPath);
            $hasJoins = $queryOptions['has_joins'] ?? false;
            $tableName = $queryOptions['table_name'];
            
            $this->applyFilters($query, $filters, $hasJoins, $tableName);
        }
        
        return $query;
    }

    /**
     * Get a query builder with search applied
     * 
     * @param string $configPath Path to config file
     * @param string $searchTerm Search term
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     * 
     * @example
     * $query = DataFinder::search('users/main', 'john');
     * $users = $query->get();
     */
    public function search(string $configPath, string $searchTerm)
    {
        $query = $this->query($configPath);
        
        if (!empty($searchTerm)) {
            $searchableColumns = ConfigParser::getTableSearchableColumns($configPath);
            $queryOptions = ConfigParser::getPrimaryQueryOptions($configPath);
            $tableName = $queryOptions['table_name'];
            
            $this->applySearch($query, $searchableColumns, $searchTerm, $tableName);
        }
        
        return $query;
    }

    /**
     * Get a query builder with both filters and search applied
     * 
     * @param string $configPath Path to config file
     * @param array $filters Filters array
     * @param string|null $searchTerm Search term
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function queryWithFiltersAndSearch(string $configPath, array $filters = [], ?string $searchTerm = null)
    {
        $query = $this->queryWithFilters($configPath, $filters);
        
        if (!empty($searchTerm)) {
            $searchableColumns = ConfigParser::getTableSearchableColumns($configPath);
            $queryOptions = ConfigParser::getPrimaryQueryOptions($configPath);
            $tableName = $queryOptions['table_name'];
            
            $this->applySearch($query, $searchableColumns, $searchTerm, $tableName);
        }
        
        return $query;
    }

    // =========================================================================
    // DATATABLE PAGINATION
    // =========================================================================

    /**
     * Get paginated data formatted for DataTables
     * 
     * This method handles the full DataTable request cycle:
     * - Applies filters from request
     * - Applies search from request
     * - Handles pagination (start, length)
     * - Returns DataTable-compatible response
     * 
     * @param string $configPath Path to config file
     * @param Request $request Laravel request with DataTable params
     * @return array DataTable-compatible response
     * 
     * @example
     * // In your controller
     * public function data(Request $request)
     * {
     *     $response = DataFinder::paginate('users/main', $request);
     *     return response()->json($response);
     * }
     */
    public function paginate(string $configPath, Request $request): array
    {
        $query = $this->query($configPath);
        $queryOptions = ConfigParser::getPrimaryQueryOptions($configPath);
        $tableName = $queryOptions['table_name'];
        $hasJoins = $queryOptions['has_joins'] ?? false;
        
        // Apply filters
        $filters = $request->input('filters', []);
        if (!empty($filters)) {
            $this->applyFilters($query, $filters, $hasJoins, $tableName);
        }
        
        // Get total before search
        $totalQuery = clone $query;
        $totalData = $this->getCount($totalQuery);
        
        // Apply search
        $search = $request->input('search.value');
        if (!empty($search)) {
            $searchableColumns = ConfigParser::getTableSearchableColumns($configPath);
            $this->applySearch($query, $searchableColumns, $search, $tableName);
        }
        
        // Get filtered count
        $filteredQuery = clone $query;
        $totalFiltered = $this->getCount($filteredQuery);
        
        // Apply ordering
        $orderColumn = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $columns = ConfigParser::getTableColumnsConfiguation($configPath);
        
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn]['data'], $orderDir);
        }
        
        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        if ($start > 0) {
            $query->skip($start);
        }
        if ($length > 0) {
            $query->take($length);
        }
        
        // Get records
        $records = $query->get();
        
        // Add row buttons if configured
        if (ConfigParser::tableHasRowButtons($configPath)) {
            $buttons = ConfigParser::tableRowButtons($configPath);
            foreach ($records as $record) {
                $this->generateButtons($record, $buttons);
            }
        }
        
        return [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $records->toArray(),
            'errors' => $this->getErrors(),
        ];
    }

    // =========================================================================
    // CONFIGURATION ACCESS
    // =========================================================================

    /**
     * Get all query options for a configuration
     * 
     * @param string $configPath Path to config file
     * @return array
     */
    public function getQueryOptions(string $configPath): array
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getPrimaryQueryOptions($configPath);
    }

    /**
     * Get frontend options for a configuration
     * 
     * @param string $configPath Path to config file
     * @return array
     */
    public function getFrontendOptions(string $configPath): array
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getPrimaryFrontendOptions($configPath);
    }

    /**
     * Get table columns configuration
     * 
     * @param string $configPath Path to config file
     * @return array
     */
    public function getColumns(string $configPath): array
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getTableColumnsConfiguation($configPath);
    }

    /**
     * Get searchable columns
     * 
     * @param string $configPath Path to config file
     * @return array
     */
    public function getSearchableColumns(string $configPath): array
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getTableSearchableColumns($configPath);
    }

    /**
     * Get filters configuration
     * 
     * @param string $configPath Path to config file
     * @return array
     */
    public function getFilters(string $configPath): array
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getFiltersConfiguation($configPath);
    }

    /**
     * Get table name
     * 
     * @param string $configPath Path to config file
     * @return string
     */
    public function getTableName(string $configPath): string
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getTableName($configPath);
    }

    /**
     * Get model path
     * 
     * @param string $configPath Path to config file
     * @return string
     */
    public function getModelPath(string $configPath): string
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::getModelPath($configPath);
    }

    /**
     * Check if configuration has joins
     * 
     * @param string $configPath Path to config file
     * @return bool
     */
    public function hasJoins(string $configPath): bool
    {
        $this->validateConfigPath($configPath);
        return (bool) ConfigParser::hasJoins($configPath);
    }

    /**
     * Check if configuration has row buttons
     * 
     * @param string $configPath Path to config file
     * @return bool
     */
    public function hasRowButtons(string $configPath): bool
    {
        $this->validateConfigPath($configPath);
        return ConfigParser::tableHasRowButtons($configPath);
    }

    /**
     * Check if export is enabled
     * 
     * @param string $configPath Path to config file
     * @return bool
     */
    public function isExportable(string $configPath): bool
    {
        $this->validateConfigPath($configPath);
        return (bool) ConfigParser::isExportable($configPath);
    }

    // =========================================================================
    // UTILITY METHODS
    // =========================================================================

    /**
     * Validate that a config file exists
     * 
     * @param string $configPath Path to config file
     * @throws \InvalidArgumentException If config file not found
     */
    public function validateConfigPath(string $configPath): void
    {
        if (!ConfigGlobal::validateConfigFile($configPath)) {
            throw new \InvalidArgumentException(
                "DataFinder configuration file not found: {$configPath}"
            );
        }
    }

    /**
     * Check if a config file exists
     * 
     * @param string $configPath Path to config file
     * @return bool
     */
    public function configExists(string $configPath): bool
    {
        return ConfigGlobal::validateConfigFile($configPath);
    }

    /**
     * Get count from a query (handles aggregates and group by)
     * 
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @return int
     */
    protected function getCount($query): int
    {
        $baseQuery = $query instanceof \Illuminate\Database\Eloquent\Builder
            ? $query->getQuery()
            : $query;

        $hasGroupBy = !empty($baseQuery->groups);
        $hasAggregates = collect($baseQuery->columns)->contains(function ($col) {
            return is_string($col) && preg_match('/count|sum|avg|max|min|group_concat/i', $col);
        });

        if ($hasGroupBy || $hasAggregates) {
            return DB::query()->fromSub($baseQuery, 'sub')->count();
        }

        return $baseQuery->count();
    }
}

