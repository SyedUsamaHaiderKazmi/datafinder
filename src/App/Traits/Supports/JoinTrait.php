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
use SUHK\DataFinder\App\Helpers\DFQueryBuilder;

trait JoinTrait
{

    protected $join_type = '';
    protected $join_by = '';
    protected $joined_table_name = '';
    protected $join_options = [];

    public function joinsInit($join)
    {
        $this->setTableName($join);
        $this->setOption($join);
        $this->setJoinType($join);
        $this->setJoinBy($join);
    }

    public function attachJoin($query){
        if ($this->matchTagValues($this->join_by, 'table')) {
            $this->getJoinByTable($query);
        } elseif ($this->matchTagValues($this->join_by, 'sub_query')){
            $this->getJoinBySubQuery($query);
        }     
    }

    private function setTableName($join){
        $this->joined_table_name = $join['using']['name'];
    }

    private function setOption($join){
        $this->join_options = $join['using']['options'];
    }
    private function setJoinType($join){
        $this->join_type = $join['type'];
    }

    private function setJoinBy($join){
        $this->join_by = $join['by'];
    }

    private function getJoinName(){
        $join_name = $this->joined_table_name;
        if ($this->keyHasProperValue($this->join_options, 'alias')) {
            if ($this->matchTagValues($this->join_by, 'sub_query')) {
                $join_name = $this->join_options['alias'];
            } else {
                $join_name .= ' as ' . $this->join_options['alias'];
            }
            $this->joined_table_name = $this->join_options['alias']; // if alias is true then select query has to be generated with table's alias name
        }
        return $join_name;
    }

    private function getJoinByTable($query){
        $indepth_constraints = ['on' => $this->join_options['on']/*, 'where' => $this->join_options['onWhere']*/];
        $query->{$this->join_type}($this->getJoinName(), function (JoinClause $joinQuery) use ($indepth_constraints) {
            foreach ($indepth_constraints['on'] as $key => $value) {
                $joinQuery->{$value['type']}($value['left_side'], '=', $value['right_side']);
            }
            /*foreach ($indepth_constraints['onWhere'] as $key => $value) {
                $joinQuery->on($value['left_side'], '=', $value['right_side']);
            }*/
        });
    }
    private function getJoinBySubQuery($query){
        $sub_query_join_options = $this->join_options['sub_query'];
        $sub_query_join_options['table_name'] = $this->joined_table_name;

        $df_query_builder = new DFQueryBuilder($sub_query_join_options);
        $sub_query = $df_query_builder->createQuery();

        $indepth_constraints = ['on' => $this->join_options['on']/*, 'where' => $this->join_options['onWhere']*/];

        $query->joinSub($sub_query, $this->getJoinName(), function (JoinClause $joinQuery) use ($indepth_constraints) {
            foreach ($indepth_constraints['on'] as $key => $value) {
                $joinQuery->{$value['type']}($value['left_side'], '=', $value['right_side']);
            }
            /*foreach ($indepth_constraints['onWhere'] as $key => $value) {
                $joinQuery->on($value['left_side'], '=', $value['right_side']);
            }*/
        });
    }
}