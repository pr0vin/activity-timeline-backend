<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $company = Company::create([
            'name' => 'Mohrain Websoft Pvt.LTD',
            'email' => "mohrain@gmail.com",
            'contact' => '9848420288',
            'province' => 'farwest',
            'district' => 'kailali',
            'municipality' => 'Dhangadhi',
            'address' => 'newroad'

        ]);
    }
}
