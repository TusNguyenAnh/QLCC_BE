<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
                //apartment
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:apartment',
                    'module' => 'apartment',
                    'description' => 'Xem danh sách căn hộ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:apartment',
                    'module' => 'apartment',
                    'description' => 'Thêm, sửa, xóa thông tin căn hộ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                // building
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:building',
                    'module' => 'building',
                    'description' => 'Xem danh sách tòa nhà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:building',
                    'module' => 'building',
                    'description' => 'Thêm, sửa, xóa thông tin tòa nhà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                // org
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:organization',
                    'module' => 'organization',
                    'description' => 'Xem danh sách ban quản trị',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:organization',
                    'module' => 'organization',
                    'description' => 'Thêm, sửa, xóa thông tin ban quản trị',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //permission
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:permission',
                    'module' => 'permission',
                    'description' => 'Xem danh sách quyền hạn',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'assign:permission',
                    'module' => 'permission',
                    'description' => 'Gán quyền hạn cho vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //priority
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:priority',
                    'module' => 'priority',
                    'description' => 'Lấy danh sách độ ưu tiên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //resident
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:resident',
                    'module' => 'resident',
                    'description' => 'Xem danh sách cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:resident',
                    'module' => 'resident',
                    'description' => 'Thêm, sửa, xóa thông tin cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                // role
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:role',
                    'module' => 'role',
                    'description' => 'Xem danh sách vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:role',
                    'module' => 'role',
                    'description' => 'Thêm, sửa, xóa thông tin vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'assign:role',
                    'module' => 'role',
                    'description' => 'Gán vai trò cho cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //task
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:task',
                    'module' => 'task',
                    'description' => 'Xem danh sách yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:task',
                    'module' => 'task',
                    'description' => 'Thêm, sửa, xóa thông tin yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'review:task',
                    'module' => 'task',
                    'description' => 'Xét duyệt yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                // task type
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:taskType',
                    'module' => 'taskType',
                    'description' => 'Xem danh sách loại yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:taskType',
                    'module' => 'taskType',
                    'description' => 'Thêm, sửa, xóa loại yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //user
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:user',
                    'module' => 'user',
                    'description' => 'Xem danh sách người dùng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:user',
                    'module' => 'user',
                    'description' => 'Thêm, sửa, xóa người dùng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                //workflow
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:workflow',
                    'module' => 'workflow',
                    'description' => 'Xem danh sách quy trình nghiệp vụ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:workflow',
                    'module' => 'workflow',
                    'description' => 'Thêm, sửa, xóa quy trình nghiệp vụ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:complex',
                    'module' => 'complex',
                    'description' => 'Xem danh sách chung cư',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:complex',
                    'module' => 'complex',
                    'description' => 'Thêm, sửa, xóa chung cư',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'review:complex',
                    'module' => 'complex',
                    'description' => 'Xét duyệt yêu cầu chung cư',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:expense',
                    'module' => 'expense',
                    'description' => 'Xem danh sách khoản chi',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:expense',
                    'module' => 'expense',
                    'description' => 'Thêm, sửa, xóa khoản chi',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:revenue',
                    'module' => 'revenue',
                    'description' => 'Xem danh sách khoản thu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:revenue',
                    'module' => 'revenue',
                    'description' => 'Thêm, sửa, xóa khoản thu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
//                [
//                    'id' => (string)Str::uuid(),
//                    'name' => '',
//                    'module' => '',
//                    'description' => '',
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now()
//                ],
            ]
        );
    }
}
