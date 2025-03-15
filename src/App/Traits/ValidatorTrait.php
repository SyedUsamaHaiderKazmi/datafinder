<?php

/**
    * Setup Package command class for the DataFinder package.
    *
    * This command file is responsible for setting up the datafinder package for users
    * such as publishing configuration file, assets and more
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Traits;

trait ValidatorTrait
{

    // error messages
    // Searchable Specific Error Handlers
    
    private $EXCP_MSG_NO_SEARCH_COLUMN = 'No <b>searchable</b> column found!';
    private $EXCP_MSG_SEARCH_COLUMN = 'Property: <b>column_name</b> is not defined for the searchable column <b>"{title}"</b>';
    private $EXCP_MSG_SEARCH_WITH_JOIN_COLUMN = 'Property: <b>table_name</b> is not defined for the searchable column <b>"{title}"</b> through joins!';

    public function returnExceptionMessage($replace, $with, $from){
        $this->errors[] = str_replace($replace, $with, $from);
    }

    public function keyHasProperValue($object, $value)
    {
        return (isset($object[$value]) && $object[$value] != null && $object[$value] != '') ? true : false;
    }

}