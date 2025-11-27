<?php

namespace App\Helpers;

class StringHelper
{
    public static function randomStrongCode($length = 6)
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=[]{}<>?';

        // Mỗi loại lấy 1 ký tự trước
        $result = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $special[random_int(0, strlen($special) - 1)],
        ];

        // Các ký tự còn lại để đủ độ dài
        $all = $upper . $lower . $numbers . $special;

        for ($i = 0; $i < $length - 4; $i++) {
            $result[] = $all[random_int(0, strlen($all) - 1)];
        }

        // Xáo trộn ký tự
        shuffle($result);

        return implode('', $result);
    }
}
