<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinancialModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('financial_model')->insert([
            [
                'id' => (string)Str::uuid(),
                'name' => 'Mô hình tài chính tập trung',
                'type' => 'centralized',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => (string)Str::uuid(),
                'name' => 'Mô hình tài chính phân tán',
                'type' => 'decentralized',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
