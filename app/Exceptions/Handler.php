<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
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
     * @param \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return Response
     *
     */
    public function render($request, Throwable $e)
    {


        /**
         * Render an Authentification exception when user trying to viditing a route or
         * Perform an action is not properly authenticated
         */
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {


            return $this->unauthenticated($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $exception->redirectTo()
            ? redirect()->guest($exception->redirectTo())
            : response()->json(['message' => $exception->getMessage()], 401);
    }

}
