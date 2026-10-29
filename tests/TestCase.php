<?php

declare(strict_types=1);

namespace AvonezAuthClient\Tests;

use AvonezAuthClient\AvonezAuthClientServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            AvonezAuthClientServiceProvider::class,
        ];
    }
}
