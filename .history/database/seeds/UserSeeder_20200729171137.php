<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users') -> insert([
            'id' => '1',
            'name' => 'LOLA',
            'user_name' => 'LALO',
            'email' => 'PIMP@GMAIL.COM',
            'password' => '12345678',
            'avatar' => 'PIMP',
            'api_token' => '1111111',
            'state' => 'ACTIVE',

        ]);
        factory(App\User::class, 100)->create();
    }
}
