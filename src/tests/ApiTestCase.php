<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Route;
use Tests\TestCase as BaseTestCase;

abstract class ApiTestCase extends BaseTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Route::aliasMiddleware('api', SubstituteBindings::class);
    }
}
