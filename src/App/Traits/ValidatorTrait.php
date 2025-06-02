<?php

/**
    * Validator Trait for the DataFinder package.
    *
    * This trait file is responsible for providing validation checks along with message response which will
    * upgraded with the passage of time
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Traits;

trait ValidatorTrait
{
    private $errors = [];
    /** 
     * Error Messages Section
    */

    // Searchable Specific Error Handlers

    private $VALIDATION_MSG_NO_SEARCH_COLUMN = 'No <b>searchable</b> column found!';
    private $VALIDATION_MSG_SEARCH_COLUMN = 'Property: <b>column_name</b> is not defined for the searchable column <b>"{title}"</b>';
    private $VALIDATION_MSG_SEARCH_WITH_JOIN_COLUMN = 'Property: <b>table_name</b> is not defined for the searchable column <b>"{title}"</b> through joins!';

    /** 
     * Function Section
    */

    public function setValidationError($replace, $with, $from){
        $this->errors[] = str_replace($replace, $with, $from);
    }

    public function setExceptionError($exception){
        $this->errors[] = $exception;
    }

    public function getErrors(){
        return $this->errors;
    }

    public function keyHasProperValue($object, $value)
    {
        return (isset($object[$value]) && $object[$value] != null && $object[$value] != '') ? true : false;
    }

    public function matchTagValues($compare, $to)
    {
        return strcasecmp($compare, $to) == 0 ? true: false;
    }
}