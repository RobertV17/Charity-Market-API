<?php

namespace Modules\Api\Providers;


use App\Providers\BaseModuleServiceProvider;

class ApiModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Api';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Api';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(ApiRouteServiceProvider::class);
    }
}
