<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
            if ($exception instanceof UnauthorizedHttpException) {
                return response()->error(null, $exception->getMessage(), 401);
            }

            if ($exception instanceof NotFoundHttpException) {
                if ($exception->getMessage() === '') {
                    $message = 'The specified URL cannot be found';
                } else {
                    $message = $exception->getMessage();
                }

                return response()->error(null, $message, 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->error(null, 'The specified method for the request is invalid', 405);
            }

            if ($exception instanceof HttpException) {
                return response()->error(null, $exception->getMessage(), $exception->getStatusCode());
            }

            if ($exception instanceof AccessDeniedHttpException) {
                return response()->error(null, $exception->getMessage(), $exception->getStatusCode());
            }

            return response()->error(null, 'Unexpected Exception. Try later', 500);
        }

        return parent::render($request, $exception);
    }
}
