<?php

namespace Tests\Feature;

use App\DataTransferObjects\CountyDTO;
use App\Models\County;
use App\Services\CountyService;

class CountyServiceTest extends CustomTestCase
{
    // public function test_create_one_successfully()
    // {
    //     // arrange
    //     $service_mock = $this->mock(CountyService::class);

    //     $county = County::factory()->make();
    //     $countyDTO = new CountyDTO($county->name, $county->region_id);

    //     $service_mock->shouldReceive('createOne')
    //         ->once()
    //         ->andReturn($county);

    //     // act
    //     $result = $service_mock->createOne($countyDTO);

    //     // assert
    //     $this->assertInstanceOf(County::class, $result);
    // }
}
