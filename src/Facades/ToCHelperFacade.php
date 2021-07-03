<?php

namespace Botble\ToC\Facades;

use Botble\ToC\ToCHelper;
use Illuminate\Support\Facades\Facade;

class ToCHelperFacade extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ToCHelper::class;
    }
}
