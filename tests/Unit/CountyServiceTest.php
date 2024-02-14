<?php

namespace Tests\Unit;

use App\DataTransferObjects\CountyDTO;
use App\Models\County;
use App\Services\CountyService;
use Mockery;
use PHPUnit\Framework\TestCase;

class CountyServiceTest extends TestCase
{
    private string $county_name;

    private string $county_name_not_unique;

    private int $region_id;

    public function setUp(): void
    {
        parent::setUp();

        $this->county_name = 'county_name';
        $this->county_name_not_unique = $this->county_name;
        $this->region_id = 1;
    }

    public function test_create_one_successfully()
    {
        // arrange
        $service_mock = Mockery::mock(CountyService::class);

        $county_dto = new CountyDTO($this->county_name, $this->region_id);

        $service_mock->shouldReceive('createOne')
            ->once()
            ->andReturn(new County());

        // act
        $result = $service_mock->createOne($county_dto);

        // assert
        $this->assertInstanceOf(County::class, $result);
    }
}
