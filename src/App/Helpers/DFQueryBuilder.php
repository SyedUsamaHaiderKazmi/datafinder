<?php

/**
    * DataFinder Support Trait for the Side Required Operation.
    *
    * This trait file is responsible for providing a 
    *
    * @package SUHK\DataFinder
    *
*/
namespace SUHK\DataFinder\App\Helpers;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;
use SUHK\DataFinder\App\Traits\ValidatorTrait;
use SUHK\DataFinder\App\Traits\Supports\JoinTrait;

class DFQueryBuilder
{
    use ValidatorTrait, JoinTrait;

    protected $where = [];
    protected $groupBy = [];
    protected $select = [];
    protected $having = [];
    protected $query_options = [];

    function __construct($query_options)
    {
        $this->query_options = $query_options;
        /*$this->setWhere($query);
        $this->setGroupBy($query);
        $this->setSelect($query);
        $this->setHaving($query);*/
    }

    public function createQuery($by = ConfigGlobal::QUERY_BY_ELOQUENT_BUILDER)
    {
        if ($by == ConfigGlobal::QUERY_BY_MODEL) {
            $query = $this->query_options['model_path']::query();
        } else {
            $query = DB::table($this->query_options['table_name']);
        }

        // if primary table have selective columns using select option or extra columns using select
        $this->setupSelectQuery($query, $this->query_options['has_select'] ?? false, $this->query_options['table_name'], $this->query_options['select']);
        
        $this->setJoins($query);
        $this->setWhere($query);
        $this->setGroupBy($query);
        $this->setHaving($query);
        
        return $query;
    }   

    private function setWhere($query)
    {
        if ($this->keyHasProperValue($this->query_options, 'where') && count($this->query_options['where']) > 0) {
            $where = $this->query_options['where'];
            $query->where(function ($query) use ($where) {
                foreach ($where as $key => $value) {
                    if ($this->keyHasProperValue($value, 'value')) {
                        $conditional_operator = $this->keyHasProperValue($value, 'conditional_operator') ? $value['conditional_operator'] : '=';
                        $query->{$value['type']}($value['column_name'], $value['conditional_operator'], $value['value']);
                    } else {
                        $query->{$value['type']}($value['column_name']);
                    }
                }
            });
        }
    }

    private function setGroupBy($query)
    {
        if ($this->keyHasProperValue($this->query_options, 'groupBy') && count($this->query_options['groupBy']) > 0) {
            $query->groupBy($this->query_options['groupBy']);
        };
    }

    private function setHaving($query)
    {
        if ($this->keyHasProperValue($this->query_options, 'having') && count($this->query_options['having']) > 0) {
            foreach ($this->query_options['having'] as $key => $value) {
                if ($this->keyHasProperValue($value, 'value')) {
                    $conditional_operator = $this->keyHasProperValue($value, 'conditional_operator') ? $value['conditional_operator'] : '=';
                    $query->{$value['type']}($value['column_name'], $value['conditional_operator'], $value['value']);
                } else {
                    $query->{$value['type']}($value['column_name']);
                }
            }
        }
    }

    private function setJoins($query){
        // dd($joins);
        $has_joins = $this->query_options['has_joins'] ?? false;
        if ($has_joins) {
            foreach ($this->query_options['joins'] as $key => $join) {
                $this->joinsInit($join);
                $this->attachJoin($query);
                $this->setupSelectQuery($query, $join['using']['options']['has_select'], $this->joined_table_name, $join['using']['options']['select']);
            }
        }
    }

    private function setupSelectQuery($query, $has_select, $table_name, $columns){
        if ($has_select) {
            foreach ($columns as $key => $column) {
                if ($this->matchTagValues($column['type'], 'default')) {
                    $raw_query = $table_name . '.' . $column['column_name'];
                    if ($this->keyHasProperValue($column, 'alias')) {
                        $raw_query .= ' as ' . $column['alias'];
                    }
                    $query->addSelect($raw_query);
                } elseif ($this->matchTagValues($column['type'], 'raw')) {
                    $query->addSelect(\DB::raw($column['column_name']));
                }
                // array_push($select_raw, $raw_query);
            }
        } else {
            $query->addSelect($table_name . '.*'); /* WIll get all the data for all columns if the array is empty*/
            // array_push($select_raw, $table_name . '.*');
        }
    }
}