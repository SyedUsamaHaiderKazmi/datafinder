<?php

namespace SUHK\DataFinder\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use SUHK\DataFinder\App\Services\DataLayerService;
use SUHK\DataFinder\App\Traits\ValidatorTrait;
use Exception;

class DataSearchController extends Controller
{

    private $table_buttons = [];

    public function liveSearchTableRender(Request $request)
    {
        try {
            $data_layer = new DataLayerService($request);
            $data = $data_layer->getData();
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($data_layer->total_data),
                "recordsFiltered" => intval($data_layer->total_filtered),
                "data" => $data->toArray(),
                "errors" => $data_layer->getErrors(),

            );
            echo json_encode($json_data);

        } catch (QueryException $e) {
            $error_message = $e->getmessage();
            if (!is_null($e->getPrevious()) && isset($e->getPrevious()->errorInfo[2])) {
                $error_message = $e->getPrevious()->errorInfo[2];
            }
            $data_layer->setExceptionError($error_message);
            // Handle database-related errors (like SQL issues)
            return response()->json([
                'message' => 'Database query failed: ' . $e->getMessage(),
                'errors' => $data_layer->getErrors()
            ], 500);
        } catch (Exception $e) {
            $data_layer->setExceptionError($e->getmessage());
            return response()->json([
                'message' => $e->getmessage(),
                'errors' => $data_layer->getErrors()
            ], 500);
        }
    }
}
