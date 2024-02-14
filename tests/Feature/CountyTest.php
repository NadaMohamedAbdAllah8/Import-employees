<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\County;

class CountyTest extends CustomTestCase
{
    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->route = $this->base_route.'counties/';
    }

    public function test_store_county_successfully()
    {
        // arrange
        $county = County::factory()->make();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->route, $county->toArray());

        // assert
        $this->assertDatabaseHas(County::class, [
            'name' => $county->name,
            'region_id' => $county->region_id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('item.name', $county->name)
            ->assertJsonPath('item.region.id', $county->region->id);
    }

    public function test_update_county_successfully()
    {
        // arrange
        $old_county = County::factory()->create();

        $county = County::factory()->make();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->route.$old_county->id, $county->toArray());

        // assert
        $this->assertDatabaseHas(County::class, [
            'id' => $old_county->id,
            'name' => $county->name,
            'region_id' => $county->region_id,
        ]);

        $response->assertSuccessful()
            ->assertJsonPath('item.id', $old_county->id)
            ->assertJsonPath('item.name', $county->name)
            ->assertJsonPath('item.region.id', $county->region->id);
    }

    public function test_delete_county_successfully()
    {
        // arrange
        $county = County::factory()->create();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->deleteJson($this->route.$county->id);

        // assert
        $this->assertDatabaseMissing(County::class, [
            'id' => $county->id,
            'name' => $county->name,
            'region_id' => $county->region_id,
        ]);

        $response->assertNoContent();
    }
}
