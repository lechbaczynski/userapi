<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscriber as Subscriber;

// use Illuminate\Foundation\Testing\WithoutMiddleware;

class AddingFieldTest extends TestCase
{

    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
     
     
    /**
     * Adding a field
     *
     * @return void
     */
    public function testAddField()
    {
        
        $email = 'ilikefields@example.com';
        $title = 'Added field';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/fields',
            [
                'title' => $title,
                'type' => 'number',
                'subscriber_email' => $email,
            ]
        );
        
        // check response
        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
        
        // check if exists in the database
        $id = $response->getData()->id;

        $this->assertDatabaseHas('fields', [
            'id' => $id,
        ]);
        
        $this->assertDatabaseHas('fields', [
            'title' => $title,
        ]);
    }
    
     /**
     * Adding a field for non exisitng subscriber
     *
     * @return void
     */
    public function testAddFieldForNonExisitingSubscriber()
    {
        $email = 'idontexist@example.com';
        $title = 'Added field';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/fields',
            [
                'title' => $title,
                'type' => 'number',
                'subscriber_email' => $email,
            ]
        );
        
        // check response
        $response->assertStatus(409);
    }

    /**
     * Adding a field without type
     *
     * @return void
     */
    public function testAddFieldWithoutType()
    {
        $email = 'idontexist@example.com';
        $title = 'Added field';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/fields',
            [
                'title' => $title,
                'subscriber_email' => $email,
            ]
        );
        
        // check response
        $response->assertStatus(422);
    }

    /**
     * Adding a field that already exists
     *
     * @return void
     */
    public function testAddFieldThatExists()
    {
        $email = 'ihavefields@example.com';
        $title = 'A string field, first';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'POST',
            '/api/fields',
            [
                'title' => $title,
                'type' => 'string',
                'subscriber_email' => $email,
            ]
        );
        
        // check response
        $response->assertStatus(409);
    }
}
