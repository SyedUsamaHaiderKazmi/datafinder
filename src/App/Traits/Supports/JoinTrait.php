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
use SUHK\DataFinder\App\Traits\Supports\SubQueryTrait;

trait JoinTrait
{

    use SubQueryTrait;

    protected $join_type = '';
    protected $join_by = '';
    protected $joined_table_name = '';
    protected $options = [];

    public function joinsInit($join)
    {
        $this->setTableName($join);
        $this->setOption($join);
        $this->setJoinType($join);
        $this->setJoinBy($join);
    }

    public function attachJoin($query){
        if (strcasecmp($this->join_by, 'table') == 0) {
            $this->getJoinByTable($query);
        } elseif (strcasecmp($this->join_by, 'sub_query') == 0){
            $this->getJoinBySubQuery($query);
        }     
    }

    private function setTableName($join){
        $this->joined_table_name = $join['using']['name'];
    }

    private function setOption($join){
        $this->options = $join['using']['options'];
    }
    private function setJoinType($join){
        $this->join_type = $join['type'];
    }

    private function setJoinBy($join){
        $this->join_by = $join['by'];
    }

    private function getJoinName(){
        $join_name = $this->joined_table_name;
        if ($this->keyHasProperValue($this->options, 'alias')) {
            if (strcasecmp($this->join_by, 'sub_query') == 0) {
                $join_name = $this->options['alias'];
            } else {
                $join_name .= ' as ' . $this->options['alias'];
            }
            $this->joined_table_name = $this->options['alias']; // if alias is true then select query has to be generated with table's alias name
        }
        return $join_name;
    }

    private function getJoinByTable($query){
        $indepth_constraints = ['on' => $this->options['on']/*, 'where' => $this->options['onWhere']*/];
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
        $sub_query_options = $this->options['sub_query'];

        $this->subQueryInit($sub_query_options);
        $sub_query = $this->getNeatQuery($this->joined_table_name);
        $indepth_constraints = ['on' => $this->options['on']/*, 'where' => $this->options['onWhere']*/];

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