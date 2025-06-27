<?php

/**
    * Stub Handler class for the DataFinder package.
    *
    * This helper file is responsible for parsing and generating the files through stubs to publish
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;

class StubHandler
{

    public static function generate($stub_path, $values)
    {
        $stub = file_get_contents(realpath(dirname(__DIR__). $stub_path));

        foreach ($values as $key => $value) {
            $stub = str_replace($value['from'], $value['to'], $stub);
        }
        return $stub;
    }

    public static function publish($stub, $to)
    {
        File::put(ConfigGlobal::getPath($to), $stub);
    }

}