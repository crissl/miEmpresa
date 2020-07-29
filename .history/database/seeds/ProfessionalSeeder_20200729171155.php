<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('professionals') -> insert([
            'id' => '1',
            'user_id' => '1',
            'identity' => 'MALE',
            'email' => 'PIMP@GMAIL.COM',
            'first_name' => 'AAA',
            'last_name' => 'BBB',
            'nationality' => 'EC',
            'civil_state' => 'S',
            'birthdate' => '01-12-99',
            'gender' => 'MALE',
            'phone' => '022123456',
            'address' => 'COMITE DEL PUEBLO',
            'about_me' => 'lalaland',
            'state' => 'ACTIVE'

        ]);
        factory(App\User::class, 100)->create();

    }
}
