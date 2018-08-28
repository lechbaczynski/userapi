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
        $email = 'example@gmail.com';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/subscribers/',
            ['name' => 'John',
            'email' => $email,
            'fields' => [
                ['title' => 'source', 'type' => 'string', 'value' => 'website'],
            ]]);

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
        
               
        // check if has fields
       
        $subscriber = Subscriber::find($id);
        $fields = $subscriber->fields->toArray();
        
        
        $this->assertArraySubset(
           [['title' => 'source', 
             'type' => 'string', 
             'value' => 'website', 
             'subscriber_id' => $id 
                ]],
            $fields);
        
        // dd($fields);
        
        
    }

    public function testAddSubscriberWithManyFields()
    {
        $email = 'example2@gmail.com';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', '/api/subscribers/',
                ['name' => 'John',
                 'email' => $email,
                  'fields' => [
                     ['title' => 'source', 'type'=> 'string', 'value' => 'website'],
                     ['title' => 'age', 'type' => 'number', 'value' => 20],
                     ['title' => 'sex', 'type' => 'string'], // empty value
                    ]]);

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
        
        // check if has fields
        
        $subscriber = Subscriber::find($id);
        $fields = $subscriber->fields->toArray();
        
        $counter = 0;
        foreach ($fields as $field) {
            if ($field['title'] == 'source') {
                $this->assertEquals($field['type'], 'string'); 
                $this->assertEquals($field['value'], 'website'); 
                $counter++;
            }
            else if ($field['title'] == 'age') {
                $this->assertEquals($field['type'], 'number'); 
                $this->assertEquals($field['value'], 20); 
                $counter++;
            }
            else if ($field['title'] == 'sex') {
                $this->assertEquals($field['type'], 'string'); 
                $this->assertEquals($field['value'], null); 
                $counter++;
            }
            
        }
        
         $this->assertEquals($counter, 3); 
        
    }
}
