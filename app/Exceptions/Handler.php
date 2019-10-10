<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        \InvalidArgumentException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        /**
         * Validar erro
         */
        $httpCode = 500;
        $code = $e->getCode();
        $message = $e->getMessage();

        if ($e instanceof ValidationException) {
            $message = $e->validator->getMessageBag();
            $httpCode = 422;
        }
        else if ($e instanceof \InvalidArgumentException)
            $httpCode =  ($code > 0 ? $code : 422);

        return response()->json([
            "code" => ($code > 0 ? $code : $httpCode),
            "message" => $message
        ], $httpCode);

        //return parent::render($request, $e);
    }
}
