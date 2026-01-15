<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LedgerSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preTime = Carbon::create(Carbon::now()->year, Carbon::now()->month, 1)->subMonth();

        DB::table('ledger_summary')->insert([
//            [
//                'id' => (string)Str::uuid(),
//                'complex_id' => '14952ebf-a753-4a13-9d7f-1c8bc4115d23',
//                'building_id' => '8a5c922a-c8b5-4bf3-aff1-0937cacd98fe',
//                'year' => $preTime->year,
//                'month' => $preTime->month,
//                'total_in' => 0,
//                'total_out' => 0,
//                'opening_balance' => 0,
//                'closing_balance' => 0,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],
//            [
//                'id' => (string)Str::uuid(),
//                'complex_id' => '14952ebf-a753-4a13-9d7f-1c8bc4115d23',
//                'building_id' => '89592365-0fda-47ff-9e69-80951ad94045',
//                'year' => $preTime->year,
//                'month' => $preTime->month,
//                'total_in' => 0,
//                'total_out' => 0,
//                'opening_balance' => 0,
//                'closing_balance' => 0,
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ],

            [
                'id' => (string)Str::uuid(),
                'complex_id' => '2c241a7b-054f-4e10-bfba-4a84a1c7db39',
                'year' => $preTime->year,
                'month' => $preTime->month,
                'total_in' => 0,
                'total_out' => 0,
                'opening_balance' => 0,
                'closing_balance' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
