<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_professional') -> insert([
            'id' => '1',
            'company_id' => '1',
            'professional_id' => '1',
            'state' => 'ACTIVE'
        ]);

    }
}
