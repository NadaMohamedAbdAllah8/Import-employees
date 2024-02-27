<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\City;

class CityTest extends CustomTestCase
{
    private string $route;

    public function setUp(): void
    {
        parent::setUp();
        $this->route = $this->base_route.'cities/';
    }

    public function test_store_city_successfully()
    {
        // arrange
        $city = City::factory()->make();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->route, $city->toArray());

        // assert
        $this->assertDatabaseHas(City::class, [
            'name' => $city->name,
            'county_id' => $city->county_id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('item.name', $city->name)
            ->assertJsonPath('item.county.id', $city->county->id);
    }

    public function test_store_city_with_repeated_name_and_county_unsuccessfully()
    {
        // arrange
        $old_city = City::factory()->create();

        $new_city = City::factory()->make([
            'name' => $old_city->name,
            'county_id' => $old_city->county_id,
        ]);

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->postJson($this->route, $new_city->toArray());

        // assert
        $response->assertUnprocessable();
    }

    public function test_update_city_successfully()
    {
        // arrange
        $old_city = City::factory()->create();

        $city = City::factory()->make();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->patchJson($this->route.$old_city->id, $city->toArray());

        // assert
        $this->assertDatabaseHas(City::class, [
            'id' => $old_city->id,
            'name' => $city->name,
            'county_id' => $city->county_id,
        ]);

        $response->assertSuccessful()
            ->assertJsonPath('item.id', $old_city->id)
            ->assertJsonPath('item.name', $city->name)
            ->assertJsonPath('item.county.id', $city->county->id);
    }

    public function test_delete_city_successfully()
    {
        // arrange
        $city = City::factory()->create();

        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'api')
            ->deleteJson($this->route.$city->id);

        // assert
        $this->assertDatabaseMissing(City::class, [
            'id' => $city->id,
            'name' => $city->name,
            'county_id' => $city->county_id,
        ]);

        $response->assertNoContent();
    }
}
