<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof AuthenticationException) {
                return response()->fail(null, $exception->getMessage(), 401);
            }

            if ($exception instanceof NotFoundHttpException) {
                if ($exception->getMessage() === '') {
                    $message = 'The specified URL cannot be found';
                } else {
                    $message = $exception->getMessage();
                }

                return response()->fail(null, $message, 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->fail(null, 'The specified method for the request is invalid', 405);
            }

            if ($exception instanceof AccessDeniedHttpException) {
                return response()->fail(null, $exception->getMessage(), $exception->getStatusCode());
            }

            if ($exception instanceof HttpException) {
                return response()->fail(null, $exception->getMessage(), $exception->getStatusCode());
            }

            return response()->error($this->getDebugInfo($exception), 'Unexpected Exception. Try later', 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * @param Throwable $exception
     * @return array|null
     */
    public function getDebugInfo(Throwable $exception): ?array
    {
        if (env('APP_DEBUG')) {
            return $exception->getTrace();
        }

        return null;
    }
}
