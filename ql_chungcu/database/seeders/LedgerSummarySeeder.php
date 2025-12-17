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
            [
                'id' => (string)Str::uuid(),
                'complex_id' => '3b4d638a-84d0-4d05-82ed-b38690d10a75',
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
