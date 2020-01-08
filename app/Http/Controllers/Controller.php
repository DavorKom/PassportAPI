<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function out($data = [], $status = 200, $message = 'Success', array $headers = [])
    {
        $request = request();

        $response = [
            'http_code' => $status,
            'message'=> $message,
            'method' => $request->method(),
            'base_url' => $request->root(),
            'uri' => $request->path(),
            'query_parameters' => $request->query(),
            'errors' => [],
            'data' => $data
        ];

        return response()->json($response, $status, $headers);
    }

    protected function outWithErrors($errors = [], $status = 400, $message = 'Bad Request', array $headers = [])
    {
        $request = request();

        $response = [
            'http_code' => $status,
            'message'=> $message,
            'method' => $request->method(),
            'base_url' => $request->root(),
            'uri' => $request->path(),
            'query_parameters' => $request->query(),
            'errors' => $errors,
            'data' => []
        ];

        return response()->json($response, $status, $headers);
    }
}
