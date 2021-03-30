<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

abstract class BaseModuleServiceProvider extends ServiceProvider
{
    /**
     * Module name
     *
     * @return string
     */
    abstract public function getModuleName(): string;

    /**
     * @return string
     */
    abstract public function getModuleNamespace(): string;

    /**
     * @return string
     */
    protected function getModulePath(): string
    {
        return base_path() . DIRECTORY_SEPARATOR . str_replace('\\', '/', static::getModuleNamespace());
    }

    public function boot(): void
    {
        $this->moduleBoot();
    }

    public function register(): void
    {
        //
    }

    protected function moduleBoot(): void
    {
        $this->loadMigrationsFrom($this->getModulePath() . DIRECTORY_SEPARATOR . 'Database/Migrations');
        $this->registerViews();
    }

    protected function registerViews(): void
    {
        if (is_dir($this->getModulePath().'/Resources/views')) {
            $this->loadViewsFrom($this->getModulePath().'/Resources/views', $this->getModuleName());
        }
    }
}
