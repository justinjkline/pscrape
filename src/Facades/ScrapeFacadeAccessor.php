<?php

namespace Pscrape\Pscrape\Facades;
use Illuminate\Support\Facades\Facade;

class ScrapeFacadeAccessor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pscrape.scrape';
    }
}
