<?php

/**
    * Add New Module command class for the DataFinder package.
    *
    * This command file is responsible for setting up the datafinder package configureation for new module
    * such as publishing frontend or database column configurations or joins or table row buttons etc
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SUHK\DataFinder\App\Helpers\StubHandler;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;

class AddNewModule extends Command
{
    private $module_name = null;
    protected $signature = 'datafinder:add-new-module';

    protected $description = 'This command is used to generate configuration per module to help user journey for integration by setting up module folder, publishing frontend configuration files, database config file if require joins else primary database information, row buttons in table and more..';

    public function handle()
    {
        // $this->comment('Please answer following questions to generate modular configurations:');
        $this->question('Please answer following questions to generate modular configurations:');
        // get module name
        $module_input = $this->ask('Module Name (camalCase):');
        $this->module_name = Str::camel($module_input);

        // get database table name
        $db_table_name_input = $this->ask('Database Table Name: '); // database table name for the module
        // get eloquent model path
        $model_path_input = $this->ask('Eloquent Model Path: '); // eloquent model path for the module
        // has joins or not?
        $has_joins_input = $this->choice('Data from other database tables using joins?', ['Y', 'N'], 1);
        $has_joins = $has_joins_input == 'Y' ? true: false;
        // has joins or not?
        $has_buttons_input = $this->choice('Will each row requires custom function buttons?', ['Y', 'N'], 1); 
        $has_buttons = $has_buttons_input == 'Y' ? true: false;
        // stub generating and publishing process start
        // generate section A stub
        $section_a = $this->setupStub(ConfigGlobal::SECTION_A['stub_path'], [
            ['from' => '{{DOM_TABLE_ID}}', 'to' => Str::snake($this->module_name)]
        ]);
        // generate section B stub
        $section_b = $this->setupStub(ConfigGlobal::SECTION_B['stub_path'], [
            ['from' => '{{MODEL_PATH}}', 'to' => $model_path_input],
            ['from' => '{{DATABASE_TABLE_NAME}}', 'to' => $db_table_name_input]
        ]);
        // generate section C stub
        $section_c = $this->setupStub(ConfigGlobal::SECTION_C['stub_path'], []);
        // generate section D stub
        if ($has_joins) {
            $section_d = $this->setupStub(ConfigGlobal::SECTION_D['stub_path'], []);
        }
        // generate section E stub
        $section_e = $this->setupStub(ConfigGlobal::SECTION_E['stub_path'], []);
        // generate section F stub
        if ($has_buttons) {
            $section_f = $this->setupStub(ConfigGlobal::SECTION_F['stub_path'], []);
        }
        $this->comment('support configuration files have been published successfully with the information provided above.');
        $this->comment('Generating the main configuration file. Please wait!');

        // generate main configuration stub
        $section_a = $this->setupStub(ConfigGlobal::MAIN_CONFIG_FILE['stub_path'], [
            ['from' => "'{{HAS_JOIN}}'", 'to' => $has_joins ? 'true': 'false'],
            ['from' => "'{{JOIN_VALUE}}'", 'to' => $has_joins? "include('database/joins.php')" : ''],
            ['from' => "'{{HAS_BUTTONS}}'", 'to' => $has_buttons ? 'true': 'false'],
            ['from' => "'{{BUTTON_VALUE}}'", 'to' => $has_buttons ? "include('frontend/row_buttons.php')": ''],
        ]);
    }

    function setupStub($stub_path, $values)
    {
        $stub = StubHandler::generate($stub_path, $values);
        $publish_path = str_replace(['/../stubs/', '.stub'], ['', ''], $stub_path);
        $stub_handler = StubHandler::publish($stub, $this->module_name . '/' . $publish_path);
    }
}
