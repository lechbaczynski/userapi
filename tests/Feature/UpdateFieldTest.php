<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Field as Field;

// use Illuminate\Foundation\Testing\WithoutMiddleware;

class UpdateFieldTest extends TestCase
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
    public function testUpdateField()
    {
        
        $title = 'Changed field';
        $id = 1;
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/fields/' . $id,
            [
                'title' => $title,
                'type' => 'date',
            ]
        );
        
        // check response
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('fields', [
            'id' => $id,
        ]);
        
        $this->assertDatabaseHas('fields', [
            'title' => $title,
        ]);
        
        $field = Field::find($id);
                
        // check if it has 'date' type
        $this->assertEquals($field->type, 'date');
        $this->assertEquals($field->title, $title);
    }

    public function testUpdateFieldVithValue()
    {
        
        $title = 'Changed field with value';
        $id = 1;
        $value = "Foobar";
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/fields/' . $id,
            [
                'title' => $title,
                'type'  => 'string',
                'value' => $value,
            ]
        );
        
        // check response
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('fields', [
            'id' => $id,
        ]);
        
        $field = Field::find($id);
                
        // check if it has 'date' type
        $this->assertEquals($field->type, 'string');
        $this->assertEquals($field->title, $title);
        $this->assertEquals($field->value, $value);
    }
    
    public function testUpdateFieldThatDoNotExist()
    {
        
        $title = 'Changed not exisitnig field';
        $id = 1007;
        $value = "Foobar";
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json(
            'PUT',
            '/api/fields/' . $id,
            [
                'title' => $title,
                'type' => 'string',
            ]
        );
        
        // check response
        $response->assertStatus(404);
        
        $this->assertDatabaseMissing('fields', [
            'id' => $id,
        ]);
    }
}
