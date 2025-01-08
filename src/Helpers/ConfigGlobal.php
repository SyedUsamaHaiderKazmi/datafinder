<?php
namespace SUHK\DataFinder\Helpers;

use Illuminate\Support\Arr;

class ConfigGlobal
{
    static $basePath = 'Helpers\DataFinder\\';
    static $file_not_exisit_message = 'Configuration file for the package not found. Please make sure you have correct configuration file setup.';


    public static function validateConfigFile($path)
    {
        if(file_exists(app_path(self::$basePath . $path . '.php'))){
            return true;
        }
        return false;
    }


    public static function getValueFromFile($path, $value_to_get)
    {
        // This function is used to get the array value from the file contains configrations
        // only as array. Not a class but just a file.

        $file = include(app_path(self::$basePath . $path . '.php'));
        $value = Arr::get($file, $value_to_get);
        return $value;
    }

}
