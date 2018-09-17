<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Subscriber as Subscriber;

class UpdateSubscriberTest extends TestCase
{
 
    use RefreshDatabase;
    
    const UNSUBSCRIBEDUSER = 8;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
    
    public function testUpdateSubscriber()
    {
        $id     = 1;
        $name   = 'Elisa Foo';
        $state  = 'junk';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/subscribers/' . $id,
            [
                'name'  => $name,
                'state' => $state,
            ]
        );
        
        // check response
        $response->assertStatus(200);
        
        // it has not dissapered
        $this->assertDatabaseHas('subscribers', [
            'id' => $id,
        ]);
       
        $subscriber = Subscriber::find($id);
                
        $this->assertEquals($subscriber->name, $name);
        $this->assertEquals($subscriber->state, $state);
    }

    public function testChangeFromUnsubscribedToActive()
    {
        $id     = self::UNSUBSCRIBEDUSER;
        $name  = 'Resubscribed';
        $state  = 'active';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/subscribers/' . $id,
            [
                'name'  => $name,
                'state' => $state,
            ]
        );
        
        // check response
        $response->assertStatus(422);
        
        // it has not dissapered
        $this->assertDatabaseHas('subscribers', [
            'id' => $id,
        ]);
        
        $subscriber = Subscriber::find($id);
                
        // still unsubscribed
        $this->assertEquals($subscriber->state, 'unsubscribed');
    }

    public function testChangeFromUnsubscribedToUnconfirmed()
    {
        $id     = self::UNSUBSCRIBEDUSER;
        $name   = 'John Unconfirmed';
        $state  = 'unconfirmed';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/subscribers/' . $id,
            [
                'name'  => $name,
                'state' => $state,
            ]
        );
        
        // check response
        $response->assertStatus(422);
        
        // it has not dissapered
        $this->assertDatabaseHas('subscribers', [
            'id' => $id,
        ]);
        
        $subscriber = Subscriber::find($id);
                
        // still unsubscribed
        $this->assertEquals($subscriber->state, 'unsubscribed');
    }

    public function testChangeFromBouncedToActive()
    {
        $id    = self::UNSUBSCRIBEDUSER;
        $name  = 'John Active';
        $state = 'active';
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/subscribers/' . $id,
            [
                'name'  => $name,
                'state' => $state,
            ]
        );
        
        // check response
        $response->assertStatus(422);
        
        // it has not dissapered
        $this->assertDatabaseHas('subscribers', [
            'id' => $id,
        ]);
        
        $subscriber = Subscriber::find($id);
                
        // still unsubscribed
        $this->assertEquals($subscriber->state, 'unsubscribed');
    }
}
