<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class CustomTestCase extends TestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use RefreshDatabase;

    public string $base_route;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->base_route = '/api/';
    }
}
