<?php

namespace App\Enums;


enum ErrorCode
{
    // Organization
    case ORG_NAME_NOT_EMPTY;
    case ORG_NAME_LENGTH;
    case ORG_NAME_NOT_FOUND;
    case ORG_DESCRIPTION_LENGTH;
    case ORG_NAME_UNIQUE;


    case UNCATEGORIZED_EXCEPTION;
    case UNAUTHENTICATED;
    case UNAUTHORIZED;
    case TOKEN_EXPIRED;
    case TOKEN_INVALID;
    case INCORRECT_LOGIN_INFO;
    case INCORRECT_RF_TOKEN;
    case CODE_NOT_EMPTY;

    case USER_EXISTED;
    case EMAIL_EXITED;
    case USER_NON_EXISTED;
    case PASSWORD_NOT_MATCH;
    case USERNAME_NOT_NULL;
    case USERNAME_SIZE;
    case EMAIL_NOT_NULL;
    case EMAIL_NOT_FORMAT;
    case ADDRESS_NOT_NULL;
    case ADDRESS_SIZE;

    // bat dau tu day
    //complex
    case COMPLEX_NAME_EXISTED;
    case ADDRESS_EXISTED;
    case COMPLEX_NAME_NOT_EMPTY;
    case ADDRESS_NOT_EMPTY;
    case ADDRESS_LENGTH;
    case DESCRIPTION_LENGTH;
    case TOTAL_BUILDING_NOT_EMPTY;
    case TOTAL_BUILDING_LENGTH;
    case TOTAL_APARTMENT_NOT_EMPTY;
    case NAME_CONTACT_NOT_EMPTY;
    case PHONE_CONTACT_NOT_EMPTY;
    case PHONE_CONTACT_EXISTED;

    case EMAIL_CONTACT_NOT_EMPTY;


    public function code(): int
    {
        return match ($this) {
            self::UNCATEGORIZED_EXCEPTION => 9999,
            self::UNAUTHENTICATED => 1000,
            self::UNAUTHORIZED => 1001,
            self::TOKEN_EXPIRED => 1002,
            self::INCORRECT_LOGIN_INFO => 1003,
            self::INCORRECT_RF_TOKEN => 1004,
            self::TOKEN_INVALID => 1005,
            self::CODE_NOT_EMPTY => 1006,

            self::USER_EXISTED => 1010,
            self::EMAIL_EXITED => 1011,
            self::USER_NON_EXISTED => 1012,

            self::PASSWORD_NOT_MATCH => 1018,
            self::USERNAME_NOT_NULL => 1019,
            self::USERNAME_SIZE => 1020,
            self::EMAIL_NOT_NULL => 1021,
            self::EMAIL_NOT_FORMAT => 1022,
            self::ADDRESS_NOT_NULL => 1023,
            self::ADDRESS_SIZE => 1024,


            //org
            self::ORG_NAME_NOT_EMPTY => 2000,
            self::ORG_NAME_LENGTH => 2001,
            self::ORG_NAME_NOT_FOUND => 2002,
            self::ORG_DESCRIPTION_LENGTH => 2003,
            self::ORG_NAME_UNIQUE => 2004,


            //complex
            self::COMPLEX_NAME_EXISTED => 3000,
            self::ADDRESS_EXISTED => 3001,
            self::COMPLEX_NAME_NOT_EMPTY => 3002,
            self::ADDRESS_NOT_EMPTY => 3003,
            self::ADDRESS_LENGTH => 3004,
            self::DESCRIPTION_LENGTH => 3005,
            self::TOTAL_BUILDING_NOT_EMPTY => 3006,
            self::TOTAL_BUILDING_LENGTH => 3007,
            self::TOTAL_APARTMENT_NOT_EMPTY => 3008,
            self::NAME_CONTACT_NOT_EMPTY => 3009,
            self::PHONE_CONTACT_NOT_EMPTY => 3010,
            self::EMAIL_CONTACT_NOT_EMPTY => 3011,
            self::PHONE_CONTACT_EXISTED => 3012,
        };
    }

    public function message(): string
    {
        return match ($this) {

            self::UNCATEGORIZED_EXCEPTION => "Lỗi chưa được phân loại",
            self::UNAUTHENTICATED => "Không thể xác thực người dùng",
            self::UNAUTHORIZED => "Bạn không có quyền truy cập",
            self::TOKEN_EXPIRED => "Token đã hết hạn",
            self::INCORRECT_LOGIN_INFO => "Sai thông tin đăng nhập",
            self::INCORRECT_RF_TOKEN => "Refresh token không hợp lệ hoặc hết hạn",
            self::TOKEN_INVALID => "Token không hợp lệ",
            self::CODE_NOT_EMPTY => "Mã code không hợp lệ hoặc bị bỏ trống",

            self::USER_EXISTED => "User đã tồn tại",
            self::EMAIL_EXITED => "Email đã tồn tại",
            self::USER_NON_EXISTED => "User không tồn tại",
            self::PASSWORD_NOT_MATCH => "Password và Retype password không trùng nhau",
            self::USERNAME_NOT_NULL => "Username không được để trống",
            self::USERNAME_SIZE => "Độ dài tên lớn hơn 2 và không vượt quá 50 kí tự",
            self::EMAIL_NOT_NULL => "Email không được để trống",
            self::EMAIL_NOT_FORMAT => "Email không đúng định dạng",
            self::ADDRESS_NOT_NULL => "Địa chỉ không được để trống",
            self::ADDRESS_SIZE => "Độ dài địa chỉ lớn hơn 5 và không vượt quá 255 kí tự",

            //complex
            self::COMPLEX_NAME_EXISTED => "Tên chung cư đã tồn tại!",
            self::COMPLEX_NAME_NOT_EMPTY => "Tên chung cư không được để trống!",
            self::ADDRESS_NOT_EMPTY => "Địa chỉ không được để trống!",
            self::ADDRESS_LENGTH => "Địa chỉ dài tối thiểu 5 ký tự và tối đa 50 ký tự!",
            self::DESCRIPTION_LENGTH => "Mô tả dài tối đa 100 ký tự",
            self::TOTAL_BUILDING_NOT_EMPTY => "Tổng số tòa nhà không được để trống!",
            self::TOTAL_BUILDING_LENGTH => "Tối đa 20 tòa!",
            self::TOTAL_APARTMENT_NOT_EMPTY => "Tổng số căn hộ không được để trống!",
            self::NAME_CONTACT_NOT_EMPTY => "Tên người liên hệ không được để trống!",
            self::PHONE_CONTACT_NOT_EMPTY => "Số điện thoại liên hệ không được để trống!",
            self::EMAIL_CONTACT_NOT_EMPTY => "Email liên hệ không được để trống!",
            self::PHONE_CONTACT_EXISTED => "Số điện thoại liên hệ đã được sử dụng!",
            //org
            self::ORG_NAME_NOT_EMPTY => "Tên phòng ban không được để trống!",
            self::ORG_NAME_UNIQUE => "Phòng ban đã tồn tại!",
            self::ORG_NAME_LENGTH => "Tên phòng ban dài tối thiểu 5 ký tự và tối đa 30 ký tự!",
            self::ORG_NAME_NOT_FOUND => "Phòng ban không tồn tại!",
            self::ORG_DESCRIPTION_LENGTH => "Mô tả phòng ban dài tối đa 100 ký tự",

            self::ADDRESS_EXISTED => "Địa chỉ đã tồn tại!",
        };
    }

    public function httpStatus(): int
    {
        return match ($this) {
            self::UNCATEGORIZED_EXCEPTION => 500,
            self::UNAUTHENTICATED,
            self::INCORRECT_RF_TOKEN,
            self::TOKEN_INVALID,
            self::INCORRECT_LOGIN_INFO => 401,

            self::UNAUTHORIZED,
            self::TOKEN_EXPIRED => 403,


            self::ORG_NAME_NOT_EMPTY,
            self::ORG_NAME_UNIQUE,
            self::ORG_NAME_LENGTH,
            self::ORG_NAME_NOT_FOUND,
            self::ORG_DESCRIPTION_LENGTH,


            self::CODE_NOT_EMPTY,
            self::USER_EXISTED,
            self::EMAIL_EXITED,
            self::USER_NON_EXISTED,
            self::PASSWORD_NOT_MATCH,
            self::USERNAME_NOT_NULL,
            self::USERNAME_SIZE,
            self::EMAIL_NOT_NULL,
            self::EMAIL_NOT_FORMAT,
            self::ADDRESS_NOT_NULL,
            self::ADDRESS_SIZE,
            self::COMPLEX_NAME_EXISTED,
            self::ADDRESS_EXISTED,
            self::COMPLEX_NAME_NOT_EMPTY,
            self::ADDRESS_NOT_EMPTY,
            self::ADDRESS_LENGTH,
            self::DESCRIPTION_LENGTH,
            self::TOTAL_BUILDING_NOT_EMPTY,
            self::TOTAL_BUILDING_LENGTH,
            self::TOTAL_APARTMENT_NOT_EMPTY,
            self::NAME_CONTACT_NOT_EMPTY,
            self::PHONE_CONTACT_NOT_EMPTY,
            self::PHONE_CONTACT_EXISTED,
            self::EMAIL_CONTACT_NOT_EMPTY => 400,
        };
    }

    public static function getCaseName(string $value)
    {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return self::UNCATEGORIZED_EXCEPTION; // Không tìm thấy case
    }
}
