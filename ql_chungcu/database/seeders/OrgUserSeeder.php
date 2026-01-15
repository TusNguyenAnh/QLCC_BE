<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrgUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy admin
        $admin = User::where([
                ['username', '=', 'atus'],
                ['complex_id', '=', ''],
                ['res_id', '=', ''],
            ]
        )->first();

        // Lấy role suadmin
        $role = Role::where([
                ['role_name', '=', 'suadmin'],
                ['complex_id', '=', ''],
            ]
        )->first();

        // Gán tất cả quyền admin
        DB::table('org_user')->insert([
                'id' => (string)Str::uuid(),
                'role_id' => $role->id,
                'user_id' => $admin->id,
                'org_id' => "",
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
