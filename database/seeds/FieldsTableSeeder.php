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
        // fields for subscriber that have fields
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A number field, first",
            'type' => 'number',
            'value' => 42
        ]);
        
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A string field, first",
            'type' => 'string',
            'value' => 'foo'
        ]);
        
        DB::table('fields')->insert([
            'subscriber_id' => 6,
            'title' => "A string field, second, empty",
            'type' => 'string',
            // no value
        ]);
    }
}
