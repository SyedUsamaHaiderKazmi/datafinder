<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use SUHK\DataFinder\App\Helpers\ConfigParser;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;
use SUHK\DataFinder\App\Traits\DataFinderTrait;
use Illuminate\Support\Facades\Route;
use Exception;

class DataSearchController extends Controller
{
    use DataFinderTrait;

    private $errors = [];
    private $table_buttons = [];

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

            if (!empty($records)) {
                $table_has_buttons = ConfigParser::tableHasRowButtons($request->config_file_name);
                if ($table_has_buttons) {
                    $this->table_buttons = ConfigParser::tableRowButtons($request->config_file_name);
                }
                foreach ($records as $record) {
                    if ($table_has_buttons) {
                        $this->generateButtons($record, $this->table_buttons);
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
}
