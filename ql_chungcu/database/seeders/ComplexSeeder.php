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
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string) Str::uuid(),
                    'complex_name' => 'Khu HH Linh Đàm',
                    'address' => "20 linh đàm",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string) Str::uuid(),
                    'complex_name' => 'Khu Vinhomes Ocean Park',
                    'address' => "Văn Giang & Văn Lâm, Hưng Yên",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]
        );
    }
}
