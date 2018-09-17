<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i<5; $i++) {
            DB::table('subscribers')->insert([
                'email' => $faker->safeEmail,
                'name' => $faker->name,
                'state' => 'unconfirmed',
                'account_id' => 1
            ]);
        }
        
        // user with fields // 6
        DB::table('subscribers')->insert([
            'email' => 'ihavefields@example.com',
            'name' => "John H. Fields",
            'state' => 'unconfirmed',
            'account_id' => 1
        ]);
        
        
        // user for testing fields // 7
        DB::table('subscribers')->insert([
            'email' => 'ilikefields@example.com',
            'name' => "Ned Fields",
            'state' => 'unconfirmed',
            'account_id' => 1
        ]);
        
        // user unsubscribed // 8
        DB::table('subscribers')->insert([
            'email' => 'iamunsubscribed@example.com',
            'name' => "Donna Email",
            'state' => 'unsubscribed',
            'account_id' => 1
        ]);
        
    }
}
