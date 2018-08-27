<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscriber as Subscriber;

// use Illuminate\Foundation\Testing\WithoutMiddleware;

class AddingSubscriberTest extends TestCase
{

    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
     
     
    /**
     * Adding a subscriber
     *
     * @return void
     */
    public function testAddSubscriber()
    {
        
        $email = 'example@gmail.com';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/subscribers',
            ['name' => 'John Smith', 'email' => $email]
        );
        
        // check response
        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
        
        // check if exists in the database
        $id = $response->getData()->id;

        $this->assertDatabaseHas('subscribers', [
            'id' => $id,
        ]);
        
        $this->assertDatabaseHas('subscribers', [
            'email' => $email,
        ]);
    }
    
    /**
     * Adding a subscriber with a wrong e-mail
     *
     * @return void
     */
    public function testAddSubscriberWrongEmail()
    {
        
        $email = 'examplegmail.com';
        
        $response = $this->withHeaders([
        'X-Header' => 'Value',
        ])->json('POST',
                '/api/subscribers/',
                ['name' => 'John',
                'email' => $email]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => true,
            ]);
        
        // check if NOT exists in the database
        $this->assertDatabaseMissing('subscribers', [
            'email' => $email,
        ]);
        
    }

    public function testAddSubscriberWithOneField()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/subscribers/', 
                ['name' => 'John',
                 'email' => "example@gmail.com",
                  'fields' => [
                      ['name' => 'source', 'type' => 'string', 'value' => 'website'],
                    ]]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }

    public function testAddSubscriberWithManyFields()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/subscribers/',
                ['name' => 'John',
                 'email' => "example@gmail.com",
                  'fields' => [
                     ['name' => 'source', 'type'=> 'string', 'value' => 'website'],
                     ['name' => 'age', 'type' => 'number', 'value' => 20],
                    ]]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);    
    
        // check if exists in the database

        
        
    }
}
