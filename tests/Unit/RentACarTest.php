<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Car;
use Illuminate\Http\Response;
use Tests\TestCase;
//use Ilumininate\Foundation\Testing\RefreshDataBase;

class RentACarTest extends TestCase
{
 //  use RefreshDataBase; 
    
  /** @test */
    public function testUserIsCreatedSuccessfully() {
    
        $payload = [
           // 'registration_licence' => $this->faker->firstName,
            'registration_licence' => $this->faker->registration_licence,
            'brand'  => $this->faker->brand,
            'model'      => $this->faker->model,
            'manufacture_date' => $this->faker->manufacture_date,
            'car_description'  => $this->faker->car_description,
            'properties'      => $this->faker->properties,
            'category_id' => $this->faker->category_id,
        ];
        $this->json('post', 'api/car', $payload)
             ->assertStatus(Response::HTTP_CREATED)
             ->assertJsonStructure(
                 [
                     'data' => [
                        'id',
                        'registration_licence',
                        'brand',
                        'model',
                        'manufacture_date',
                        'car_description',
                        'properties',
                        'category_id'
                     ]
                 ]
             );
        $this->assertDatabaseHas('car', $payload);
    }

    
   

}
