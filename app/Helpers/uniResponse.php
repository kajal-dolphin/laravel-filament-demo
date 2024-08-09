<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('uniResponse')) {
    function uniResponse($status = '', $message = '', $data = '', $http_code = 500): \Illuminate\Http\JsonResponse
    {
        $res = [];

        if (!is_null($status)) {
            $res['status'] = $status;
        }

        if (!is_null($message)) {
            $res['message'] = $message;
        }

        // Check if the data object / array is instance of paginator
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginate = [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ];
            $res['paginate'] = $paginate;
            $data = $data->items();
        }
        $res['data'] = $data;

        return response()->json($res, $http_code);
    }
}


if (!function_exists('logError')) {
    function logError($th, $abort = null)
    {
        Log::error($th->getMessage(), ['path' => dirname(__FILE__)]);
        if ($abort != null) {
            abort($abort);
        }
    }
}
