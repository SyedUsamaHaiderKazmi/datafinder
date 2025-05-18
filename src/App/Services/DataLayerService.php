<?php

namespace SUHK\DataFinder\App\Services;

use SUHK\DataFinder\App\Helpers\ConfigParser;
use SUHK\DataFinder\App\Helpers\ConfigGlobal;
use SUHK\DataFinder\App\Traits\DataFinderTrait;

/**
    * DataFinder Data Layer Service for the DataFinder package.
    *
    * This service file is responsible for providing default complex function to the controller or other files
    * to provide better readability to what are the corefeatures for the Datafinder in regards to its business layer
    *
    * @package SUHK\DataFinder
    *
*/
class DataLayerService
{
    use DataFinderTrait;

    private $request;

    public $total_filtered;
    public $total_data;
    public $exportable_by_chunk;
    public $exportable_chunk_size;
    public $table_header_columns;

	function __construct($request)
	{
		$this->request = $request;
        if (isset($request->exportable) && $request->exportable) {
            $this->table_header_columns = ConfigParser::getTableColumnsForExport($this->request->config_file_name);
            $this->exportable_by_chunk = ConfigParser::isExportableByChunk($this->request->config_file_name);
            $this->exportable_chunk_size = ConfigParser::getExportableChunkSize($this->request->config_file_name);
        } else {
            $this->table_header_columns = ConfigParser::getTableColumnsConfiguation($this->request->config_file_name);
        }
	}

	public function getData()
	{
		$query = $this->getQuery();

        if($this->request->has('start') && $this->request->input('start') > 0){
            $start = $this->request->input('start');
            $query->skip($start);
        }
        if($this->request->has('length') && $this->request->input('length') > 0){
            $limit = $this->request->input('length');
            $query->take($limit);
        }
        
        $records = $query->get();

        if (!empty($records)) {
            $table_has_buttons = ConfigParser::tableHasRowButtons($this->request->config_file_name);
            if ($table_has_buttons) {
                $this->table_buttons = ConfigParser::tableRowButtons($this->request->config_file_name);
            }
            foreach ($records as $record) {
                if ($table_has_buttons) {
                    $this->generateButtons($record, $this->table_buttons);
                }
            }
        }

        return $records;
	}

	public function getQuery()
	{
		// dd($this->request->all());
        $table_name = ConfigParser::getTableName($this->request->config_file_name);
        $MODEL = ConfigParser::getModelPath($this->request->config_file_name);
        $this->total_data = 0;
        $this->total_filtered = $this->total_data;

        $query = $MODEL::query();
        $table_has_selective_query = ConfigParser::tableHasSelectiveColumns($this->request->config_file_name);
        if ($table_has_selective_query) {
            $columns = ConfigParser::tableSelectiveColumns($this->request->config_file_name);
            $this->setupSelectQuery($query, $table_has_selective_query, $table_name, $columns);
        }
        $has_joins = ConfigParser::hasJoins($this->request->config_file_name);
        if ($has_joins) {
            $this->setJoins($query, ConfigParser::getJoins($this->request->config_file_name));
        }

        $search = $this->request->input('search.value');

        if ($this->request->filters) {
            $filters = $this->request->filters;
            $this->applyFilters($query, $filters, $has_joins, $table_name);
        }
        // dd($query->toSql());
        $totalDataQuery = clone $query;
        $this->total_data = $totalDataQuery->count();

        if (empty(!$search)) {
            $searchable_columns = ConfigParser::getTableSearchableColumns($this->request->config_file_name);
            $this->applySearch($query, $searchable_columns, $search, $table_name);
        }
        
        $this->total_filtered = $query->count();
        
        if($this->request->has('order')){
            if (count($this->table_header_columns) > 0) {
                if (isset($this->table_header_columns[$this->request->input('order.0.column')])) {
                    $order =  $this->table_header_columns[$this->request->input('order.0.column')]['data'];
                } else {
                    $order =  $this->table_header_columns[0]['data'];
                }
                $dir = $this->request->input('order.0.dir');
                $query->orderBy($order, $dir); 
            }
        }

        return $query;
	}
}