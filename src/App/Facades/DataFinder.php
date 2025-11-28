<?php

/**
 * DataFinder Facade
 * 
 * Provides a clean, static interface to the DataFinderManager.
 * Allows users to access DataFinder functionality from anywhere in their Laravel app.
 * 
 * Usage:
 *   DataFinder::query('users/main');
 *   DataFinder::query('users/main')->where('status', 'active')->get();
 *   DataFinder::search('users/main', 'john');
 *   DataFinder::paginate('users/main', $request);
 * 
 * @package SUHK\DataFinder
 * @since 2.0.0
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder query(string $configPath)
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder queryWithFilters(string $configPath, array $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder search(string $configPath, string $searchTerm)
 * @method static array paginate(string $configPath, \Illuminate\Http\Request $request)
 * @method static array getConfig(string $configPath)
 * @method static array getColumns(string $configPath)
 * @method static array getSearchableColumns(string $configPath)
 * @method static array getFilters(string $configPath)
 * @method static bool hasJoins(string $configPath)
 * @method static string getTableName(string $configPath)
 * @method static string getModelPath(string $configPath)
 * 
 * @see \SUHK\DataFinder\App\Services\DataFinderManager
 */

namespace SUHK\DataFinder\App\Facades;

use Illuminate\Support\Facades\Facade;

class DataFinder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'datafinder';
    }
}

