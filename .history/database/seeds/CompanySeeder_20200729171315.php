<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies') -> insert([
            'id' => '1',
            'user_id' => '1',
            'identity' => '1',
            'nature' => 'AMB',
            'email' => 'PIMP@GMAIL.COM',
            'trade_name' => 'PIMP',
            'comercial_activity' => 'DRUG DEALER',
            'phone' => '022123456',
            'cell_phone' => '1234567890',
            'web_page' => 'WWW.MYSITE.COM',
            'address' => 'COMITE DEL PUEBLO',
            'state' => 'ACTIVE',

        ]);
        factory(App\Company::class, 100)->create();

    }
}
