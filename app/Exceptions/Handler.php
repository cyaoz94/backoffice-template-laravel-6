<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

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
     * @param \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'code' => 1000,
                'message' => $exception->getMessage(),
            ], 400);
        }

        if ($exception instanceof AuthorizationException || $exception instanceof UnauthorizedException) {
            return response()->json([
                'code' => 1001,
                'message' => $exception->getMessage(),
            ], 403);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'code' => 1002,
                'message' => $exception->errors(),
            ], 400);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'code' => 1003,
                'message' => $exception->getMessage(),
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
