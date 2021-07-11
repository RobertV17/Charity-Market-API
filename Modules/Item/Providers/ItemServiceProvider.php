<?php

namespace Modules\Item\Providers;

use App\Providers\BaseModuleServiceProvider;

class ItemServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return 'Item';
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return 'Modules\Item';
    }
}
