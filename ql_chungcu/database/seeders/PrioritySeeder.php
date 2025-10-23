<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('priority')->insert([
                [
                    'id' => (string)Str::uuid(),
                    'priority_name' => 'LOW',
                    'weight' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'priority_name' => 'MEDIUM',
                    'weight' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'priority_name' => 'HIGH',
                    'weight' => 3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'priority_name' => 'URGENT',
                    'weight' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
            ]
        );
    }
}
