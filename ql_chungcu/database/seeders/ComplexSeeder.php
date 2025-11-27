<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComplexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('complex')->insert([
                [
                    'id' => (string) Str::uuid(),
                    'complex_name' => 'Khu Vinhomes Times City Park Hill',
                    'address' => "22 vinh tuy",
                    'total_building' => 10,
                    'total_apartment' => 1000,
                    'name_contact' => 'Nguyen Van A',
                    'phone_contact' => '0900517622',
                    'email_contact' => 'nguyenvana@gmail.com',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string) Str::uuid(),
                    'complex_name' => 'Khu HH Linh Đàm',
                    'address' => "20 linh đàm",
                    'total_building' => 20,
                    'total_apartment' => 2000,
                    'name_contact' => 'Nguyen Van B',
                    'phone_contact' => '0900517621',
                    'email_contact' => 'nguyenvanb@gmail.com',
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]
        );
    }
}
