<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\County;
use App\Services\CountyService;
use Tests\CustomTestCase;

class CountyTest extends CustomTestCase
{
    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->route = $this->base_route . 'counties';
    }

    public function test_store_successfully()
    {
        // arrange
        $service_mock = $this->mock(CountyService::class);

        $county = County::factory()->make();

        $service_mock->shouldReceive('createOne')
            ->once()
            ->andReturn($county);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->route, $county->toArray());

        // assert
        $response->assertCreated()
            ->assertJsonPath('item.name', $county->name)
            ->assertJsonPath('item.region.id', $county->region->id);
    }
}
