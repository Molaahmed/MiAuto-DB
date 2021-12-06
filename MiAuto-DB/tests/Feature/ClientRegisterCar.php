<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientRegisterCar extends TestCase
{
    use RefreshDatabase;

      /**
     * A basic test example.
     *
     * @return void
     */
    public function a_car_can_be_added_to_the_lits_of_cars()
    {
      $this->post('api/client/car/add', [
      ]);

      $this->assertCount(1, Car::all());
    
    }
    public function a_car_cans_be_added_to_the_lits_of_cars()
    {
      $this->post('api/client/car/add', [
      ]);

      $this->assertCount(1, Car::all());
    
    }
    public function a_car_dcan_be_added_to_the_lits_of_cars()
    {
      $this->post('api/client/car/add', [
      ]);

      $this->assertCount(1, Car::all());
    
    }
}
