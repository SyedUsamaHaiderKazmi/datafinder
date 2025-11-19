<?php

/**
    * Config Global class for the DataFinder package.
    *
    * This helper file is responsible for providing re-useable functions and variables
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Helpers;

use Illuminate\Support\Arr;

class ConfigGlobal
{
    static $basePath = 'Helpers\DataFinder\\';
    static $file_not_exist_message = 'Configuration file for the package not found. Please make sure you have correct configuration file setup.';
    static $config_file_path_missed_message = 'Configuration file path is not defined.';

    // Configuration File Section Constants
    const MAIN_CONFIG_FILE = ['key' => 'main_config_file', 'stub_path' => '/../stubs/main.stub'];
    const SECTION_A = ['key' => 'frontend_base', 'stub_path' => '/../stubs/frontend/base.stub'];
    const SECTION_B = ['key' => 'backend_primary', 'stub_path' => '/../stubs/database/primary.stub'];
    const SECTION_C = ['key' => 'frontend_filters', 'stub_path' => '/../stubs/frontend/filters.stub'];
    const SECTION_D = ['key' => 'frontend_table_headers', 'stub_path' => '/../stubs/frontend/table_headers.stub'];
    const SECTION_E = ['key' => 'backend_joins', 'stub_path' => '/../stubs/database/joins.stub'];
    const SECTION_F = ['key' => 'frontend_row_buttons', 'stub_path' => '/../stubs/frontend/row_buttons.stub'];

    // type of sources to generate query, by model or by table (raw)
    const QUERY_BY_MODEL = 'MODEL';
    const QUERY_BY_ELOQUENT_BUILDER = 'TABLE_RAW';

    public static function validateConfigFile($path)
    {
        if(file_exists(self::getPath($path))){
            return true;
        }
        return false;
    }

    public static function getValueFromFile($path, $value_to_get)
    {
        // This function is used to get the array value from the file contains configrations
        // only as array. Not a class but just a file.

        $file = include(self::getPath($path));
        $value = Arr::get($file, $value_to_get);
        return $value;
    }

    public static function getPath($path)
    {
        $file_path = app_path(self::$basePath . $path . '.php');
        $path_info = pathinfo($file_path);
        self::createDirectoryIfNotExist($path_info['dirname']);
        return $file_path;
    }

    public static function createDirectoryIfNotExist($path, $replace = false)
    {
        if (file_exists($path) && $replace) {
            rmdir($path);
        }

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
