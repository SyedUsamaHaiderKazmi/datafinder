<?php
namespace SUHK\DataFinder\Helpers;

use Illuminate\Support\Arr;

class ConfigGlobal
{
    static $basePath = 'Helpers\DataFinder\\';


    public static function getValueFromFile($path, $value_to_get)
    {
        // This function is used to get the array value from the file contains configrations
        // only as array. Not a class but just a file.

        $file = include(app_path(self::$basePath . $path));
        $value = Arr::get($file, $value_to_get);
        return $value;
    }

}
