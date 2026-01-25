<?php

namespace App\Enums;


use function Laravel\Prompts\search;
use function Symfony\Component\String\s;

enum ErrorCode
{
    case NOT_CREATED;
    case NOT_FOUND;
    case NOT_UPDATE;
    case NOT_DELETED;
    // Organization
    case ORG_NAME_NOT_EMPTY;
    case ORG_NAME_LENGTH;
    case ORG_NAME_NOT_FOUND;
    case ORG_DESCRIPTION_LENGTH;
    case ORG_NAME_UNIQUE;
    case PARENT_ORG_EXISTED;
    case MAX_ORG_LEVEL;


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

    //building
    case BUILDING_NON_EXISTED;
    case BUILDING_NOT_EMPTY;
    case BUILDING_NAME_NOT_EMPTY;
    case BUILDING_ADDRESS_NOT_EMPTY;
    case COMPLEX_ID_NOT_EMPTY;
    case FINANCIAL_TOTAL_RATIO_NOT_VALID;

    //apartment
    case FLOOR_REQUIRED;
    case FLOOR_NOT_INTEGER;
    case APT_NUMBER_REQUIRED;
    case APT_NUMBER_LENGTH;
    case APT_AREA_REQUIRED;
    case APT_AREA_NOT_NUMERIC;
    case APT_AREA_MIN;
    case APT_TYPE_REQUIRED;
    case APT_TYPE_INVALID;
    case APT_NUMBER_EXISTED;


    //service_unit_prices
    case PRICE_NON_EXISTED;
    case YEAR_NOT_EMPTY;
    case YEAR_NOT_INTEGER;
    case YEAR_MIN_MAX;
    case MONTH_NOT_EMPTY;
    case MONTH_NOT_INTEGER;
    case MONTH_MIN_MAX;
    case PRICE_PER_M2_NOT_EMPTY;
    case PRICE_PER_M2_NOT_NUMERIC;
    case PRICE_HAS_REVENUE;

    //revenue filter
    case APARTMENT_ID_NOT_UUID;
    case STATUS_INVALID;

    //revenue
    case APARTMENT_ID_REQUIRED;
    case APARTMENT_ID_NOT_EXISTS;
    case ORIGINAL_AMOUNT_REQUIRED;
    case ORIGINAL_AMOUNT_NOT_NUMERIC;
    case REVENUE_NOT_UPDATE;

    //expense
    case TITLE_REQUIRED;
    case CATEGORY_REQUIRED;
    case CATEGORY_INVALID;
    case AMOUNT_REQUIRED;
    case AMOUNT_NOT_NUMERIC;
    case AMOUNT_MIN;
    case EXPENSE_NOT_UPDATE;

    //expense filter
    case DATE_INVALID;
    case DATE_TO_AFTER_FROM;

    // ledgers
    case VOUCHER_NOT_VALID;
    case BUILDING_ID_REQUIRED;
    case FUND_TYPE_REQUIRED;
    case FUND_TYPE_INVALID;
    case PAYMENT_METHOD_REQUIRED;
    case PAYMENT_METHOD_INVALID;
    case TRANSACTION_DATE_REQUIRED;
    case LEDGER_SUMMARY_EXISTED;
    case LEDGER_SUMMARY_NOT_EXISTED;

    // Excel file validation
    case FILE_REQUIRED;
    case FILE_INVALID;
    case FILE_EXCEL_INVALID_FORMAT;
    case FILE_SIZE_EXCEEDED;

    //resident
    case RESIDENT_EXISTED;
    case RESIDENT_GENDER_NOT_EMPTY;
    case RESIDENT_FULLNAME_NOT_EMPTY;
    case RESIDENT_EMAIL_NOT_EMPTY;
    case RESIDENT_BIRTHDAY_NOT_EMPTY;
    case RESIDENT_PHONE_NOT_EMPTY;
    case RESIDENT_RELATIONSHIP_NOT_EMPTY;
    case RESIDENT_CCCD_NOT_EMPTY;
    //staff
    case STAFF_EXISTED;
    case STAFF_FULLNAME_NOT_EMPTY;
    case STAFF_EMAIL_NOT_EMPTY;
    case STAFF_PHONE_NOT_EMPTY;
    case STAFF_ORG_ID_NOT_EMPTY;
    case STAFF_ROLE_ID_NOT_EMPTY;

    //task
    case TASK_INFO_INVALID;
    case TASK_TYPE_ID_NOT_EMPTY;
    case TASK_BUILDING_ID_NOT_EMPTY;
    case TASK_NAME_NOT_EMPTY;
    case TASK_DESCRIPTION_NOT_EMPTY;

    //task type
    case TASKTYPE_WORKFLOW_ID_NOT_EMPTY;
    case TASKTYPE_PRIORITY_ID_NOT_EMPTY;
    case TASKTYPE_NAME_NOT_EMPTY;
    case TASKTYPE_DESCRIPTION_NOT_EMPTY;

    //permission
    case PERMISSION_NAME_NOT_EMPTY;
    case PERMISSION_MODULE_NOT_EMPTY;

    //role
    case ROLE_NAME_NOT_EMPTY;
    case ROLE_COMPLEX_ID_NOT_EMPTY;
    case ROLE_DESCRIPTION_NOT_EMPTY;
    case ROLE_USER_ID_NOT_EMPTY;
    case ROLE_ID_NOT_EMPTY;

    //workflow
    case WORKFLOW_COMPLEX_ID_NOT_EMPTY;
    case WORKFLOW_NAME_NOT_EMPTY;
    case WORKFLOW_DESCRIPTION_NOT_EMPTY;
    case WORKFLOW_STATUS_NOT_EMPTY;
    case WORKFLOW_STEP_NOT_EMPTY;
    case WORKFLOW_STEP_MIN;
    case WORKFLOW_STEP_ORG_LEVEL_NOT_EMPTY;
    case WORKFLOW_STEP_ORDER_NOT_EMPTY;
    case WORKFLOW_STEP_DESCRIPTION_NOT_EMPTY;
    case WORKFLOW_STEP_STATUS_NOT_EMPTY;
    case WORKFLOW_STEP_POSITION_NOT_EMPTY;
    case WORKFLOW_STEP_POSITION_ARRAY;
    case WORKFLOW_STEP_POSITION_ITEM_NOT_EMPTY;

    public function code(): int
    {
        return match ($this) {
            self::UNCATEGORIZED_EXCEPTION => 9999,
            self::NOT_CREATED => 9998,
            self::NOT_FOUND => 9997,
            self::NOT_UPDATE => 9996,
            self::NOT_DELETED => 9995,

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
            self::PARENT_ORG_EXISTED => 2005,
            self::MAX_ORG_LEVEL => 2006,


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

            //service_unit_prices
            self::PRICE_NON_EXISTED => 4000,
            self::YEAR_NOT_EMPTY => 4001,
            self::YEAR_NOT_INTEGER => 4002,
            self::YEAR_MIN_MAX => 4003,
            self::MONTH_NOT_EMPTY => 4004,
            self::MONTH_NOT_INTEGER => 4005,
            self::MONTH_MIN_MAX => 4006,
            self::PRICE_PER_M2_NOT_EMPTY => 4007,
            self::PRICE_PER_M2_NOT_NUMERIC => 4008,
            self::PRICE_HAS_REVENUE => 4009,

            //revenue
            self::APARTMENT_ID_NOT_UUID => 4010,
            self::STATUS_INVALID => 4011,
            self::APARTMENT_ID_REQUIRED => 4012,
            self::APARTMENT_ID_NOT_EXISTS => 4013,
            self::ORIGINAL_AMOUNT_REQUIRED => 4014,
            self::ORIGINAL_AMOUNT_NOT_NUMERIC => 4015,
            self::REVENUE_NOT_UPDATE => 4016,

            //expense
            self::TITLE_REQUIRED => 4017,
            self::CATEGORY_REQUIRED => 4018,
            self::CATEGORY_INVALID => 4019,
            self::AMOUNT_REQUIRED => 4020,
            self::AMOUNT_NOT_NUMERIC => 4021,
            self::AMOUNT_MIN => 4022,
            self::EXPENSE_NOT_UPDATE => 4023,
            self::DATE_INVALID => 4024,
            self::DATE_TO_AFTER_FROM => 4025,
            self::BUILDING_ID_REQUIRED => 4026,
            self::FUND_TYPE_REQUIRED => 4027,
            self::FUND_TYPE_INVALID => 4028,
            self::PAYMENT_METHOD_REQUIRED => 4029,
            self::PAYMENT_METHOD_INVALID => 4030,
            self::TRANSACTION_DATE_REQUIRED => 4031,

            //building
            self::BUILDING_NON_EXISTED => 4099,
            self::BUILDING_NOT_EMPTY => 4098,
            self::BUILDING_NAME_NOT_EMPTY => 4097,
            self::BUILDING_ADDRESS_NOT_EMPTY => 4096,
            self::COMPLEX_ID_NOT_EMPTY => 4095,
            self::FINANCIAL_TOTAL_RATIO_NOT_VALID => 4096,

            //apartment
            self::FLOOR_REQUIRED => 4100,
            self::FLOOR_NOT_INTEGER => 4101,
            self::APT_NUMBER_REQUIRED => 4102,
            self::APT_NUMBER_LENGTH => 4103,
            self::APT_AREA_REQUIRED => 4104,
            self::APT_AREA_NOT_NUMERIC => 4105,
            self::APT_AREA_MIN => 4106,
            self::APT_TYPE_REQUIRED => 4107,
            self::APT_TYPE_INVALID => 4108,
            self::APT_NUMBER_EXISTED => 4109,

            //ledger
            self::VOUCHER_NOT_VALID => 5000,
            self::LEDGER_SUMMARY_EXISTED => 5001,
            self::LEDGER_SUMMARY_NOT_EXISTED => 5002,

            //excel file
            self::FILE_REQUIRED => 6000,
            self::FILE_INVALID => 6001,
            self::FILE_EXCEL_INVALID_FORMAT => 6002,
            self::FILE_SIZE_EXCEEDED => 6003,

            //resident
            self::RESIDENT_EXISTED => 7000,
            self::RESIDENT_GENDER_NOT_EMPTY => 7001,
            self::RESIDENT_FULLNAME_NOT_EMPTY => 7002,
            self::RESIDENT_EMAIL_NOT_EMPTY => 7003,
            self::RESIDENT_BIRTHDAY_NOT_EMPTY => 7004,
            self::RESIDENT_PHONE_NOT_EMPTY => 7005,
            self::RESIDENT_RELATIONSHIP_NOT_EMPTY => 7006,
            self::RESIDENT_CCCD_NOT_EMPTY => 7007,

            //staff
            self::STAFF_EXISTED => 8001,
            self::STAFF_FULLNAME_NOT_EMPTY => 8002,
            self::STAFF_EMAIL_NOT_EMPTY => 8003,
            self::STAFF_PHONE_NOT_EMPTY => 8004,
            self::STAFF_ORG_ID_NOT_EMPTY => 8005,
            self::STAFF_ROLE_ID_NOT_EMPTY => 8006,

            //task
            self::TASK_INFO_INVALID => 8999,
            self::TASK_TYPE_ID_NOT_EMPTY => 8100,
            self::TASK_BUILDING_ID_NOT_EMPTY => 8101,
            self::TASK_NAME_NOT_EMPTY => 8102,
            self::TASK_DESCRIPTION_NOT_EMPTY => 8103,

            //task type
            self::TASKTYPE_WORKFLOW_ID_NOT_EMPTY => 8200,
            self::TASKTYPE_PRIORITY_ID_NOT_EMPTY => 8201,
            self::TASKTYPE_NAME_NOT_EMPTY => 8202,
            self::TASKTYPE_DESCRIPTION_NOT_EMPTY => 8203,

            //permission
            self::PERMISSION_NAME_NOT_EMPTY => 9000,
            self::PERMISSION_MODULE_NOT_EMPTY => 9001,

            //role
            self::ROLE_NAME_NOT_EMPTY => 9100,
            self::ROLE_COMPLEX_ID_NOT_EMPTY => 9101,
            self::ROLE_DESCRIPTION_NOT_EMPTY => 9102,
            self::ROLE_USER_ID_NOT_EMPTY => 9103,
            self::ROLE_ID_NOT_EMPTY => 9104,

            //workflow
            self::WORKFLOW_COMPLEX_ID_NOT_EMPTY => 8300,
            self::WORKFLOW_NAME_NOT_EMPTY => 8301,
            self::WORKFLOW_DESCRIPTION_NOT_EMPTY => 8302,
            self::WORKFLOW_STATUS_NOT_EMPTY => 8303,
            self::WORKFLOW_STEP_NOT_EMPTY => 8304,
            self::WORKFLOW_STEP_MIN => 8305,
            self::WORKFLOW_STEP_ORG_LEVEL_NOT_EMPTY => 8306,
            self::WORKFLOW_STEP_ORDER_NOT_EMPTY => 8307,
            self::WORKFLOW_STEP_DESCRIPTION_NOT_EMPTY => 8308,
            self::WORKFLOW_STEP_STATUS_NOT_EMPTY => 8309,
            self::WORKFLOW_STEP_POSITION_NOT_EMPTY => 8310,
            self::WORKFLOW_STEP_POSITION_ARRAY => 8311,
            self::WORKFLOW_STEP_POSITION_ITEM_NOT_EMPTY => 8312,
        };
    }

    public function message(): string
    {
        return match ($this) {

            self::UNCATEGORIZED_EXCEPTION => "Lỗi chưa được phân loại",
            self::NOT_CREATED => "Đã xảy ra lỗi khi tạo mới. Vui lòng kiểm tra lại thông tin!",
            self::NOT_FOUND => "Không tìm thấy thông tin",
            self::NOT_UPDATE => "Đã xảy ra lỗi khi cập nhật. Vui lòng kiểm tra lại thông tin!",
            self::NOT_DELETED => "Đã xảy ra lỗi khi xóa dữ liệu. Vui lòng kiểm tra lại thông tin!",

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
            self::PARENT_ORG_EXISTED => "Chỉ tồn tại 1 cấp BQT cao nhất!",
            self::MAX_ORG_LEVEL => "Cấp tổ chức tối đa là cấp 3",

            //building
            self::BUILDING_NON_EXISTED => "Toà nhà không tồn tại",
            self::BUILDING_NOT_EMPTY => "Toà nhà không được để trống",
            self::BUILDING_NAME_NOT_EMPTY => "Tên toà nhà không được để trống",
            self::BUILDING_ADDRESS_NOT_EMPTY => "Địa chỉ toà nhà không được để trống",
            self::COMPLEX_ID_NOT_EMPTY => "Chung cư không được để trống",
            self::FINANCIAL_TOTAL_RATIO_NOT_VALID => "Tổng tỉ lệ phải bằng 100%",
            //apartment
            self::FLOOR_REQUIRED => "Số tầng là bắt buộc",
            self::FLOOR_NOT_INTEGER => "Số tầng phải là số nguyên",
            self::APT_NUMBER_REQUIRED => "Số căn hộ là bắt buộc",
            self::APT_NUMBER_LENGTH => "Số căn hộ không vượt quá 20 ký tự",
            self::APT_AREA_REQUIRED => "Diện tích căn hộ là bắt buộc",
            self::APT_AREA_NOT_NUMERIC => "Diện tích căn hộ phải là số",
            self::APT_AREA_MIN => "Diện tích căn hộ phải lớn hơn 0",
            self::APT_TYPE_REQUIRED => "Loại căn hộ là bắt buộc",
            self::APT_TYPE_INVALID => "Loại căn hộ không hợp lệ",
            self::APT_NUMBER_EXISTED => "Số căn hộ đã tồn tại trong tòa nhà này",

            self::ADDRESS_EXISTED => "Địa chỉ đã tồn tại!",
            // prices
            self::PRICE_NON_EXISTED => "Giá dịch vụ không tồn tại",
            self::YEAR_NOT_EMPTY => "Năm là bắt buộc",
            self::YEAR_NOT_INTEGER => "Năm phải là số nguyên",
            self::YEAR_MIN_MAX => "Năm phải từ 1900 đến 2100",
            self::MONTH_NOT_EMPTY => "Tháng là bắt buộc",
            self::MONTH_NOT_INTEGER => "Tháng phải là số nguyên",
            self::MONTH_MIN_MAX => "Tháng phải từ 1 đến 12",
            self::PRICE_PER_M2_NOT_EMPTY => "Đơn giá/m² là bắt buộc",
            self::PRICE_PER_M2_NOT_NUMERIC => "Đơn giá/m² phải là số",
            self::PRICE_HAS_REVENUE => "Đơn giá dịch vụ đã có hóa đơn",

            //revenue
            self::APARTMENT_ID_NOT_UUID => "ID căn hộ không hợp lệ",
            self::STATUS_INVALID => "Trạng thái không hợp lệ (unpaid, partial, paid, overpaid)",
            self::APARTMENT_ID_REQUIRED => "Căn hộ là bắt buộc",
            self::APARTMENT_ID_NOT_EXISTS => "Căn hộ không tồn tại",
            self::ORIGINAL_AMOUNT_REQUIRED => "Số tiền nghĩa vụ là bắt buộc",
            self::ORIGINAL_AMOUNT_NOT_NUMERIC => "Số tiền nghĩa vụ phải là số",
            self::REVENUE_NOT_UPDATE => "Khoản thu không thể chỉnh sửa",

            //expense
            self::TITLE_REQUIRED => "Tên sự việc là bắt buộc",
            self::CATEGORY_REQUIRED => "Hạng mục chi là bắt buộc",
            self::CATEGORY_INVALID => "Hạng mục chi không hợp lệ",
            self::AMOUNT_REQUIRED => "Số tiền là bắt buộc",
            self::AMOUNT_NOT_NUMERIC => "Số tiền phải là số",
            self::AMOUNT_MIN => "Số tiền phải >= 0",
            self::EXPENSE_NOT_UPDATE => "Khoản chi không thể cập nhật",
            self::DATE_INVALID => "Ngày không hợp lệ",
            self::DATE_TO_AFTER_FROM => "Ngày kết thúc phải sau hoặc bằng ngày bắt đầu",
            self::BUILDING_ID_REQUIRED => "Tòa nhà là bắt buộc",
            self::FUND_TYPE_REQUIRED => "Loại quỹ là bắt buộc",
            self::FUND_TYPE_INVALID => "Loại quỹ không hợp lệ",
            self::PAYMENT_METHOD_REQUIRED => "Phương thức thanh toán là bắt buộc",
            self::PAYMENT_METHOD_INVALID => "Phương thức thanh toán không hợp lệ",
            self::TRANSACTION_DATE_REQUIRED => "Ngày giao dịch là bắt buộc",

            //ledger
            self::VOUCHER_NOT_VALID => "Loại phiếu không hợp lệ. Chỉ chấp nhận PT hoặc PC",
            self::LEDGER_SUMMARY_EXISTED => "Số dư cuối kỳ đã được tạo",
            self::LEDGER_SUMMARY_NOT_EXISTED => "Số dư cuối kỳ chưa được tạo",

            //excel file
            self::FILE_REQUIRED => "File là bắt buộc",
            self::FILE_INVALID => "File tải lên không hợp lệ",
            self::FILE_EXCEL_INVALID_FORMAT => "File phải có định dạng xlsx hoặc xls",
            self::FILE_SIZE_EXCEEDED => "File không được vượt quá 50MB",

            //resident
            self::RESIDENT_EXISTED => "Thông tin cư dân đã tồn tại!",
            self::RESIDENT_GENDER_NOT_EMPTY => "Giới tính không được để trống",
            self::RESIDENT_FULLNAME_NOT_EMPTY => "Họ tên không được để trống",
            self::RESIDENT_EMAIL_NOT_EMPTY => "Email không được để trống",
            self::RESIDENT_BIRTHDAY_NOT_EMPTY => "Ngày sinh không được để trống",
            self::RESIDENT_PHONE_NOT_EMPTY => "Số điện thoại không được để trống",
            self::RESIDENT_RELATIONSHIP_NOT_EMPTY => "Mối quan hệ không được để trống",
            self::RESIDENT_CCCD_NOT_EMPTY => "Số CCCD không được để trống",
            //staff
            self::STAFF_EXISTED => "Thông tin thành viên BQL đã tồn tại!",
            self::STAFF_FULLNAME_NOT_EMPTY => "Họ tên không được để trống",
            self::STAFF_EMAIL_NOT_EMPTY => "Email không được để trống",
            self::STAFF_PHONE_NOT_EMPTY => "Số điện thoại không được để trống",
            self::STAFF_ORG_ID_NOT_EMPTY => "Phòng ban không được để trống",
            self::STAFF_ROLE_ID_NOT_EMPTY => "Vai trò không được để trống",
            //task
            self::TASK_INFO_INVALID => "Thông tin đề xuất chưa hợp lệ. Vui lòng kiểm tra lại thông tin (loại yêu cầu,tòa nhà)",
            self::TASK_TYPE_ID_NOT_EMPTY => "Loại yêu cầu không được để trống",
            self::TASK_BUILDING_ID_NOT_EMPTY => "Tòa nhà không được để trống",
            self::TASK_NAME_NOT_EMPTY => "Tên yêu cầu không được để trống",
            self::TASK_DESCRIPTION_NOT_EMPTY => "Mô tả không được để trống",
            //task type
            self::TASKTYPE_WORKFLOW_ID_NOT_EMPTY => "Quy trình không được để trống",
            self::TASKTYPE_PRIORITY_ID_NOT_EMPTY => "Độ ưu tiên không được để trống",
            self::TASKTYPE_NAME_NOT_EMPTY => "Tên loại yêu cầu không được để trống",
            self::TASKTYPE_DESCRIPTION_NOT_EMPTY => "Mô tả không được để trống",
            //permission
            self::PERMISSION_NAME_NOT_EMPTY => "Tên quyền không được để trống",
            self::PERMISSION_MODULE_NOT_EMPTY => "Module không được để trống",
            //role
            self::ROLE_NAME_NOT_EMPTY => "Tên vai trò không được để trống",
            self::ROLE_COMPLEX_ID_NOT_EMPTY => "Chung cư không được để trống",
            self::ROLE_DESCRIPTION_NOT_EMPTY => "Mô tả không được để trống",
            self::ROLE_USER_ID_NOT_EMPTY => "User không được để trống",
            self::ROLE_ID_NOT_EMPTY => "Vai trò không được để trống",
            //workflow
            self::WORKFLOW_COMPLEX_ID_NOT_EMPTY => "Chung cư không được để trống",
            self::WORKFLOW_NAME_NOT_EMPTY => "Tên quy trình không được để trống",
            self::WORKFLOW_DESCRIPTION_NOT_EMPTY => "Mô tả không được để trống",
            self::WORKFLOW_STATUS_NOT_EMPTY => "Trạng thái không được để trống",
            self::WORKFLOW_STEP_NOT_EMPTY => "Bước quy trình không được để trống",
            self::WORKFLOW_STEP_MIN => "Quy trình phải có ít nhất 1 bước",
            self::WORKFLOW_STEP_ORG_LEVEL_NOT_EMPTY => "Cấp tổ chức của bước không được để trống",
            self::WORKFLOW_STEP_ORDER_NOT_EMPTY => "Thứ tự bước không được để trống",
            self::WORKFLOW_STEP_DESCRIPTION_NOT_EMPTY => "Mô tả bước không được để trống",
            self::WORKFLOW_STEP_STATUS_NOT_EMPTY => "Trạng thái bước không được để trống",
            self::WORKFLOW_STEP_POSITION_NOT_EMPTY => "Vị trí không được để trống",
            self::WORKFLOW_STEP_POSITION_ARRAY => "Vị trí phải là một mảng",
            self::WORKFLOW_STEP_POSITION_ITEM_NOT_EMPTY => "Phần tử vị trí không được để trống"
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


            self::NOT_FOUND,
            self::NOT_DELETED,
            self::NOT_UPDATE,
            self::NOT_CREATED,

            self::ORG_NAME_NOT_EMPTY,
            self::ORG_NAME_UNIQUE,
            self::ORG_NAME_LENGTH,
            self::ORG_NAME_NOT_FOUND,
            self::ORG_DESCRIPTION_LENGTH,
            self::PARENT_ORG_EXISTED,
            self::MAX_ORG_LEVEL,


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
            self::EMAIL_CONTACT_NOT_EMPTY,
            self::PRICE_NON_EXISTED,
            self::YEAR_NOT_EMPTY,
            self::YEAR_NOT_INTEGER,
            self::YEAR_MIN_MAX,
            self::MONTH_NOT_EMPTY,
            self::MONTH_NOT_INTEGER,
            self::MONTH_MIN_MAX,
            self::PRICE_PER_M2_NOT_EMPTY,
            self::PRICE_PER_M2_NOT_NUMERIC,
            self::PRICE_HAS_REVENUE,

                //revenue
            self::APARTMENT_ID_NOT_UUID,
            self::STATUS_INVALID,
            self::APARTMENT_ID_REQUIRED,
            self::APARTMENT_ID_NOT_EXISTS,
            self::ORIGINAL_AMOUNT_REQUIRED,
            self::ORIGINAL_AMOUNT_NOT_NUMERIC,
            self::REVENUE_NOT_UPDATE,

                //expense
            self::TITLE_REQUIRED,
            self::CATEGORY_REQUIRED,
            self::CATEGORY_INVALID,
            self::AMOUNT_REQUIRED,
            self::AMOUNT_NOT_NUMERIC,
            self::AMOUNT_MIN,
            self::EXPENSE_NOT_UPDATE,
            self::DATE_INVALID,
            self::DATE_TO_AFTER_FROM,
            self::BUILDING_ID_REQUIRED,
            self::FUND_TYPE_REQUIRED,
            self::FUND_TYPE_INVALID,
            self::PAYMENT_METHOD_REQUIRED,
            self::PAYMENT_METHOD_INVALID,
            self::TRANSACTION_DATE_REQUIRED,

                //building
            self::BUILDING_NON_EXISTED,
            self::BUILDING_NOT_EMPTY,
            self::BUILDING_NAME_NOT_EMPTY,
            self::BUILDING_ADDRESS_NOT_EMPTY,
            self::COMPLEX_ID_NOT_EMPTY,
            self::FINANCIAL_TOTAL_RATIO_NOT_VALID,

                //apartment
            self::FLOOR_REQUIRED,
            self::FLOOR_NOT_INTEGER,
            self::APT_NUMBER_REQUIRED,
            self::APT_NUMBER_LENGTH,
            self::APT_AREA_REQUIRED,
            self::APT_AREA_NOT_NUMERIC,
            self::APT_AREA_MIN,
            self::APT_TYPE_REQUIRED,
            self::APT_TYPE_INVALID,
            self::APT_NUMBER_EXISTED,

                //ledgers
            self::VOUCHER_NOT_VALID,
            self::LEDGER_SUMMARY_EXISTED,
            self::LEDGER_SUMMARY_NOT_EXISTED,

                //excel file
            self::FILE_REQUIRED,
            self::FILE_INVALID,
            self::FILE_EXCEL_INVALID_FORMAT,
            self::FILE_SIZE_EXCEEDED,

                //resident
            self::RESIDENT_EXISTED,
            self::RESIDENT_GENDER_NOT_EMPTY,
            self::RESIDENT_FULLNAME_NOT_EMPTY,
            self::RESIDENT_EMAIL_NOT_EMPTY,
            self::RESIDENT_BIRTHDAY_NOT_EMPTY,
            self::RESIDENT_PHONE_NOT_EMPTY,
            self::RESIDENT_RELATIONSHIP_NOT_EMPTY,
            self::RESIDENT_CCCD_NOT_EMPTY,

                //staff
            self::STAFF_EXISTED,
            self::STAFF_FULLNAME_NOT_EMPTY,
            self::STAFF_EMAIL_NOT_EMPTY,
            self::STAFF_PHONE_NOT_EMPTY,
            self::STAFF_ORG_ID_NOT_EMPTY,
            self::STAFF_ROLE_ID_NOT_EMPTY,
                //task
            self::TASK_INFO_INVALID,
            self::TASK_TYPE_ID_NOT_EMPTY,
            self::TASK_BUILDING_ID_NOT_EMPTY,
            self::TASK_NAME_NOT_EMPTY,
            self::TASK_DESCRIPTION_NOT_EMPTY,
                //task type
            self::TASKTYPE_WORKFLOW_ID_NOT_EMPTY,
            self::TASKTYPE_PRIORITY_ID_NOT_EMPTY,
            self::TASKTYPE_NAME_NOT_EMPTY,
            self::TASKTYPE_DESCRIPTION_NOT_EMPTY,
                //permission
            self::PERMISSION_NAME_NOT_EMPTY,
            self::PERMISSION_MODULE_NOT_EMPTY,
                //role
            self::ROLE_NAME_NOT_EMPTY,
            self::ROLE_COMPLEX_ID_NOT_EMPTY,
            self::ROLE_DESCRIPTION_NOT_EMPTY,
            self::ROLE_USER_ID_NOT_EMPTY,
            self::ROLE_ID_NOT_EMPTY,
                //workflow
            self::WORKFLOW_COMPLEX_ID_NOT_EMPTY,
            self::WORKFLOW_NAME_NOT_EMPTY,
            self::WORKFLOW_DESCRIPTION_NOT_EMPTY,
            self::WORKFLOW_STATUS_NOT_EMPTY,
            self::WORKFLOW_STEP_NOT_EMPTY,
            self::WORKFLOW_STEP_MIN,
            self::WORKFLOW_STEP_ORG_LEVEL_NOT_EMPTY,
            self::WORKFLOW_STEP_ORDER_NOT_EMPTY,
            self::WORKFLOW_STEP_DESCRIPTION_NOT_EMPTY,
            self::WORKFLOW_STEP_STATUS_NOT_EMPTY,
            self::WORKFLOW_STEP_POSITION_NOT_EMPTY,
            self::WORKFLOW_STEP_POSITION_ARRAY,
            self::WORKFLOW_STEP_POSITION_ITEM_NOT_EMPTY,
            => 400,
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
