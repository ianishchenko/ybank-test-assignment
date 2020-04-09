<?php

namespace App\Traits;

use Illuminate\Http\Request;

/**
 * Trait RestTrait
 * @package App\Traits
 */
trait RestTrait
{
    /**
     * Determines if request is an api call.
     *
     * If the request URI contains 'api' part.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        return $request->is('api/*');
    }
}
