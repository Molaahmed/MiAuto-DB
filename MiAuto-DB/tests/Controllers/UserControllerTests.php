<?php
    
namespace Tests\Controllers;
    
use Illuminate\Http\Response;
use Tests\TestCase;
    
class UserControllerTests extends TestCase {
    
    public function testIndexReturnsDataInValidFormat() {
    
    $this->json('get', 'api/user')
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
             [
                 'id',
                 'first_name',
                 'last_name',
                 'email',
                 'date_of_birth',
                 'address',
                 'phone_number',
                 'password',
                 'remember_token',
                 'role'
             ]
         );
  } 
}