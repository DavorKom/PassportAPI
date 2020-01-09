<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function exceptionTamplate($request, Exception $e, $status = 500, $errors = [])
    {
        return [
            'http_code' => $status,
            'message' => $e->getMessage(),
            'method' => $request->method(),
            'base_url' => $request->root(),
            'uri' => $request->path(),
            'query_parameters' => $request->query(),
            'errors' => $errors,
            'data' => []
        ];
    }

    protected function invalidJson($request, $exception)
    {
        $errors = [];
        $headers = [];
        $status = 500;

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $status = $exception->getStatusCode();
            $headers = $request->header();
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $status = $exception->status;
            $validation_errors = $exception->errors();

            $errors = array_map(function($value, $key) {
                return [
                    'key' => $key,
                    'message' => $value[0]
                ];
            }, $validation_errors, array_keys($validation_errors));
        }

        $response = $this->exceptionTamplate($request, $exception, $status, $errors);

        return response()->json($response, $status, $headers);
    }

    protected function invalid($request, $exception)
    {
        return $this->invalidJson($request, $exception);
    }

    protected function unauthenticated($request, $exception)
    {
        $response = $this->exceptionTamplate($request, $exception, 401);

        return response()->json($response, 401);
    }
}