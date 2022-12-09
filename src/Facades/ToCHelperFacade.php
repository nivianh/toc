<?php

namespace Plugin\ToC\Facades;

use Plugin\ToC\ToCHelper;
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
