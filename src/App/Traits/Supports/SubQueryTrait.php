<?php

/**
    * DataFinder Support Trait for the Side Required Operation.
    *
    * This trait file is responsible for providing a 
    *
    * @package SUHK\DataFinder
    *
*/
namespace SUHK\DataFinder\App\Traits\Supports;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
;

trait SubQueryTrait
{

    protected $where = [];
    protected $groupBy = [];
    protected $select = [];
    protected $having = [];

    public function subQueryInit($sub_query)
    {
        $this->setWhere($sub_query);
        $this->setGroupBy($sub_query);
        $this->setSelect($sub_query);
        $this->setHaving($sub_query);
    }

    public function getNeatQuery($table_name){
        $query = DB::table($table_name);
        if (count($this->select) > 0) {
            $this->setupSelectQuery($query, true, $table_name, $this->select);
        }
        if (count($this->where) > 0) {
            $where = $this->where;
            $query->where(function (Builder $query) use ($where) {
                foreach ($where as $key => $value) {
                    $query->{$value['type']}($value['column_name'], $value['conditional_operator'], $value['value']);
                }
            });
        }
        if (count($this->groupBy) > 0) {
            $query->groupBy($this->groupBy);
        }
        if (count($this->having) > 0) {
            foreach ($this->having as $key => $value) {
                $query->{$value['type']}($value['column_name'], $value['conditional_operator'], $value['value']);
            }
        }
        return $query;
    }   

    private function setWhere($sub_query){
        $this->where = $sub_query['where'] ?? [];
    }

    private function setGroupBy($sub_query){
        $this->groupBy = $sub_query['groupBy'] ?? [];
    }

    private function setSelect($sub_query){
        $this->select = $sub_query['select'] ?? [];
    }

    private function setHaving($sub_query){
        $this->having = $sub_query['having'] ?? [];
    }
}