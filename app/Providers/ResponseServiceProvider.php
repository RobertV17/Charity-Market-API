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
        Response::macro('success', function ($message, $data) {
            return Response::json(
                [
                    'message' => $message,
                    'data'    => $data
                ]
            );
        });

        Response::macro('error', function ($data, $message = 'error', $code = 400) {
            return Response::json(
                [
                    'message' => $message,
                    'data'    => $data
                ],
                $code
            );
        });
    }
}
