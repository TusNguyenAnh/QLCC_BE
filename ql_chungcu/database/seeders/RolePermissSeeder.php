<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy role admin
        $roleAdmin = Role::where('role_name', 'admin')->first();
        // lay role suadmin
        $roleSuAdmin = Role::where('role_name', 'suadmin')->first();

        // Lấy tất cả permission id cua role admin
        $permissionIdsAdmin = Permission::whereNotIn('module', ['complex','task'])
            ->pluck('id')->toArray();

        $permissionIdsSuAdmin = Permission::whereIn('module', ['complex','user'])
            ->pluck('id')->toArray();

        // Gán tất cả quyền admin
        DB::table('role_permiss')->insert(
            array_map(fn($pid) => [
                'id' => (string)Str::uuid(),
                'role_id' => $roleAdmin->id,
                'permission_id' => $pid
            ], $permissionIdsAdmin)
        );

        // Gán tất cả quyền suadmin
        DB::table('role_permiss')->insert(
            array_map(fn($pid) => [
                'id' => (string)Str::uuid(),
                'role_id' => $roleSuAdmin->id,
                'permission_id' => $pid
            ], $permissionIdsSuAdmin)
        );
    }
}
