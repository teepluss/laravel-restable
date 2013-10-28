<?php namespace Teepluss\Restable\Facades;

use Illuminate\Support\Facades\Facade;

class Restable extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'restable'; }

}