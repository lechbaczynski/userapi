<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $accounts = ['First Account', 'Another Account'];
        foreach ($accounts as $accountName) {
            DB::table('accounts')->insert([
                'name' => $accountName
            ]);
        }
    }
}
