<?php

declare(strict_types=1);

namespace AvonezAuthClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AvonezAuthClient\AvonezAuthClient
 */
class AvonezAuthClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AvonezAuthClient\AvonezAuthClient::class;
    }
}
