<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\County;
use App\Services\CountyService;
use Tests\Feature\CustomTestCase;

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
        $county = County::factory()->make();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->route, $county->toArray());

        // assert
        $this->assertDatabaseHas(County::class, [
            'name' => $county->name,
            'region_id' => $county->region_id
        ]);

        $response->assertCreated()
            ->assertJsonPath('item.name', $county->name)
            ->assertJsonPath('item.region.id', $county->region->id);
    }
}
