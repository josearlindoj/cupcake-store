<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure session driver is set to array
        config(['session.driver' => 'array']);

        // Disable middleware that starts sessions
        $this->withoutMiddleware(
            \Illuminate\Session\Middleware\StartSession::class
        );
    }
}
