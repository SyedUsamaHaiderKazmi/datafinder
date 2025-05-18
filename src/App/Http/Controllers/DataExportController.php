<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use SUHK\DataFinder\App\Services\DataLayerService;
use Exception;

class DataExportController extends Controller
{

    public function init(Request $request)
    {
        $data_layer = new DataLayerService($request);
        $query = $data_layer->getQuery();

        $header_columns = $data_layer->table_header_columns;
        
        if ($data_layer->exportable_by_chunk) {

            if ($data_layer->exportable_chunk_size == null) {
                $data_layer->exportable_chunk_size = 0;
            }
            $offset = $request->input('offset', 0);
            $results = $query->skip($offset)
                         ->take($data_layer->exportable_chunk_size)
                         ->get()->toArray();

            $next_offset = $offset + $data_layer->exportable_chunk_size;
            $hasMore = $data_layer->total_filtered > $next_offset;
            $completed_percentage = ((count($results)+$offset)/$data_layer->total_filtered) * 100;
        } else {
            $results = $query->get()->toArray();
        }
        
        $ordered_data = $this->generateKeyPairOrderedRecords($results, $header_columns);

        return response()->json([
            'data' => $ordered_data,
            'next_offset' => $hasMore ? $next_offset : null,
            'completed_percentage' => number_format($completed_percentage, 2). "%",
        ]);
    }

    function generateKeyPairOrderedRecords(array $records, array $header_columns)
    {
        // Extract the keys in order (e.g. ['first_name', 'last_name', ...])
        $keys = array_column($header_columns, 'data');
        $titles = array_column($header_columns, 'title');

        return array_map(function ($record) use ($keys, $titles) {
            // Build new ordered array per record
            $ordered = [];
            foreach ($keys as $index => $key) {
                $ordered[$titles[$index]] = $record[$key] ?? null;
            }
            return $ordered;
        }, $records);
    }

    function generateValueOrderedRecords(array $records, array $header_columns)
    {
        // Extract the keys in order (e.g. ['first_name', 'last_name', ...])
        $keys = array_column($header_columns, 'data');

        return array_map(function ($record) use ($keys) {
            // Build new ordered array per record
            $ordered = [];
            foreach ($keys as $key) {
                $ordered[$key] = $record[$key] ?? null;
            }
            return array_values($ordered);
        }, $records);
    }
}
