<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use SUHK\DataFinder\Helpers\ConfigParser;
use SUHK\DataFinder\Helpers\ConfigGlobal;
use Illuminate\Support\Facades\Route;
use Exception;

class DataSearchController extends Controller
{

    private $errors = [];

    // error messages
    // Searchable Specific Error Handlers
    private $EXCP_MSG_NO_SEARCH_COLUMN = 'No <b>searchable</b> column found!';
    private $EXCP_MSG_SEARCH_COLUMN = 'Property: <b>column_name</b> is not defined for the searchable column <b>"{title}"</b>';
    private $EXCP_MSG_SEARCH_WITH_JOIN_COLUMN = 'Property: <b>table_name</b> is not defined for the searchable column <b>"{title}"</b> through joins!';

    public function liveSearchTableRender(Request $request)
    {
        try {

            $table_name = ConfigParser::getTableName($request->config_file_name);
            $MODEL = ConfigParser::getModelPath($request->config_file_name);
            $table_header_columns = ConfigParser::getTableColumnsConfiguation($request->config_file_name);
            $totalData = 0;
            $totalFiltered = $totalData;

            $query = $MODEL::query();
            $table_has_selective_query = ConfigParser::tableHasSelectiveColumns($request->config_file_name);
            if ($table_has_selective_query) {
                $columns = ConfigParser::tableSelectiveColumns($request->config_file_name);
                $this->setupSelectQuery($query, $table_has_selective_query, $table_name, $columns);
            }
            $has_joins = ConfigParser::hasJoins($request->config_file_name);
            if ($has_joins) {
                $this->setJoins($query, ConfigParser::getJoins($request->config_file_name));
                // $this->getSelectQuery($query, ConfigParser::getValuesForSelectQuery($request->config_file_name));
                // $query->select($this->getSelectQuery($select))->distinct();
                // dd($this->getSelectQuery($select));
            }

            $search = $request->input('search.value');

            if ($request->filters) {
                $filters = $request->filters;
                $this->applyFilters($query, $filters, $has_joins, $table_name);
            }
            
            $totalDataQuery = clone $query;
            $totalData = $totalDataQuery->count();

            if (empty(!$search)) {
                $searchable_columns = ConfigParser::getTableSearchableColumns($request->config_file_name);
                $this->applySearch($query, $searchable_columns, $search, $table_name);
            }
            
            $totalFiltered = $query->count();

            if($request->has('start') && $request->input('start') > 0){
                $start = $request->input('start');
                $query->skip($start);
            }
            if($request->has('length') && $request->input('length') > 0){
                $limit = $request->input('length');
                $query->take($limit);
            }
            if($request->has('order')){
                $order =  $table_header_columns[$request->input('order.0.column')]['data'];
                $dir = $request->input('order.0.dir');
                $query->orderBy($order, $dir); 
            }
            
            $records = $query->get();

            $data = array();

            if (!empty($records)) {
                foreach ($records as $record) {
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
                }
            }

            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $records->toArray(),
                "errors" => $this->errors,

            );
            echo json_encode($json_data);   
        } catch (QueryException $e) {
            $this->errors[] = $e->getmessage();
            // Handle database-related errors (like SQL issues)
            return response()->json([
                'message' => 'Database query failed: ' . $e->getMessage(),
                'errors' => $this->errors
            ], 500);
        }  catch (Exception $e) {
            $this->errors[] = $e->getmessage();
            return response()->json([
                'message' => $e->getmessage(),
                'errors' => $this->errors
            ], 500);
        }
    }

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
                        $this->returnExceptionMessage('{title}', $column['title'], $this->EXCP_MSG_SEARCH_COLUMN);
                    } else {
                        if ($this->keyHasProperValue($column, 'search_through_join') && $column['search_through_join']) {
                            if ($this->keyHasProperValue($column, 'table_name')) {
                                $query->orWhere($column['table_name'] . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                            } else {
                                $this->returnExceptionMessage('{title}', $column['title'], $this->EXCP_MSG_SEARCH_WITH_JOIN_COLUMN);
                            }
                        } else{
                            $query->orWhere($table_name . '.' . $column['column_name'], 'LIKE', "%{$search}%");
                        }
                    }
                }
            });
        } else {
            $this->returnExceptionMessage('', '', $this->EXCP_MSG_NO_SEARCH_COLUMN);
        }
    }

    public function setupSelectQuery($query, $selective_column, $table_name, $columns){
        if ($selective_column) {
            foreach ($columns as $key => $column) {
                if (strcasecmp($column['type'], 'default') == 0) {
                    if ($column['column'] == '*') {
                        $raw_query = $table_name . '.' . $column['column'];
                        $query->addSelect($raw_query);
                    }
                    if ($column['column'] != '*' && $column['alias']) {
                        $raw_query = $table_name . '.' . $column['column'] . ' as ' . $column['alias'];
                        $query->addSelect($raw_query);
                    }
                } elseif (strcasecmp($column['type'], 'raw') == 0) {
                    $query->addSelect(\DB::raw($column['column']));
                }
                // array_push($select_raw, $raw_query);
            }
        } else {
            $query->addSelect($table_name . '.*'); /* WIll get all the data for all columns if the array is empty*/
            // array_push($select_raw, $table_name . '.*');
        }
    }
    
    public function returnExceptionMessage($replace, $with, $from){
        $this->errors[] = str_replace($replace, $with, $from);
    }

    public function keyHasProperValue($object, $value)
    {
        return (isset($object[$value]) && $object[$value] != null && $object[$value] != '') ? true : false;
    }
}
