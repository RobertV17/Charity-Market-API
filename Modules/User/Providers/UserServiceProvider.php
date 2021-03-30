<?php


namespace Modules\User\Providers;


use App\Providers\BaseModuleServiceProvider;

class UserServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'User';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\User';
    }
}
