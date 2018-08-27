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
    }
}
