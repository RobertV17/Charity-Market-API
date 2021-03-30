<?php

namespace Modules\Api\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class ApiRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @var string
     */
    protected $moduleNamespace = 'Modules\Api\Http\Controllers';

    public function map(): void
    {
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(base_path('Modules/Api/Routes/api.php'));
    }
}
