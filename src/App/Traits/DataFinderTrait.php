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

trait DataFinderTrait
{
    use ValidatorTrait;

    public function setJoins($query, $joins){
        foreach ($joins as $key => $join) {
            $query->leftjoin($join['join_with_table'], $join['reference_in_current'], '=', $join['reference_in_join']);
            $this->setupSelectQuery($query, $join['selective_columns'], $join['join_with_table'], $join['columns']);
        }
    }

    public function applyFilters($query, $filters, $has_joins, $table_name)
    {
        $query->where(function ($query) use ($filters, $has_joins, $table_name) {
            foreach ($filters as $key => $filter) {
                $query->where(function ($multiQuery) use ($filter, $key, $has_joins, $table_name) {
                    foreach ($filter as $subFilterKey => $value) {
                        if ($value['filter_through_join'] == 'true' && $value['join_table'] != null) {
                            $multiQuery->orWhere($has_joins ? ($value['join_table'] . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                        } else {
                            if ($value['type'] == 'date') {
                                if (!is_null($value['value'])) {
                                    $multiQuery->whereDate($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                                }
                            } else {

                                $multiQuery->orWhere($has_joins ? ($table_name . '.' . $key) : $key, $value['conditional_operator'] == null ? '=' : $value['conditional_operator'], $value['value']);
                            }
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

    public function setupSelectQuery($query, $selective_column, $table_name, $columns){
        if ($selective_column) {
            foreach ($columns as $key => $column) {
                if (strcasecmp($column['type'], 'default') == 0) {
                    if ($column['column_name'] == '*') {
                        $raw_query = $table_name . '.' . $column['column_name'];
                        $query->addSelect($raw_query);
                    }
                    if ($column['column_name'] != '*' && $this->keyHasProperValue($column, 'alias')) {
                        $raw_query = $table_name . '.' . $column['column_name'] . ' as ' . $column['alias'];
                        $query->addSelect($raw_query);
                    }
                } elseif (strcasecmp($column['type'], 'raw') == 0) {
                    $query->addSelect(\DB::raw($column['column_name']));
                }
                // array_push($select_raw, $raw_query);
            }
        } else {
            $query->addSelect($table_name . '.*'); /* WIll get all the data for all columns if the array is empty*/
            // array_push($select_raw, $table_name . '.*');
        }
    }
    
    public function generateButtons($record, $table_buttons)
    {
        $record->options = "<div class='btn-group' role='group' aria-label='Basic example'>";
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
            $record->options .= $button_html;
        }
        $record->options .= '</div>';
    }
}