<?php

use Illuminate\Database\Seeder;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user that have fields
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A number field one",
            'type' => 'number',
            'value' => 42
        ]);
        
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A string field one",
            'type' => 'string',
            'value' => 'foo'
        ]);
        
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A string field two - empty",
            'type' => 'string',
            // no value
        ]);
        
    }
}
