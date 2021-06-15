<?php


namespace App\Providers;


use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($message, $data, $code = 200) {
            return Response::json(
                [
                    'status'  => 'success',
                    'message' => $message,
                    'data'    => $data
                ],
                $code
            );
        });

        Response::macro('fail', function ($data, $message = 'Request was failed', $code = 403) {
            return Response::json(
                [
                    'status'  => 'fail',
                    'message' => $message,
                    'data'    => $data
                ],
                $code
            );
        });

        Response::macro('error', function ($data, $message = 'Server error', $code = 500) {
            return Response::json(
                [
                    'status'  => 'error',
                    'message' => $message,
                    'data'    => $data
                ],
                $code
            );
        });
    }
}
