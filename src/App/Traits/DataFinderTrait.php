<?php

/**
    * DataFinder Trait for the DataFinder package.
    *
    * This trait file is responsible for providing default complex function to the controller
    * to provide better readability to what are the corefeatures for the Datafinder
    *
    * @package SUHK\DataFinder
    *
*/

namespace SUHK\DataFinder\App\Traits;

use SUHK\DataFinder\App\Traits\ValidatorTrait;
use SUHK\DataFinder\App\Traits\Supports\JoinTrait;

trait DataFinderTrait
{
    use ValidatorTrait;

    public function applyFilters($query, $filters, $has_joins, $table_name)
    {
        $query->where(function ($query) use ($filters, $has_joins, $table_name) {
            foreach ($filters as $key => $filter) {
                $query->where(function ($multiQuery) use ($filter, $key, $has_joins, $table_name) {
                    foreach ($filter as $subFilterKey => $value) {
                        if ($has_joins) {
                            if ($this->keyHasProperValue($value, 'filter_through_join') && $value['filter_through_join'] == 'true') {
                                if ($this->keyHasProperValue($value, 'join_table')){
                                    $table_column = $value['join_table'] . '.' . $value['column_name'];
                                }
                            } else {
                                $table_column = $table_name . '.' . $value['column_name'];
                            }
                        } else {
                            $table_column = $value['column_name'];
                        }
                        $conditional_operator = !$this->keyHasProperValue($value, 'conditional_operator') ? '=' : $value['conditional_operator'];
                        if ($value['type'] == 'date') {
                            $multiQuery->whereDate($table_column, $conditional_operator, $value['value']);
                        } else if ($value['type'] == 'time'){
                            $multiQuery->whereTime($table_column, $conditional_operator, $value['value']);
                        } else if ($value['type'] == 'month'){
                            $multiQuery->whereMonth($table_column, $conditional_operator, $value['value']);
                        } else if ($value['type'] == 'year'){
                            $multiQuery->whereYear($table_column, $conditional_operator, $value['value']);
                        } else {
                            $multiQuery->orWhere($table_column, $conditional_operator, $value['value']);
                        }
                    }
                });
            }
        });
    }

    public function applySearch($query, $columns, $search, $table_name)
    {
        if (!empty($columns)) {
            $query->where(function ($query) use ($columns, $search, $table_name) {
                foreach ($columns as $key => $column) {
                    if (!$this->keyHasProperValue($column, 'column_name')) {
                        $this->setValidationError('{title}', $column['title'], $this->VALIDATION_MSG_SEARCH_COLUMN);
                    } else {
                        if ($this->keyHasProperValue($column, 'search_through_join') && $column['search_through_join']) {
                            if ($this->keyHasProperValue($column, 'table_name')) {
                                $query->orWhere($column['table_name'] . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                            } else {
                                $this->setValidationError('{title}', $column['title'], $this->VALIDATION_MSG_SEARCH_WITH_JOIN_COLUMN);
                            }
                        } else{
                            $query->orWhere($table_name . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                        }
                    }
                }
            });
        } else {
            $this->setValidationError('', '', $this->VALIDATION_MSG_NO_SEARCH_COLUMN);
        }
    }
    
    public function generateButtons($record, $table_buttons)
    {
        $record->actions = "<div class='btn-group' role='group' aria-label='Basic example'>";
        foreach ($table_buttons as $key => $button) {

            // $route = str_replace('{row_id}', $record->id, $request->routes[$button['route_key']]) ?? '#';
            $routeParam = [];
            foreach ($button['route']['params'] as $paramKey => $param) {
                $routeParam[$param['param_key']] = $record->{$param['data_key']};
            }
            foreach ($button['route']['additional_params'] as $additioalParamKey => $param) {
                $routeParam[$param['param_key']] = $record->{$param['data_key']};
            }
            $route = route($button['route']['name'], $routeParam);
            // $route = '#';
            $button_html = '<a';
            // add route to button
            $button_html .= ' href="' . $route . '"';
            // add tooltip to button
            $button_html .= $this->keyHasProperValue($button, 'tooltip') ? ' title="' . $button['tooltip'] . '"' : '';
            // add class to button
            $button_html .= $this->keyHasProperValue($button, 'class') ? ' class="' . $button['class'] . '"' : '';
            // add style to button
            $button_html .= $this->keyHasProperValue($button, 'style') ? ' style="' . $button['style'] . '"' : '';
            // close button opening tag
            $button_html .= '>';
            // add icon to button
            $button_html .= $this->keyHasProperValue($button, 'icon') ? ' <i class="' . $button['icon'] . '"></i> ' : '';
            // add label to button
            $button_html .= $this->keyHasProperValue($button, 'label') ? $button['label'] : '';
            // end button
            $button_html .= '</a>';
            $record->actions .= $button_html;
        }
        $record->actions .= '</div>';
    }
}