<?php

namespace SmartCms\Kit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SmartCms\Kit\Kit
 */
class Kit extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SmartCms\Kit\Kit::class;
    }
}
