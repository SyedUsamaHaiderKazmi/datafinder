<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use SUHK\DataFinder\Helpers\ConfigParser;
use SUHK\DataFinder\Helpers\ConfigGlobal;
use Illuminate\Support\Facades\Route;

class DataSearchController extends Controller
{

    public function liveSearchTableRender(Request $request)
    {

        $table_name = ConfigParser::getTableName($request->config_file_name);
        $MODEL = ConfigParser::getModelPath($request->config_file_name);
        $table_header_columns = ConfigParser::getTableColumnsConfiguation($request->config_file_name);
        $totalData = 0;
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $table_header_columns[$request->input('order.0.column')]['data'];
        $dir = $request->input('order.0.dir');
        $filters = $request->filters;
        // dd($filters);
        $query = $MODEL::query();
        $this->setupSelectQuery($query, ConfigParser::tableHasSelectiveColumns($request->config_file_name), $table_name, ConfigParser::tableSelectiveColumns($request->config_file_name));
        $has_joins = ConfigParser::hasJoins($request->config_file_name);
        if ($has_joins) {
            $this->setJoins($query, ConfigParser::getJoins($request->config_file_name));
            // $this->getSelectQuery($query, ConfigParser::getValuesForSelectQuery($request->config_file_name));
            // $query->select($this->getSelectQuery($select))->distinct();
            // dd($this->getSelectQuery($select));
        }
        // sums of columns

        $search = $request->input('search.value');

        $this->applyFilters($query, $filters, $has_joins, $table_name);

        $totalDataQuery = clone $query;
        $totalData = $totalDataQuery->count();

        if (empty(!$search)) {
            $searchable_columns = ConfigParser::getTableSearchableColumns($request->config_file_name);
            $this->applySearch($query, $searchable_columns, $search, $table_name);

        }
        // dd($query->orderBy($order, $dir)->get()->unique('id')->toArray());
        
        $totalFiltered = $query->count();
        $records = $query->skip($start)->take($limit)->orderBy($order, $dir)->get();
        // dd($start, $order, $dir, $query->count());

        $data = array();
        // dd($records->toArray());
        if (!empty($records)) {
            // on joins sql add same parent row multiple times according to the number of rows in child table. SO we had to do below thing.
            $ids_only = $records->pluck('id');
            $added_ids = [];
            // This above array will be used to compare and see if same aray is not being added to draw on table.
            foreach ($records as $record) {
                $arrayRecord = $record->toArray();

                if (ConfigParser::tableHasRowButtons($request->config_file_name)) {
                    $record->options = "<div class='btn-group' role='group' aria-label='Basic example'>";
                    foreach (ConfigParser::tableRowButtons($request->config_file_name) as $key => $button) {

                        // $route = str_replace('{row_id}', $record->id, $request->routes[$button['route_key']]) ?? '#';
                        $routeParam = [];
                        foreach ($button['route']['params'] as $key => $param) {
                            $routeParam[] = $record->$param;
                        }
                        $route = route($button['route']['name'], $routeParam);
                        // $route = '#';
                        $record->options .= "<a href='" . $route 
                            . (!is_null($button['tooltip']) ? "' title='" . $button['tooltip'] . "'" : '') 
                            . (!is_null($button['class']) ? " class='" . $button['class'] . "'" : '')
                            . (!is_null($button['style']) ? " style='" . $button['style'] . "'" : '') 
                            . ">"
                            . (!is_null($button['icon']) ? "<i class='" . $button['icon'] . "'></i>" : "")
                            . (!is_null($button['label']) ? " " . $button['label'] : "") . "</a>";

                    }
                    $record->options .= '</div>';
                }
                $data[] = $record->toArray();
                array_push($added_ids, $record->id);

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,

        );
        echo json_encode($json_data);   
    }

    public function setJoins($query, $joins){
        foreach ($joins as $key => $join) {
            $query->leftjoin($join['join_with_table'], $join['reference_in_current'], $join['conditional_sign'], $join['reference_in_join']);
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
                // $query->where($key, $filter);
            }
        });
    }

    public function applySearch($query, $columns, $search, $table_name)
    {
        $query->where(function ($query) use ($columns, $search, $table_name) {
            foreach ($columns as $key => $column) {
                if ($column['search_through_join']) {
                    $query->orWhere($column['table_name'] . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                } else{
                    $query->orWhere($table_name . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                }
            }
        });
    }

    public function setupSelectQuery($query, $selective_column, $table_name, $columns){
        if ($selective_column) {
            foreach ($columns as $key => $value) {
                if (isset($value['sum'])) {
                    foreach ($value['sum'] as $sum) {
                        if (isset($sum['where'])) {
                            $sum_raw_query = 'SUM(CASE WHEN ' . str_replace(['lessthan', 'greaterthan'], ['<', '>'], $sum['where']['when_clause']) . ' THEN ' . $table_name . '.' . $value['column_name'] . ' ELSE ' . $sum['where']['else_clause'] . ' END' . ') as ' . $sum['as'];
                        } else {
                            $sum_raw_query = 'SUM(' . $table_name . '.' . $value['column_name'] . ') as ' . $sum['as'];
                        }
                        // dd(\DB::raw($sum_raw_query));
                        // array_push($select_raw, \DB::raw($sum_raw_query));
                        // $query->addSelect(\DB::raw($sum_raw_query));
                    }
                }
                if (isset($value['count'])) {
                    // will be written in future as per the need.
                }
                $raw_query = $table_name . '.' . $value['column_name'] . ($value['as'] ? ' as ' . $value['as'] : '');
                $query->addSelect($raw_query);
                // array_push($select_raw, $raw_query);
            }
        } else {
            $query->addSelect($table_name . '.*'); /* WIll get all the data for all columns if the array is empty*/
            // array_push($select_raw, $table_name . '.*');
        }
    }
}
