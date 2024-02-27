<?php

namespace Tests\Unit;

use App\DataTransferObjects\CityDTO;
use App\Models\City;
use App\Services\CityService;
use Mockery;
use PHPUnit\Framework\TestCase;

class CityServiceTest extends TestCase
{
    private string $city_name;

    private string $city_name_not_unique;

    private int $county_id;

    public function setUp(): void
    {
        parent::setUp();

        $this->city_name = 'city_name';
        $this->city_name_not_unique = $this->city_name;
        $this->county_id = 1;
    }

    public function test_create_city_successfully()
    {
        // arrange
        $service_mock = Mockery::mock(CityService::class);

        $city_dto = new CityDTO($this->city_name, $this->county_id);

        $service_mock->shouldReceive('createOne')
            ->once()
            ->andReturn(new City());

        // act
        $result = $service_mock->createOne($city_dto);

        // assert
        $this->assertInstanceOf(City::class, $result);
    }

    public function test_update_city_successfully()
    {
        // arrange
        $service_mock = Mockery::mock(CityService::class);

        $city_dto = new CityDTO($this->city_name, $this->county_id);

        $service_mock->shouldReceive('updateOne')
            ->once()
            ->andReturn(new City());

        // act
        $result = $service_mock->updateOne($city_dto, new City());

        // assert
        $this->assertInstanceOf(City::class, $result);
    }

    public function test_delete_city_successfully()
    {
        // arrange
        $service_mock = Mockery::mock(CityService::class);

        $service_mock->shouldReceive('deleteOne')
            ->once()
            ->andReturn(true);

        // act
        $result = $service_mock->deleteOne(new City());

        // assert
        $this->assertTrue($result);
    }
}
