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
                    'module' => 'Căn hộ',
                    'description' => 'Xem danh sách căn hộ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:apartment',
                    'module' => 'Căn hộ',
                    'description' => 'Thêm, sửa, xóa thông tin căn hộ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                // building
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:building',
                    'module' => 'Tòa nhà',
                    'description' => 'Xem danh sách tòa nhà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:building',
                    'module' => 'Tòa nhà',
                    'description' => 'Thêm, sửa, xóa thông tin tòa nhà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                // org
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:organization',
                    'module' => 'Cơ cấu tổ chức',
                    'description' => 'Xem danh sách ban quản trị',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:organization',
                    'module' => 'Cơ cấu tổ chức',
                    'description' => 'Thêm, sửa, xóa thông tin ban quản trị',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //permission
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:permission',
                    'module' => 'Quyền hạn',
                    'description' => 'Xem danh sách quyền hạn',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'assign:permission',
                    'module' => 'Quyền hạn',
                    'description' => 'Gán quyền hạn cho vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //priority
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:priority',
                    'module' => 'Độ ưu tiên',
                    'description' => 'Lấy danh sách độ ưu tiên',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //resident
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:resident',
                    'module' => 'Cư dân',
                    'description' => 'Xem danh sách cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:resident',
                    'module' => 'Cư dân',
                    'description' => 'Thêm, sửa, xóa thông tin cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                // role
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:role',
                    'module' => 'Vai trò',
                    'description' => 'Xem danh sách vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:role',
                    'module' => 'Vai trò',
                    'description' => 'Thêm, sửa, xóa thông tin vai trò',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'assign:role',
                    'module' => 'Vai trò',
                    'description' => 'Gán vai trò cho cư dân',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //task
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:task',
                    'module' => 'Yêu cầu',
                    'description' => 'Xem danh sách yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:task',
                    'module' => 'Yêu cầu',
                    'description' => 'Thêm, sửa, xóa thông tin yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'review:task',
                    'module' => 'Yêu cầu',
                    'description' => 'Xét duyệt yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                // task type
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:taskType',
                    'module' => 'Loại yêu cầu',
                    'description' => 'Xem danh sách loại yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:taskType',
                    'module' => 'Loại yêu cầu',
                    'description' => 'Thêm, sửa, xóa loại yêu cầu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],

                //user
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:user',
                    'module' => 'Người dùng',
                    'description' => 'Xem danh sách người dùng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:user',
                    'module' => 'Người dùng',
                    'description' => 'Thêm, sửa, xóa người dùng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                //workflow
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'view:workflow',
                    'module' => 'Quy trình nghiệp vụ',
                    'description' => 'Xem danh sách quy trình nghiệp vụ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ],
                [
                    'id' => (string)Str::uuid(),
                    'name' => 'manage:workflow',
                    'module' => 'Quy trình nghiệp vụ',
                    'description' => 'Thêm, sửa, xóa quy trình nghiệp vụ',
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
