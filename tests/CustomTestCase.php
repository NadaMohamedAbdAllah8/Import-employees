<?php

namespace Tests;

use App\Constants\HttpHeader;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

abstract class CustomTestCase extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public string $base_route;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->base_route = '/api/';
    }
}
